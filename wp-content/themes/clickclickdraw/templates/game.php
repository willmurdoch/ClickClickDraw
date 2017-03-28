<?php
/**
 * Template Name: Game
 *
 * @package WordPress
 * @subpackage National Lacrosse League

 * @since National Lacrosse League 0.0.1
 */
include get_template_directory().'/inc/pointstreak/globals.php';

$gameID = $_GET['id'];
$url = 'https://api.pointstreak.com/lacrosse/game/boxscore/'.$gameID.'/json';
$opts = array(
  'http'=>array(
    'method' => "GET",
    'header' => 'apikey: Xhk8vHpMfjWx1GIRkNZs',
    'timeout' => 100
  )
);
$context = stream_context_create($opts);
$content = file_get_contents($url, false, $context);
$gameDets = json_decode($content, true);

checkArray($gameDets);

// if(isset($gameDets['boxscore']['scoringsummary'])){
//   $gameGoals = $gameDets['boxscore']['scoringsummary']['goal'];
//   $gameLeaders = array();
//   foreach($gameGoals as $player){
//     if()
//     $gameLeaders['shots']
//   }
// }


//Forced live game for testing
//if($gameID == '2965416') $gameDets['boxscore']['gameinformation']['gamestatus']['status'] = 'LIVE';

$mySeason = $gameDets['boxscore']['season']['seasonid'];
$teamDets = $gameDets['boxscore']['teamstats'];
$basicDets = $gameDets['boxscore']['gameinformation'];
$playerDets = $gameDets['boxscore']['teamroster'];
if($basicDets['gamestatus']['status'] == 'FINAL'){
  if(isset($gameDets['boxscore']['scoringsummary']['goal'][0]['videourl'])){
    $x = 0;
    foreach($gameDets['boxscore']['scoringsummary']['goal'] as $game){
      if(isset($game['videourl'])){
        $basicDets['video'] = $gameDets['boxscore']['scoringsummary']['goal'][$x]['videourl'];
      }
      $x++;
    }
  }
}

$path = getenv('HTTP_HOST');
if($path == 'localhost'){
  $path =  realpath(dirname(__FILE__).'/..').'\inc\pointstreak\/';
  $temp_files = scandir($path);
  $scheduleString = file_get_contents($path.'json/schedules/schedule'.$mySeason.'.json');
  $schedule = json_decode($scheduleString, true);
}
else{
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
    $protocol  = "https://";
  else
    $protocol  = "http://";
  $path = $protocol.$path.'/wp-content/themes/nll/inc/pointstreak/';
  $schedule = get_data($path.'json/schedules/schedule'.$mySeason.'.json');
}

$currentGame = array();
foreach($schedule as $game){
 if($game['gameid'] == $gameDets['boxscore']['gameid']){
   $currentGame = $game;
   break;
 }
}

//Home Details
$homeID = $currentGame['home']['id'];
$homeUrl = 'https://api.pointstreak.com/lacrosse/team/roster/'.$homeID.'/'.$mySeason.'/json';
$homeContent = file_get_contents($homeUrl, false, $context);
$homeDets = json_decode($homeContent, true);
$homeDets = $homeDets['teamroster']['team'];

//Get season-wide stats
$stats = array();
function sortStats($teamArray, $team){
  global $stats;
  $i = 0;
  //Check for top player stats (season-wide)
  foreach($teamArray['players']['player'] as $player){
    if(!isset($stats[$team])){
      $stats[$team]['goals'] = 0;
      $stats[$team]['shotsongoal'] = 0;
      $stats[$team]['looseballs'] = 0;
      $stats[$team]['faceoffs'] = array();
      $stats[$team]['faceoffs']['won'] = 0;
      $stats[$team]['faceoffs']['total'] = 0;
      $stats[$team]['toppoints'] = array();
      $stats[$team]['toppoints']['value'] = 0;
      $stats[$team]['topgoals'] = array();
      $stats[$team]['topgoals']['value'] = 0;
      $stats[$team]['topassists'] = array();
      $stats[$team]['topassists']['value'] = 0;
      $stats[$team]['topsaves'] = array();
      $stats[$team]['topsaves']['value'] = 0;
      $stats[$team]['captain'] = array();
    }
    $stats[$team]['goals'] += $player['goals'];
    $stats[$team]['shotsongoal'] += $player['shotsongoal'];
    $stats[$team]['looseballs'] += $player['looseballs'];
    $stats[$team]['faceoffs']['won'] += $player['faceoffswon'];
    $stats[$team]['faceoffs']['total'] += $player['faceoffs'];
    //Top points
    if($player['points'] > $stats[$team]['toppoints']['value']){
      $stats[$team]['toppoints']['name'] = $player['firstname'].' '.$player['lastname'];
      $stats[$team]['toppoints']['photo'] = $player['photo'];
      $stats[$team]['toppoints']['playerid'] = $player['playerid'];
      $stats[$team]['toppoints']['value'] = $player['points'];
    }
    //Top goals
    if($player['goals'] > $stats[$team]['topgoals']['value']){
      $stats[$team]['topgoals']['name'] = $player['firstname'].' '.$player['lastname'];
      $stats[$team]['topgoals']['photo'] = $player['photo'];
      $stats[$team]['topgoals']['playerid'] = $player['playerid'];
      $stats[$team]['topgoals']['value'] = $player['goals'];
    }
    //Top assists
    if($player['assists'] > $stats[$team]['topassists']['value']){
      $stats[$team]['topassists']['name'] = $player['firstname'].' '.$player['lastname'];
      $stats[$team]['topassists']['photo'] = $player['photo'];
      $stats[$team]['topassists']['playerid'] = $player['playerid'];
      $stats[$team]['topassists']['value'] = $player['assists'];
    }
    //Find out who is a captain
    if($player['captain'] > 0){
      $stats[$team]['captain'][$i]['name'] = $player['firstname'].' '.$player['lastname'];
      $stats[$team]['captain'][$i]['playerid'] = $player['playerid'];
      $i++;
    }
  }
  $stats[$team]['faceoffratio'] = $stats[$team]['faceoffs']['won'].'-'.($stats[$team]['faceoffs']['total'] -  $stats[$team]['faceoffs']['won']);
  if($stats[$team]['faceoffs']['won'] == 0)
    $stats[$team]['faceoffpercent'] = 0;
  else
    $stats[$team]['faceoffpercent'] = ($stats[$team]['faceoffs']['won']/$stats[$team]['faceoffs']['total'])*100;
  $stats[$team]['faceoffpercent'] = round((float)$stats[$team]['faceoffpercent'], 2).'%';
  if(isset($teamArray['goalies']['goalie'][1])){
    foreach($teamArray['goalies']['goalie'] as $player){
      //Top saves
      if($player['saves'] > $stats[$team]['topsaves']['value']){
        $stats[$team]['topsaves']['name'] = $player['firstname'].' '.$player['lastname'];
        $stats[$team]['topsaves']['photo'] = $player['photo'];
        $stats[$team]['topsaves']['playerid'] = $player['playerid'];
        $stats[$team]['topsaves']['value'] = $player['saves'];
      }
      //Find out who is a captain
      if(isset($player['captain'])){
        if($player['captain'] > 0){
          $stats[$team]['captain'][$i]['name'] = $player['firstname'].' '.$player['lastname'];
          $stats[$team]['captain'][$i]['playerid'] = $player['playerid'];
          $i++;
        }
      }
    }
  }
  else{
    //Top saves
    $player = $teamArray['goalies']['goalie'];
    $stats[$team]['topsaves']['name'] = $player['firstname'].' '.$player['lastname'];
    $stats[$team]['topsaves']['photo'] = $player['photo'];
    $stats[$team]['topsaves']['playerid'] = $player['playerid'];
    $stats[$team]['topsaves']['value'] = $player['saves'];
    //Find out who is a captain
    if(isset($player['captain']) && $player['captain'] > 0){
      $stats[$team]['captain'][$i]['name'] = $player['firstname'].' '.$player['lastname'];
      $stats[$team]['captain'][$i]['playerid'] = $player['playerid'];
      $i++;
    }
  }
  $i = 0;
  if(isset($teamArray['staff']['staffmember'])){
    if(isset($teamArray['staff']['staffmember'][1])){
      foreach($teamArray['staff']['staffmember'] as $staff){
        $stats[$team]['coach'][$i]['position'] = $staff['type'];
        $stats[$team]['coach'][$i]['name'] = $staff['firstname'].' '.$staff['lastname'];
        $i++;
      }
    }
    else{
      $stats[$team]['coach'][$i]['position'] = $teamArray['staff']['staffmember']['type'];
      $stats[$team]['coach'][$i]['name'] = $teamArray['staff']['staffmember']['firstname'].' '.$teamArray['staff']['staffmember']['lastname'];
    }
  }

}

sortStats($homeDets, 'home');

//Away Details
$awayID = $currentGame['away']['id'];
$awayUrl = 'https://api.pointstreak.com/lacrosse/team/roster/'.$awayID.'/'.$mySeason.'/json';
$awayContent = file_get_contents($awayUrl, false, $context);
$awayDets = json_decode($awayContent, true);
$awayDets = $awayDets['teamroster']['team'];

sortStats($awayDets, 'away');

//Get team leaders
function getLeaders($team, $role){
  global $stats;
  if(isset($stats[$team][$role])){
    if($role == 'coach'){
      if(count($stats[$team][$role]) > 1)
        echo '<span>Coaches:</span> ';
      elseif(count($stats[$team][$role]) == 1) echo '<span>Coach:</span> ';
    }
    else{
      if(count($stats[$team][$role]) > 1)
        echo '<span>Captains:</span> ';
      elseif(count($stats[$team][$role]) == 1) echo '<span>Captain:</span> ';
    }
    $x = 0;
    foreach($stats[$team][$role] as $person){
      if($x > 0) echo ', ';
      if($role == 'coach')
        echo $person['name'];
      else
        echo '<a href="../../players/player?id='.$person['playerid'].'">'.$person['name'].'</a>';
      $x++;
    }
    echo '<br>';
  }
}

//Countdown clock initialization
if($basicDets['gamestatus']['status'] == 'SCHEDULED'){
  $getDetDt = DateTime::createFromFormat('Y-m-d H:i:s', $basicDets['gamedate']." ".$basicDets['gametime']);
  $getDetDt3 = $getDetDt->format('Y-m-d H:i:s');
  $getDetDt = DateTime::createFromFormat('Y-m-d G:i:s', $getDetDt3, new DateTimeZone('America/New_York'));
  $getCurrTime = new \DateTime('now', new DateTimeZone('America/New_York'));
  $dateDistance = date_diff($getCurrTime,$getDetDt);
  $dateDistanceString = $dateDistance->format('%a %H %I %S');
  $timeLeft['days'] = explode(' ', $dateDistanceString)[0];
  $timeLeft['hours'] = explode(' ', $dateDistanceString)[1];
  $timeLeft['minutes'] = explode(' ', $dateDistanceString)[2];
  $timeLeft['seconds'] = explode(' ', $dateDistanceString)[3];
  if($timeLeft['days'] < 10) $timeLeft['days'] = '0'.$timeLeft['days'];
}

get_header(); ?>
<div class="schedule feed">
  <?php include get_template_directory().'/inc/schedule-slider.php'; ?>
  <div class="scaler">
    <div class="content-wrap">
      <div class="nll-bar sidebar-left w70">
        <div class="header-text-container">
          <div class="header-text">
            <h1>Schedule</h1>
          </div>
        </div>
        <div class="matchup">
          <div class="matchup-time-loc">
            <h3><?php echo date('F d, Y', strtotime($basicDets['gamedate'])); ?> - <?php echo date('g:i', strtotime($basicDets['gametime'])); ?>PM EST</h3>
            <p>at <?php echo $basicDets['arena']['arenaname']; ?></p>
          </div>

          <!--Current Game Score (if applicable)-->
          <?php if($basicDets['gamestatus']['status'] == 'FINAL' || $basicDets['gamestatus']['status'] == 'IN PROGRESS'): ?>
          <div class="game-score">
            <?php if($basicDets['gamestatus']['status'] == 'FINAL' ): ?>
            <span class="score-label">Final Score:</span>
            <?php else: ?>
            <span class="score-label">Current Score:</span>
            <?php endif; ?>
            <div class="score-numbers">
              <span class="home<?php if($teamDets[0]['goals'] > $teamDets[1]['goals']) echo ' winner'; ?>"><?php echo $teamDets[0]['goals']; ?></span>
              <span class="divider">-</span>
              <span class="away<?php if($teamDets[1]['goals'] > $teamDets[0]['goals']) echo ' winner'; ?>"><?php echo $teamDets[1]['goals']; ?></span>
            </div>
          </div>
          <?php endif; ?>

          <!--Team logos/details-->
          <div class="matchup-split">

            <!--Left Side Team-->
            <div class="team-wrap">
              <div class="logo">
                <img src="<?php echo $currentGame['away']['logo']; ?>">
              </div>
              <div class="dets">
                <span class="loc"><?php echo $currentGame['away']['city']; ?></span>
                <span class="name"><?php echo $currentGame['away']['mascot']; ?></span>
                <?php if($mySeason == $season): ?>
                <div class="standings">
                  <p><?php echo $currentGame['away']['standings']; ?></p>
                  <span>|</span>
                  <a href="<?php echo get_permalink(get_page_by_title('Schedule')).'?team='.$currentGame['away']['id']; ?>">Full Schedule &gt;</a>
                </div>
                <?php endif; ?>
              </div>
              <div class="leaders">
                <p><?php getLeaders('away', 'coach'); getLeaders('away', 'captain'); ?></p>
              </div>
            </div>

            <span class="at">at</span>

            <!--Right Side Team-->
            <div class="team-wrap">
              <div class="logo">
                <img src="<?php echo $currentGame['home']['logo']; ?>">
              </div>
              <div class="dets">
                <span class="loc"><?php echo $currentGame['home']['city']; ?></span>
                <span class="name"><?php echo $currentGame['home']['mascot']; ?></span>
                <?php if($mySeason == $season): ?>
                <div class="standings">
                  <p><?php echo $currentGame['home']['standings']; ?></p>
                  <span>|</span>
                  <a href="<?php echo get_permalink(get_page_by_title('Schedule')).'?team='.$currentGame['home']['id']; ?>">Full Schedule &gt;</a>
                </div>
              <?php endif; ?>
              </div>
              <div class="leaders">
                <p><?php getLeaders('home', 'coach'); getLeaders('home', 'captain'); ?></p>
              </div>
            </div>
          </div>
          <!--End Teams-->

          <?php if($basicDets['gamestatus']['status'] == 'SCHEDULED'): ?>
          <div class="cd-clock">
            <div class="countdown">
              <div class="timer">
                <div class="timer-header">
                  <!--<div class="sponsor"><img src="assets/logos/heineken.png"></div>-->
                  <h3>Match Day Countdown</h3>
                </div>
                <div class="clock" data-targetdate="<?php echo $getDetDt3; ?>" style="opacity:0;">
                  <div class="timehiddendiv"></div>
                  <div class="timeunit days">
                    <div class="on"><?php echo $timeLeft['days']; ?></div>
                    <div class="off">00</div>
                    <p>Days</p>
                  </div>
                  <div class="timeunit hours">
                    <div class="on"><?php echo $timeLeft['hours']; ?></div>
                    <div class="off">00</div>
                    <p>Hrs</p>
                  </div>
                  <div class="timeunit minutes">
                    <div class="on"><?php echo $timeLeft['minutes']; ?></div>
                    <div class="off">00</div>
                    <p>Mins</p>
                  </div>
                  <div class="timeunit seconds">
                    <div class="on"><?php echo $timeLeft['seconds']; ?></div>
                    <div class="off">00</div>
                    <p>Secs</p>
                  </div>
                </div>
              </div>
              <div class="countdown-ctas">
                <span class="addtocalendar">
                  <a class="btn gold reminder" href="#">Set a Reminder</a>
                  <var class="atc_event">
                      <var class="atc_date_start"><?php echo date('Y-m-d', strtotime($game['longdate'])).' '.date('H:i:s', strtotime(explode('ET', $game['time'])[0])); ?></var>
                      <var class="atc_date_end"><?php echo date('Y-m-d', strtotime($game['longdate'])).' '.date('H:i:s', strtotime(explode('ET', $game['time'])[0])); ?></var>
                      <var class="atc_timezone">America/Detroit</var>
                      <var class="atc_title"><?php echo $game['away']['city']; ?> at <?php echo $game['home']['city']; ?></var>
                      <var class="atc_description"></var>
                      <var class="atc_location"><?php echo $game['arena']; ?></var>
                      <var class="atc_organizer">National Lacrosse League</var>
                      <var class="atc_organizer_email">support@nll.com</var>
                  </var>
                </span>
                <a href="<?php echo $game['tickets']; ?>" target="_blank" class="btn gray">Buy Tickets</a>
              </div>
              <div class="countdown-listing-links">
              <p>
              <?php if($featuredGame == $_GET['id']): ?>
              Stream Free: <a href="http://twitter.com" target="_blank"></a>
              <span>|</span>
              <?php endif; ?>
              Home TV: <a href="<?php echo $currentGame['home']['broadcastlink']; ?>" target="_blank"><?php echo $currentGame['home']['broadcaster']; ?></a> <span>|</span> Away TV: <a href="<?php echo $currentGame['away']['broadcastlink']; ?>" target="_blank"><?php echo $currentGame['away']['broadcaster']; ?></a>
              <span>|</span> Online: <a href="http://nlltv.com/live" target="_blank">NLL Live</a>
              </p>
              </div>
            </div>
          <!--End Countdown-->
          </div>

          <?php elseif($basicDets['gamestatus']['status'] == 'FINAL'): ?>
          <div class="match-recap-block">
            <div class="rule-header">
              <h4>Match Recap</h4>
            </div>
            <?php if(isset($basicDets['video'])): ?>
            <video width="660" height="370" controls>
              <source src="<?php echo urldecode($basicDets['video']); ?>" type="video/mp4">
            </video>
            <?php endif; ?>
            <a href="#" data-iframe="http://pointstreak.com/gamelive/?gameid=<?php echo $gameID; ?>" class="btn red">See Full Match Recap</a>
          </div>
          <?php
          elseif($basicDets['gamestatus']['status'] == 'IN PROGRESS'):
            function getScore($team, $period){
              global $gameDets;
              global $basicDets;
              if($team == 'home') $myTeam = 1;
              else $myTeam = 0;
              $myPeriod = $period-1;
              $myScores = $gameDets['boxscore']['teamstats'];
              $dataPeriod = $myScores[$myTeam]['linescore']['period'][$myPeriod]['number'];
              $currentPeriod = $basicDets['gamestatus']['currentperiod'];
              if($dataPeriod <= $currentPeriod){
                echo $myScores[$myTeam]['linescore']['period'][$myPeriod]['goals'];
              }
              else echo '-';
            }
          ?>
          <div class="live-score">
            <div class="rule-header">
              <h4>Live Score</h4>
            </div>
            <div class="score-table">
              <table>
                <tr>
                  <th>Teams</th>
                  <th>1</th>
                  <th>2</th>
                  <th>3</th>
                  <th>4</th>
                  <th>Score</th>
                </tr>
                <tr>
                  <td><?php echo $currentGame['away']['city'].'<br>'. $currentGame['away']['mascot']; ?></td>
                  <td><?php getScore('away', 1); ?></td>
                  <td><?php getScore('away', 2); ?></td>
                  <td><?php getScore('away', 3); ?></td>
                  <td><?php getScore('away', 4); ?></td>
                  <td><?php echo $gameDets['boxscore']['teamstats'][0]['linescore']['total']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $currentGame['home']['city'].'<br>'. $currentGame['home']['mascot']; ?></td>
                  <td><?php getScore('home', 1); ?></td>
                  <td><?php getScore('home', 2); ?></td>
                  <td><?php getScore('home', 3); ?></td>
                  <td><?php getScore('home', 4); ?></td>
                  <td><?php echo $gameDets['boxscore']['teamstats'][1]['linescore']['total']; ?></td>
                </tr>
              </table>
              <a href="http://nlltv.com/live" target="_blank" class="btn gold play">Live Stream</a>
              <div class="countdown-listing-links">
                <p>Home TV Listing: <a href="<?php echo $currentGame['home']['broadcastlink']; ?>" target="_blank"><?php echo $currentGame['home']['broadcaster']; ?></a> <span>|</span> Away TV Listing: <a href="<?php echo $currentGame['away']['broadcastlink']; ?>" target="_blank"><?php echo $currentGame['away']['broadcaster']; ?></a> <span>|</span> Online: <a href="http://nlltv.com/live" target="_blank">NLL Live Stream</a></p>
              </div>
            </div>
          </div>
          <?php
          endif;

          function calcStats($key, $label, $dir = 'asc'){
            global $stats;
            if($stats['home'][$key] == $stats['away'][$key]){
              $awayWin = '';
              $homeWin = '';
            }
            elseif($dir == 'asc'){
              if($stats['home'][$key] > $stats['away'][$key]){
                $awayWin = '';
                $homeWin = ' class="winner"';
              }
              elseif($stats['away'][$key] > $stats['home'][$key]){
                $awayWin = ' class="winner"';
                $homeWin = '';
              }
            }
            else{
              if($stats['home'][$key] < $stats['away'][$key]){
                $awayWin = '';
                $homeWin = ' class="winner"';
              }
              elseif($stats['away'][$key] < $stats['home'][$key]){
                $awayWin = ' class="winner"';
                $homeWin = '';
              }
            }
            echo '<td'.$awayWin.'>'.$stats['away'][$key].'</td>';
            echo '<td>'.$label.'</td>';
            echo '<td'.$homeWin.'>'.$stats['home'][$key].'</td>';
          }
          ?>

          <!--Start stats-->
          <div class="stat-block">
            <div class="rule-header">
              <h4>Head-to-head</h4>
            </div>
            <table class="stat-table head-to-head">
              <tr>
                <th><img src="<?php echo $currentGame['away']['logo']; ?>"></th>
                <th></th>
                <th><img src="<?php echo $currentGame['home']['logo']; ?>"></th>
              </tr>
              <tr>
                <?php calcStats('shotsongoal', 'Shots On<br>Goal'); ?>
              </tr>
              <tr>
                <?php calcStats('goals', 'Goals'); ?>
              </tr>
              <tr>
                <?php calcStats('looseballs', 'Loose<br>Balls', 'desc'); ?>
              </tr>
              <tr>
                <?php calcStats('faceoffratio', 'Faceoffs'); ?>
              </tr>
              <tr>
                <?php calcStats('faceoffpercent', 'Faceoff<br>Percentage'); ?>
              </tr>
            </table>
          </div>
          <!--End stats-->

        </div>

      </div>
      <div class="nll-bar sidebar-right w30">
        <?php include get_template_directory().'/inc/master-sidebar.php'; ?>
      </div>
    </div>
    <div class="rule-header tp">
      <h4>Top Players Matchup</h4>
    </div>
  </div>
</div>

<div class="top-players">
  <div class="scaler">
    <!--Stat section-->
    <div class="top-players-section">
      <span class="cat">Points</span>
      <div class="top-players-container">
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['away']['toppoints']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['away']['toppoints']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['away']['toppoints']['name']; ?></h3>
                <p>Points: <?php echo $stats['away']['toppoints']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['away']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['home']['toppoints']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['home']['toppoints']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['home']['toppoints']['name']; ?></h3>
                <p>Points: <?php echo $stats['home']['toppoints']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['home']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
      </div>
    </div>
    <!--End stat section-->
    <!--Stat section-->
    <div class="top-players-section">
      <span class="cat">Goals</span>
      <div class="top-players-container">
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['away']['topgoals']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['away']['topgoals']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['away']['topgoals']['name']; ?></h3>
                <p>Goals: <?php echo $stats['away']['topgoals']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['away']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['home']['topgoals']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['home']['topgoals']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['home']['topgoals']['name']; ?></h3>
                <p>Goals: <?php echo $stats['home']['topgoals']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['home']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
      </div>
    </div>
    <!--End stat section-->
    <!--Stat section-->
    <div class="top-players-section">
      <span class="cat">Assists</span>
      <div class="top-players-container">
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['away']['topassists']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['away']['topassists']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['away']['topassists']['name']; ?></h3>
                <p>Assists: <?php echo $stats['away']['topassists']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['away']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['home']['topassists']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['home']['topassists']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['home']['topassists']['name']; ?></h3>
                <p>Assists: <?php echo $stats['home']['topassists']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['home']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
      </div>
    </div>
    <!--End stat section-->
    <!--Stat section-->
    <div class="top-players-section">
      <span class="cat">Saves</span>
      <div class="top-players-container">
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['away']['topsaves']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['away']['topsaves']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['away']['topsaves']['name']; ?></h3>
                <p>Saves: <?php echo $stats['away']['topsaves']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['away']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
        <!--Player-->
        <div class="top-player w25">
          <a href="<?php echo get_permalink(get_page_by_title('Player')).'?id='.$stats['home']['topsaves']['playerid']; ?>" class="thumbnail-link">
            <div class="headshot">
              <img src="<?php echo $stats['home']['topsaves']['photo']; ?>">
            </div>
            <div class="player-details">
              <div class="player-breakdown">
                <h3><?php echo $stats['home']['topsaves']['name']; ?></h3>
                <p>Saves: <?php echo $stats['home']['topsaves']['value']; ?></p>
              </div>
              <img src="<?php echo $currentGame['home']['logo']; ?>">
            </div>
          </a>
        </div>
        <!--End player-->
      </div>
    </div>
    <!--End stat section-->
  </div>
</div>
<?php get_footer(); ?>
