  <?php

  //Get footer columns
  $footerCol1 = wp_get_nav_menu_items('Footer Column 1');
  $footerCol2 = wp_get_nav_menu_items('Footer Column 2');
  $footerCol3 = wp_get_nav_menu_items('Footer Column 3');
  $footerCol4 = wp_get_nav_menu_items('Footer Column 4');

  ?>
  <footer id="mainFooter">
    <div class="footer-container">
      <div class="footer-nav-container">
        <?php include get_template_directory().'/inc/forms/newsletter.php'; ?>
        <div class="footer-linkage">
          <div class="footer-branding">
            <a href="<?php echo get_site_url(); ?>">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/footer/footer_logo.png" alt="National Lacrosse League" />
            </a>
            <div class="footer-social">
              <?php
              $socialNav = wp_get_nav_menu_items('Social Media');
              foreach($socialNav as $socialBtn):
                echo '<a href="'.$socialBtn->url.'" target="_blank"></a>';
              endforeach; ?>
            </div>
          </div>
          <div class="footer-pages">
            <?php
              function footerLinks($list){
                foreach($list as $footerLink){
                  if($footerLink->menu_item_parent) $linkClass = ' class="child"';
                  else $linkClass = '';
                  if($footerLink->url != '')
                    if(strpos($footerLink->url, '.pdf') !== false || strpos($footerLink->url, 'nll.') === false)
                      echo '<li'.$linkClass.'><a href="'.$footerLink->url.'" target="_blank">'.$footerLink->title.'</a></li>';
                    else echo '<li'.$linkClass.'><a href="'.$footerLink->url.'">'.$footerLink->title.'</a></li>';
                  else
                    echo '<li'.$linkClass.'><span>'.$footerLink->title.'</span></li>';
                }
              }
            ?>
            <ul class="page-list w25">
              <?php footerLinks($footerCol1); ?>
            </ul>
            <ul class="page-list w25">
              <?php footerLinks($footerCol2); ?>
            </ul>
            <ul class="page-list w25">
              <?php footerLinks($footerCol3); ?>
            </ul>
            <ul class="page-list w25">
              <?php footerLinks($footerCol4); ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-copyright-container">
        <?php $bottomNav = wp_get_nav_menu_items('Footer Bottom Nav'); ?>
        <div class="tertiary-nav">
          <?php
          $i = 0;
          foreach($bottomNav as $footerLink){
            $i++;
            echo '<a href="'.$footerLink->url.'">'.$footerLink->title.'</a>';
            if($i < count($bottomNav)) echo ' <span>|</span> ';
          }
          ?>
        </div>
        <small>&copy; <?php echo date('Y'); ?> National Lacrosse League. All trademarks and copyrights used by permission. All rights reserved. </small>
      </div>
    </div>
  </footer>
</div>
<!--Get theme URL for relative JS links-->
<div id="theme-url" data-dir="<?php echo get_template_directory_uri(); ?>"></div>
<!-- <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-6353414-1', 'auto');
  ga('send', 'pageview');

</script> -->
<?php wp_footer(); ?>
</body>
</html>
