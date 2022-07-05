<?php
//discord login
// TODO: move to "lib" folder
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)
error_reporting(E_ALL);
define('OAUTH2_CLIENT_ID', $GLOBALS["OAUTH2_CLIENT_ID"]);
define('OAUTH2_CLIENT_SECRET', $GLOBALS["OAUTH2_CLIENT_SECRET"]);
$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';
$apiURLBase = 'https://discordapp.com/api/users/@me';

// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 604800);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(604800);

session_start();
// Start the login process by sending the user to Discord's authorization page
if(get('discord') == 'login') {
  $params = array(
    'client_id' => $GLOBALS["OAUTH2_CLIENT_ID"],
    'redirect_uri' => $GLOBALS["OAUTH2_REDIR"],
    'response_type' => 'code',
    'scope' => 'identify email connections'
  );
  // Redirect the user to Discord's authorization page
  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
  die();
}


// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {
  // Exchange the auth code for a token
  $token = apiRequest($tokenURL, array(
    "grant_type" => "authorization_code",
    'client_id' => $GLOBALS["OAUTH2_CLIENT_ID"],
    'client_secret' => $GLOBALS["OAUTH2_CLIENT_SECRET"],
    'redirect_uri' => $GLOBALS["OAUTH2_REDIR"],
    'code' => get('code')
  ));
  var_dump($token);
  $logout_token = $token->access_token;
  $_SESSION['access_token'] = $token->access_token;
  header('Location: ' . $GLOBALS["OAUTH2_REDIR"]);
}
if(session('access_token')) {
  $user = apiRequest($apiURLBase);
  //setcookie("uid", $user->id, time() + 365*24*3600);
  $logged = true;
  $_SESSION['logged'] = true;
  $_SESSION['uid'] = $user->id;
  //$uid = $user->id;
  //checkUsernameInDb($user->id, $user->username);
    /*while ($row = $last_coms->fetch_assoc()) {
      $usid = $row["id"];
      $isAuthor = $row["author"];
      $smsss = $row["authornamae"];
      $com = $row["comment"];
      $pop = $row["popularity"];

    }*/

  //$last_coms->close();
} else if(get('discord') == 'testenv') {
  $logged = true;
  $_SESSION['logged'] = true;
  $_SESSION['did'] = "discordid";
} else {
  $logged = false;
  $uid = "Anonymous";
  $_SESSION['logged'] = false;
}
if(get('discord') == 'logout') {
  setcookie('uid', null, -1);
  $logged = false;
  apiRequest($revokeURL, array(
    'client_id' => $GLOBALS["OAUTH2_CLIENT_ID"],
    'client_secret' => $GLOBALS["OAUTH2_CLIENT_SECRET"],
    'token' => session('access_token')
  ));
  unset($_SESSION['access_token']);
  header('Location: ' . $_SERVER['PHP_SELF']);
  // Redirect the user to Discord's revoke page
  //header('Location: https://discordapp.com/api/oauth2/token/revoke' . '?' . http_build_query($params));
  //die();
}
function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $response = curl_exec($ch);
  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
  $headers[] = 'Accept: application/json';
  if(session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  return json_decode($response);
}
function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}
?>
