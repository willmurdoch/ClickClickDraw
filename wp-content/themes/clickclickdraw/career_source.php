<?php
  header('Content-Type: text/html');
  $var = file_get_contents('http://nll.teamworkonline.com/teamwork/jobs/default.cfm');
  echo $var;
?>
