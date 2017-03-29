<footer>
  <a href="<?php echo get_site_url(); ?>"><img id="footer-logo" src="<?php echo get_template_directory_uri(); ?>/images/logo_white.png" alt="Click Click Draw" /></a>
  <div class="social-icons">
    <a href="https://twitter.com/c_illustrates" target="_blank"></a>
    <a href="http://www.linkedin.com/in/chrisfernandez" target="_blank"></a>
    <a href="http://dribbble.com/c_illustrates" target="_blank"></a>
    <a href="mailto:chris@clickclickdraw.com" target="_blank"></a>
    <a href="https://instagram.com/cillustrates" target="_blank"></a>
  </div>
  <div class="footer-nav">
  <?php
    $args = array('menu' => 'Mobile/Footer Nav');
    wp_nav_menu($args);
  ?>
  </div>
  <p>&copy;<?php echo date("Y"); ?> Click.Click.Draw. All Rights Reserved.<br>
    All the way live <a href="#" target="_blank">from the 215</a>

</footer>
<?php wp_footer(); ?>
</body>
</html>
