<?php
//simple and basic discord login system
// TODO: move to "lib" folder
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)
define('OAUTH2_CLIENT_ID', $GLOBALS["OAUTH2_CLIENT_ID"]);
define('OAUTH2_CLIENT_SECRET', $GLOBALS["OAUTH2_CLIENT_SECRET"]);
$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';
$apiURLBase = 'https://discordapp.com/api/users/@me';

$logged = false;

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
  if (isset($token->access_token) && !empty($token->access_token)) {
    setcookie('tm_token', $token->access_token);
    //setcookie('tm_t2', $token->refresh_token);
    setcookie('tm_social', "discord");
    header('Location: ' . $GLOBALS["OAUTH2_REDIR"]);
    exit();
  } else {
    load_err(360480);
  }
}
if(cookie('tm_social') == "discord" && cookie('tm_token')) {
  $user = apiRequest($apiURLBase);
  $logged = true;
} else if(get('discord') == 'testenv') {
  $logged = true;
} else {
  $logged = false;
}
if(get('discord') == 'logout') {
  setcookie('uid', null, -1);
  $logged = false;
  apiRequest($revokeURL, array(
    'client_id' => $GLOBALS["OAUTH2_CLIENT_ID"],
    'client_secret' => $GLOBALS["OAUTH2_CLIENT_SECRET"],
    'token' => cookie('tm_token')
  ));
  unset($_COOKIE['tm_token']);
  setcookie('tm_token', '', -1, '/');
  unset($_COOKIE['tm_social']);
  setcookie('tm_social', '', -1, '/');
  header('Location: '.$GLOBALS["OAUTH2_REDIR"]);
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
  if(cookie('tm_token'))
    $headers[] = 'Authorization: Bearer ' . cookie('tm_token');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  return json_decode($response);
}
?>
