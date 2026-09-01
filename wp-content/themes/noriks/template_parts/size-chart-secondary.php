<!-- Size Chart Modal Styles -->
<style>
/* --- Base UI bits you already had --- */
#size-suggestion-result { border: 1px solid #ccc; }
.body-type-options { display: flex; justify-content: space-between; gap: 5px; }
.body-type-option {
  display: flex; flex-direction: column; align-items: center; cursor: pointer;
  padding: 5px; border: 1px solid #ccc; border-radius: 2px; width: auto; text-align: center;
  transition: all 0.2s ease;
}
.body-type-option input { display: none; }
.body-type-option img { width: 100px; height: 100px; margin-bottom: 5px; }
.body-type-option:hover { background-color: #e0e0e0; }
.body-type-option.selected { border: 2px solid #f39c13; background-color: #fff3d6; }
.slike-mobile-only { display: flex; }

/* --- Modal base --- */
/* Height is AUTO on ALL screens now (desktop same as mobile). */
#custom-size-chart-modal-secondary {
  display: none;              /* hidden by default; shown via .show */
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 90%;
  max-width: 800px;
  height: auto;               /* << auto height */
  background: #fff;
  border-radius: 3px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.25);
  z-index: 9999999;
  overflow: visible;          /* no forced scrollbars */
  font-family: sans-serif;
}

/* Single-column content wrapper (only image) */
.size-chart-left {
  display: flex;              /* center the image inside */
  align-items: center;        /* vertical center */
  justify-content: center;    /* horizontal center */
  background: white;
  padding: 0;
}

/* Image fills modal width, keeps aspect ratio */
.size-chart-left img {
  display: block;
  width: 100%;
  height: auto;
  object-fit: contain;
  margin: 0;                  /* ensure no offsets */
}

/* When opened */

#custom-size-chart-modal-secondary.show { display: block; }

/* --- Mobile tweaks (kept minimal) --- */
@media (max-width: 768px) {
  .info-box-desktop { display: none !important; }
  .second-one, .third-one { display: inline-block; width: 49%; }
  #size-suggestion-result { padding-top: 3px; padding-bottom: 3px; }
  .form-title { margin-top: 4px; text-align: left; padding-left: 10px; font-size: 15px; }
  .size-chart-field { margin-top: 10px; text-align: left; }
  .size-chart-field label { text-align: left; }

  /* Modal stays auto-height on mobile too; nothing else needed */
}

/* Desktop cleanups */
@media (min-width: 769px) {
  .slike-mobile-only { display: none !important; }
  .info-box-mobile  { display: none !important; }
}
</style>

<!-- Modal HTML -->
<div id="custom-size-chart-modal-secondary" aria-modal="true" role="dialog">
  <span id="close-size-chart-x-secondary" style="position: absolute;
    top: 5px; right: 5px; font-size: 24px; font-weight: bold; cursor: pointer;
    background: black; border-radius: 1px; width: 40px; height: 40px; text-align: center; color: white;">&times;</span>

  <div class="size-chart-left">
      
     
      
    <div class="size-chart-titlebar" style="padding:14px 16px;border-bottom:1px solid #eee;"><h2 style="margin:0;font-size:18px;">Size Chart</h2></div>
<div style="padding:16px;width:100%;">
<div class="noriks-bx-wrap">
          <table class="noriks-bx-t">
            <thead>
              <tr><th>Size</th><th>S</th><th>M</th><th>L</th><th>XL</th><th>2XL</th><th>3XL</th><th>4XL</th></tr>
            </thead>
            <tbody>
              <tr><th>a</th><td>32</td><td>34</td><td>36</td><td>38</td><td>40</td><td>42</td><td>44</td></tr>
              <tr><th>b</th><td>33,5</td><td>34,5</td><td>35,5</td><td>36,5</td><td>37,5</td><td>38,5</td><td>39,5</td></tr>
              <tr><th>c</th><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td></tr>
              <tr><th>d</th><td>29</td><td>30</td><td>31</td><td>32</td><td>33</td><td>34</td><td>35</td></tr>
            </tbody>
          </table>
        </div>
        <img class="noriks-bx-fig" src="<?php echo esc_url( get_template_directory_uri() . '/img/boxers-measure.webp' ); ?>"
             alt="Boxer measurements: a, b, c, d" loading="lazy">
        <p class="noriks-bx-note"><strong>Choose the same size you normally wear.</strong><br>Measurements are in centimetres. Sizes may vary by &plusmn;1&ndash;2 cm. If you are between two sizes, pick the smaller one &mdash; the fabric is stretchy.</p>
</div>
      
      
      
       
       
      
      
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const modal2 = document.getElementById("custom-size-chart-modal-secondary");
  const openBtn2 = document.getElementById("open-size-chart-secondary");
  const closeX = document.getElementById("close-size-chart-x-secondary");

  // Open using a class so CSS controls display across breakpoints
  openBtn2?.addEventListener("click", function (e) {
    e.preventDefault();
    modal2.classList.add("show");
    
    console.log("cliclk");
    
  });

  // Close
  closeX?.addEventListener("click", function () {
    modal2.classList.remove("show");
  });

  // Optional: close on ESC
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") modal2.classList.remove("show");
  });
});
</script>
