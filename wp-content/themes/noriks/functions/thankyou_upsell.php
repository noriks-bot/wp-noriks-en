<?php
/**
 * Thank You — Post-Purchase Upsell System
 * 
 * - COD orders only: upsell popup shown
 * - COD orders go to "primary-hold" for 5 min (upsell window), then auto → processing
 * - Non-COD orders: no upsell, normal flow (processing/completed)
 * - 50% off SALE price, server-side calculated
 * - Metadata: _noriks_upsell = "thank you upsell"
 */
if ( ! defined( 'ABSPATH' ) ) exit;


// ─── 1. Register custom order status "primary-hold" ─────────────────────

add_action( 'init', 'noriks_register_primary_hold_status' );
function noriks_register_primary_hold_status() {
    register_post_status( 'wc-primary-hold', array(
        'label'                     => 'Primary Hold',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Primary Hold <span class="count">(%s)</span>', 'Primary Hold <span class="count">(%s)</span>' ),
    ));
}

// Add to WC status list
add_filter( 'wc_order_statuses', 'noriks_add_primary_hold_to_statuses' );
function noriks_add_primary_hold_to_statuses( $statuses ) {
    $statuses['wc-primary-hold'] = 'Primary Hold';
    return $statuses;
}


// ─── 2. COD orders → primary-hold (instead of processing) ───────────────

add_action( 'woocommerce_thankyou', 'noriks_set_cod_primary_hold', 1 );
function noriks_set_cod_primary_hold( $order_id ) {
    if ( ! $order_id ) return;
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;

    // Only COD orders
    if ( $order->get_payment_method() !== 'cod' ) return;

    // Only if currently on-hold or processing (fresh order)
    $status = $order->get_status();
    if ( ! in_array( $status, array( 'on-hold', 'processing', 'pending' ) ) ) return;

    // Don't re-apply if already in primary-hold
    if ( $status === 'primary-hold' ) return;

    $order->update_status( 'primary-hold', 'Upsell window: 5 min hold for post-purchase offers.' );

    // Schedule auto-transition to processing after 5 minutes.
    // Use Action Scheduler when available because it is more reliable than plain WP-Cron.
    if ( ! wp_next_scheduled( 'noriks_primary_hold_to_processing', array( $order_id ) ) ) {
        wp_schedule_single_event( time() + 300, 'noriks_primary_hold_to_processing', array( $order_id ) );
    }

    if ( function_exists( 'as_next_scheduled_action' ) && function_exists( 'as_schedule_single_action' ) ) {
        if ( ! as_next_scheduled_action( 'noriks_primary_hold_to_processing', array( 'order_id' => $order_id ), 'noriks-primary-hold' ) ) {
            as_schedule_single_action( time() + 300, 'noriks_primary_hold_to_processing', array( 'order_id' => $order_id ), 'noriks-primary-hold' );
        }
    }
}

// Auto-transition: primary-hold → processing after 5 min
add_action( 'noriks_primary_hold_to_processing', 'noriks_transition_to_processing' );
function noriks_transition_to_processing( $order_id ) {
    if ( is_array( $order_id ) ) {
        $order_id = isset( $order_id['order_id'] ) ? absint( $order_id['order_id'] ) : 0;
    }

    $order_id = absint( $order_id );
    if ( ! $order_id ) return;

    $order = wc_get_order( $order_id );
    if ( ! $order ) return;

    // Only transition if still in primary-hold
    if ( $order->get_status() !== 'primary-hold' ) return;

    $order->update_status( 'processing', 'Upsell window expired — auto-transitioned to processing.' );
}


// ─── FAILSAFE: scheduled background sweep for stuck primary-hold orders ───

add_filter( 'cron_schedules', 'noriks_add_five_minute_cron_schedule' );
function noriks_add_five_minute_cron_schedule( $schedules ) {
    if ( ! isset( $schedules['noriks_every_five_minutes'] ) ) {
        $schedules['noriks_every_five_minutes'] = array(
            'interval' => 300,
            'display'  => __( 'Every 5 Minutes', 'textdomain' ),
        );
    }

    return $schedules;
}

add_action( 'init', 'noriks_schedule_primary_hold_failsafe_cron' );
function noriks_schedule_primary_hold_failsafe_cron() {
    if ( wp_next_scheduled( 'noriks_primary_hold_failsafe_cron' ) ) {
        // Keep Action Scheduler fallback in place even if WP-Cron is already scheduled.
        if ( function_exists( 'as_next_scheduled_action' ) && ! as_next_scheduled_action( 'noriks_primary_hold_failsafe_cron', array(), 'noriks-primary-hold' ) ) {
            as_schedule_recurring_action( time() + 300, 300, 'noriks_primary_hold_failsafe_cron', array(), 'noriks-primary-hold' );
        }
        return;
    }

    wp_schedule_event( time() + 300, 'noriks_every_five_minutes', 'noriks_primary_hold_failsafe_cron' );

    if ( function_exists( 'as_next_scheduled_action' ) && function_exists( 'as_schedule_recurring_action' ) ) {
        if ( ! as_next_scheduled_action( 'noriks_primary_hold_failsafe_cron', array(), 'noriks-primary-hold' ) ) {
            as_schedule_recurring_action( time() + 300, 300, 'noriks_primary_hold_failsafe_cron', array(), 'noriks-primary-hold' );
        }
    }
}

add_action( 'noriks_primary_hold_failsafe_cron', 'noriks_failsafe_primary_hold_sweep' );
function noriks_failsafe_primary_hold_sweep() {
    // Only run once per minute in case multiple cron runners overlap.
    if ( get_transient( 'noriks_ph_sweep_lock' ) ) {
        return;
    }

    set_transient( 'noriks_ph_sweep_lock', 1, 60 );

    $orders = wc_get_orders( array(
        'status'     => 'primary-hold',
        'limit'      => 20,
        'date_created' => '<' . ( time() - 300 ), // older than 5 min
    ));

    foreach ( $orders as $order ) {
        $order->update_status( 'processing', 'Failsafe: primary-hold exceeded 5 min — auto-moved to processing.' );
    }
}


// ─── FAILSAFE 2: if an overdue primary-hold order is manually saved, resolve it ───

add_action( 'woocommerce_before_order_object_save', 'noriks_failsafe_on_order_save' );

function noriks_failsafe_on_order_save( $order ) {
    // When any order is saved, also check for stuck primary-holds
    if ( $order->get_status() === 'primary-hold' ) {
        $created = $order->get_date_created();
        if ( $created && ( time() - $created->getTimestamp() ) > 300 ) {
            $order->set_status( 'processing' );
            $order->add_order_note( 'Failsafe: primary-hold auto-resolved on save.' );
        }
    }
}


// ─── 3. AJAX: Manual fix stuck orders + auto-release ─────────────────────

add_action( 'wp_ajax_noriks_release_primary_hold', 'noriks_release_primary_hold' );
add_action( 'wp_ajax_nopriv_noriks_release_primary_hold', 'noriks_release_primary_hold' );

function noriks_release_primary_hold() {
    $order_id = absint( $_POST['order_id'] ?? 0 );
    if ( ! $order_id ) wp_send_json_error( 'Missing order_id' );

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Order not found' );
    if ( $order->get_status() !== 'primary-hold' ) wp_send_json_success( 'Already released' );

    $order->update_status( 'processing', 'Released from primary-hold (timer expired on client).' );
    wp_send_json_success( 'Released to processing' );
}


// ─── 4. AJAX: Refresh order items HTML ───────────────────────────────────

add_action( 'wp_ajax_noriks_refresh_order_items', 'noriks_refresh_order_items' );
add_action( 'wp_ajax_nopriv_noriks_refresh_order_items', 'noriks_refresh_order_items' );

function noriks_refresh_order_items() {
    $order_id = absint( $_POST['order_id'] ?? 0 );
    if ( ! $order_id ) wp_send_json_error( 'Missing order_id' );

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Order not found' );

    // Build items HTML
    $items_html = '';
    foreach ( $order->get_items() as $item ) {
        $qty = $item->get_quantity();
        $meta_parts = array();
        foreach ( $item->get_formatted_meta_data( '_', true ) as $m ) {
            $meta_parts[] = wp_strip_all_tags( $m->display_key . ': ' . $m->display_value );
        }
        $is_upsell = $item->get_meta( '_noriks_upsell' ) === 'thank you upsell';
        $items_html .= '<div class="ty-item">';
        $items_html .= '<div>';
        $items_html .= '<div class="ty-item-name">' . $qty . '× ' . esc_html( $item->get_name() ) . '</div>';
        if ( $meta_parts ) {
            $items_html .= '<div class="ty-item-meta">' . esc_html( implode( ', ', $meta_parts ) ) . '</div>';
        }
        $items_html .= '</div>';
        $items_html .= '<div style="display:flex;align-items:center;gap:8px;">';
        $items_html .= '<div class="ty-item-price">' . $order->get_formatted_line_subtotal( $item ) . '</div>';
        /* remove button disabled */
        $items_html .= '</div>';
        $items_html .= '</div>';
    }

    // Build totals HTML
    $totals_html = '<div class="ty-totals">';
    foreach ( $order->get_order_item_totals() as $key => $total ) {
        $class = $key === 'order_total' ? 'ty-row ty-total-final' : 'ty-row';
        $totals_html .= '<div class="' . $class . '">';
        $totals_html .= '<span class="ty-row-label">' . $total['label'] . '</span>';
        $totals_html .= '<span class="ty-row-value">' . $total['value'] . '</span>';
        $totals_html .= '</div>';
    }
    $totals_html .= '</div>';

    wp_send_json_success( array(
        'items_html'  => $items_html . $totals_html,
        'item_count'  => $order->get_item_count(),
        'total'       => $order->get_formatted_order_total(),
    ));
}


// ─── 5. AJAX: Remove upsell item from order ─────────────────────────────

add_action( 'wp_ajax_noriks_remove_upsell', 'noriks_remove_upsell' );
add_action( 'wp_ajax_nopriv_noriks_remove_upsell', 'noriks_remove_upsell' );

function noriks_remove_upsell() {
    $order_id = absint( $_POST['order_id'] ?? 0 );
    $item_id  = absint( $_POST['item_id'] ?? 0 );
    if ( ! $order_id || ! $item_id ) wp_send_json_error( 'Missing data' );

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Order not found' );

    $item = $order->get_item( $item_id );
    if ( ! $item ) wp_send_json_error( 'Item not found' );

    // Only allow removing upsell items
    if ( $item->get_meta( '_noriks_upsell' ) !== 'thank you upsell' ) {
        wp_send_json_error( 'Only upsell items can be removed' );
    }

    // Only allow while in primary-hold
    if ( $order->get_status() !== 'primary-hold' ) {
        wp_send_json_error( 'The time for changes has expired' );
    }

    $product_name = $item->get_name();
    $order->remove_item( $item_id );
    $order->calculate_totals();
    $order->save();

    $order->add_order_note( sprintf( 'Upsell removed: %s', $product_name ) );

    wp_send_json_success( array( 'message' => 'Removed' ) );
}


// ─── 6. AJAX: Add upsell product to order ───────────────────────────────

add_action( 'wp_ajax_noriks_add_upsell', 'noriks_handle_add_upsell' );
add_action( 'wp_ajax_nopriv_noriks_add_upsell', 'noriks_handle_add_upsell' );

function noriks_handle_add_upsell() {
    $order_id     = absint( $_POST['order_id'] ?? 0 );
    $product_id   = absint( $_POST['product_id'] ?? 0 );
    $variation_id = absint( $_POST['variation_id'] ?? 0 );
    $nonce        = $_POST['nonce'] ?? '';

    if ( ! wp_verify_nonce( $nonce, 'noriks_upsell_' . $order_id ) ) {
        wp_send_json_error( 'Invalid request' );
    }

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Order not found' );

    // Only allow upsell on COD orders in primary-hold
    if ( $order->get_payment_method() !== 'cod' ) {
        wp_send_json_error( 'Upsell only available for cash on delivery' );
    }
    if ( $order->get_status() !== 'primary-hold' ) {
        wp_send_json_error( 'The time for adding has expired' );
    }

    // Time limit: 5 min from order creation (safety check)
    $created = $order->get_date_created();
    if ( $created && ( time() - $created->getTimestamp() ) > 330 ) { // 5.5 min grace
        wp_send_json_error( 'The time for adding has expired' );
    }

    // Get the actual product (variation or simple)
    $product = $variation_id ? wc_get_product( $variation_id ) : wc_get_product( $product_id );
    if ( ! $product ) wp_send_json_error( 'Product not found' );

    // Duplicate check
    $check_product_id = $variation_id ? $product_id : $product->get_id();
    foreach ( $order->get_items() as $item ) {
        $item_product_id = $item->get_product_id();
        $item_variation_id = $item->get_variation_id();
        if ( $item_product_id == $check_product_id || ( $variation_id && $item_variation_id == $variation_id ) ) {
            if ( $item->get_meta( '_noriks_upsell' ) ) {
                wp_send_json_error( 'You have already added this product' );
            }
        }
    }

    // ─── Calculate 50% off SALE price ───
    $sale_price = (float) $product->get_sale_price();
    $current_price = (float) $product->get_price();

    if ( $sale_price && $current_price ) {
        $active_price = min( $sale_price, $current_price );
    } else {
        $active_price = $current_price ?: $sale_price;
    }

    if ( ! $active_price ) {
        $active_price = (float) $product->get_regular_price();
    }
    if ( ! $active_price ) {
        wp_send_json_error( 'Product price not available' );
    }

    $quantity = max( 1, absint( $_POST['quantity'] ?? 3 ) );
    // Prices depend on product type (bokserice vs majice)
    $bokserice_prices = array( 1 => 7.99, 3 => 19.99, 5 => 29.99 );
    $majice_prices    = array( 1 => 12.99, 3 => 29.99, 6 => 39.99 );
    $is_majice = strpos(strtolower($product->get_name()), 'majic') !== false;
    $qty_prices = $is_majice ? $majice_prices : $bokserice_prices;
    $total_price = isset( $qty_prices[$quantity] ) ? $qty_prices[$quantity] : $active_price;
    $upsell_price = $total_price / $quantity;

    // Add to order
    $item_id = $order->add_product( $product, $quantity, array(
        'subtotal' => $upsell_price * $quantity,
        'total'    => $upsell_price * $quantity,
    ));

    if ( ! $item_id ) wp_send_json_error( 'Error adding item' );

    // Mark as upsell
    $item = $order->get_item( $item_id );
    $upsell_type = sanitize_text_field( $_POST['upsell_type'] ?? 'post_purchase_step1' );
    $item->add_meta_data( '_noriks_upsell', $upsell_type, true );
    $item->save();

    $order->calculate_totals();
    $order->save();

    $order->add_order_note(
        sprintf(
            'Thank you upsell: %s added with 50%% discount — sale price: %s, upsell price: %s',
            $product->get_name(),
            wc_price( $active_price ),
            wc_price( $upsell_price )
        )
    );

    wp_send_json_success( array(
        'message'      => 'Dodano',
        'product_name' => $product->get_name(),
        'upsell_price' => $upsell_price,
        'total'        => $order->get_formatted_order_total(),
    ));
}
