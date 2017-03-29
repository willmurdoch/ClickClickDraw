<section class="tweets">
  <h3 class="title">Irreverent Tweets</h3>
  <p>
  <?php //Call twitteroauth library and set credentials
    require 'twitteroauth/autoload.php';
    use Abraham\TwitterOAuth\TwitterOAuth;
    $consumer_key = 'X91A01MHzmyC918nRnqvsX18c';
    $consumer_secret = 'qwm6u7DsQY2gdzoVcqmXNHM8ks60tgaWPVh9ahk4gWoKCN53qI';
    $access_token = '2384001006-bKOA3PUrVIhDNmT0LRVi8TdpVTT0mnRcDA6AP3g';
    $access_token_secret = 'nkpFHgBaJ3BDfGuYF92egdMF5RQ7L5F1OcT2t5HTFMWzc';

    //Establish connection and get latest tweet's text
    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    $content = $connection->get("account/verify_credentials");
    $statuses = $connection->get("statuses/user_timeline", ["count" => 5, "exclude_replies" => true]);
    $tweet = $statuses[0]->text;

    //Convert hash tags and links to work like you'd expect
    $tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
    $tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
    $tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);

    echo $tweet; ?>
  </p>
  <a class="btn" href="#" target="_blank"></a>
</section>
