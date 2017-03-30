<div class="social-bar">
  <div id="fb-root"></div>
  <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=181984485193649";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>
  <div class="socialBtn facebook">
    <div class="fb-like" data-href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
  </div>
  <div class="socialBtn facebook">
    <div class="fb-share-button" data-href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" data-layout="button" data-action="share"></div>
  </div>
  <div class="socialBtn twitterBtn">
    <a href="https://twitter.com/share" class="twitter-share-button"></a>
    <script>
      !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
    </script>
  </div>
  <a class="socialBtn mail" title="Email this story" href="mailto:?subject=<?php echo strip_tags(get_the_title()); ?>&body=See it here: <?php echo get_the_permalink(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/email_counter.png" alt="share by email" /></a>
</div>
