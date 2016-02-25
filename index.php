<?php

error_reporting(E_ALL);

$login = isset($_GET['login']) ? $_GET['login'] : '';

// echo $login;

session_start();
require "../twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', "");
define('CONSUMER_SECRET', "");
define('OAUTH_CALLBACK', "http://ezchx.com/nightlife/index.php");


if ($login) {
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
  $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
  $_SESSION['oauth_token'] = $request_token['oauth_token'];
  $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
  $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
  header('Location: ' . $url);
}


if(!$login && isset($_SESSION['oauth_token'])) {
  $request_token = [];
  $request_token['oauth_token'] = $_SESSION['oauth_token'];
  $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
  if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    // Abort! Something is wrong.
  }
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
  $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);
  $_SESSION = Array();
  $string = implode(';', $access_token);
  $user_id = $access_token["user_id"];
  setcookie("ezchxNightlife", $user_id);

}


?>


<html>

  <head>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="nightlife.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="nightlife.js"></script>
  </head>

  <body>
    
  <p id="debug2"></p>
    <div class="container">
      <div class="row text-right">
        <div class="col-md-12">
          <div class="login"></div>
        </div>
      </div>
    </div>
  
    
    <div class="container">
      <div class="row heady text-center">
        <div class="col-md-12">
          <h1><div class="main-title">Nightlife Finder</div></h1>
        </div>
      </div>
    </div>
    
    <div class="container">
      <div class="row heady text-center">
        <div class="col-md-12 pad">
          <div class="search">
            <input id="location" placeholder="enter your location...">
            <button type="button" class="btn-sm btn-primary butty">Search</button>
          </div>
        </div>
      </div>
    </div>

    <div id="search_results">&nbsp;</div>
    
    
    
    <div class="container">
      <div class="row text-center">    
        <div class="col-md-12">
          <a href="https://www.yelp.com" target="_blank"><img src="http://ezchx.com/yelpoauth/yelp_button.png" width="150px" /></a>
          <p id="debug">2016 EZChx</p>
        </div>
      </div>
    </div>



  </body>

</html>