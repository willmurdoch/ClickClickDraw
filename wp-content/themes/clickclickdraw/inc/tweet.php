<section class="tweets">
  <div class="scaler">
    <h3 class="title"><span>Irreverent Tweets</span></h3>
    <p>
    <?php //Call twitteroauth library and set credentials
      require 'twitteroauth/autoload.php';
      use Abraham\TwitterOAuth\TwitterOAuth;
      $consumer_key = '1lIq3sFLTBM3WRguCQx3KA1dk';
      $consumer_secret = 'yItnl5TWGUIZIPSGuomD9vJvaZ3coSOHyM8DZIheJFaf1ypWG1';
      $access_token = '108376989-Dmcojzi8hQIzatC13AcwBzLRSqIstnTNhJpr4Nrg';
      $access_token_secret = 'Ki2VidvtvO1wEK9dCN8EYgEMu5fsuu6CHOTw47bsDqUpN';

      //Establish connection and get latest tweet's text
      $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
      $content = $connection->get("account/verify_credentials");
      $statuses = $connection->get("statuses/user_timeline", ["count" => 5, "exclude_replies" => true]);
      $date = $statuses[0]->created_at;
      $date = DateTime::createFromFormat('M j', $date);
      $tweet = $statuses[0]->text;

      //Convert hash tags and links to work like you'd expect
      $tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
      $tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
      $tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);

      echo $tweet;
      echo '<span class="date">'.$date.'</span>';
      ?>
    </p>
    <a class="btn" href="https://twitter.com/c_illustrates" target="_blank">@C_ILLUSTRATES</a>
  </div>
</section>
