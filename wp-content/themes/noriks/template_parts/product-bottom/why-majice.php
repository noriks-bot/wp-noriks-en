<?php
/**
 * NORIKS EN — why sekcija za majice
 *
 * Vsebina izlusena iz nekdanjega monolita single-product-bottom-nicer.php.
 * Pogoj prikaza je zdaj v dispecerju (noriks_is_type), ne tukaj.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<section class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
        <video 
          autoplay muted loop playsinline 
          class="why-video">
          <source src="https://noriks.com/wp-content/uploads/2025/09/noriks_gif_en_2-1.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
        <?php echo get_field( 'singlepp_content_part_h1', 'options' ); ?>
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong><?php echo get_field( 'singlepp_content_part_t_1', 'options' ); ?></strong></p>
        <p class="description"><?php echo get_field( 'singlepp_content_part_t_2', 'options' ); ?></p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong><?php echo get_field( 'singlepp_content_part_t_3', 'options' ); ?></strong></p>
        <p class="description"><?php echo get_field( 'singlepp_content_part_t_4', 'options' ); ?></p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong><?php echo get_field( 'singlepp_content_part_t_5', 'options' ); ?></strong></p>
        <p class="description"><?php echo get_field( 'singlepp_content_part_t_6', 'options' ); ?></p>
      </div>
    </div>

  </div>
</section>


  
  
  
  
  
  
<!-- table section -->

  
  
<section class="comparison-section" style="padding-top: 30px;" >
    <div class="comparison-intro">
     <!-- <h4 class="highlight"><?php echo get_field("_comp_table_t1", "options"); ?></h4>-->
      <h1 style="color:white;"><?php echo get_field("_comp_table_t2", "options"); ?></h1>
      <p style="    opacity: 0.6;" class="note"><?php echo get_field("_comp_table_t3", "options"); ?></p>
    </div>
  </section>
  
  
<section class="comparison-table-section">
 
 <div class="comparison-container">
   <table class="comparison-table">
      <thead>
        <tr>
          <th></th>
          <th class="brand-column">
                <?php echo get_field("_comp_table_inside_1", "options"); ?><br>
            <div class="price"><?php echo get_field("_comp_table_inside_3", "options"); ?></div>
          </th>
          <th class="other-brand"><?php echo get_field("_comp_table_inside_2", "options"); ?><br><span><?php echo get_field("_comp_table_inside_4", "options"); ?></span></th>
        </tr>
      </thead>
      <tbody>
          
          <?php
          $_comp_table_fieldlines = get_field("_comp_table_fieldlines","options");
          ?>
          
            <?php if ($_comp_table_fieldlines): ?>
             <?php foreach ($_comp_table_fieldlines as $item): ?>
          
                    <tr>
                      <td><?php echo $item['text']; ?></td>
                      <td class="bg-best"><span  style="background: #496d8f;" class="checkmark">✔</span></td>
                      <td class="bg-bad"><span class="crossmark">✖</span></td>
                    </tr>
                    
            <?php endforeach; ?>
        <?php endif; ?>
       
       
      </tbody>
    </table>

    <p style="    opacity: 0.6;" class="small-note">
      <?php echo get_field("_comp_table_bottom_text", "options"); ?>
    </p>
  </div>
</section>



<section class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
          <img style="width: 100%;       
    aspect-ratio: 1/1; 
    object-fit: cover;  " src="<?php echo get_template_directory_uri(); ?>/img/majice-3 (1).jpeg">
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
        WHY WILL THIS T-SHIRT BECOME YOUR STANDARD?
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Designed for real life
</strong></p>
        <p class="description">This t-shirt is made for all-day wear, from morning to evening. It requires no adjustment or thought — it simply looks good in any situation.
</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>A cut that understands the body
</strong></p>
        <p class="description">The cut is developed to follow the body's lines without constriction and accentuates where it should. The result is a neat, confident look without any discomfort.
</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Feel the difference from the very first wear
</strong></p>
        <p class="description">The material is soft, lightweight, and breathable on the skin. After the first wear, it's clear why this t-shirt quickly becomes the one you reach for most often.
</p>
      </div>
    </div>

  </div>
</section>

  
<!-- table section -->
