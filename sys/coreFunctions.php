<?php
  function load_err($errcode){
    global $err, $sys_errList;
    $errmsg = $sys_errList[$errcode];
    $vars = ["Msg"=>$errmsg, "Code"=>$errcode];
    $err->Exec($vars);
    die;
  }

  function getFirstRoute() {
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  	return substr($uri, 1);
  }
  function getFullRoute() {
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  	return $uri;
  }

  function menuRenderFromPages($page=NULL) {
	  $uri = datenshi\Page::getUri()[0];
    $list = datenshi\Page::getAll();
    $list = array_reverse($list);
    foreach ($list as $k => $v) {
      if (checkPerms($v[1])) {
        $active = (isset($uri) && $uri == $k) ? ' active' : '';
        $return .= '<li class="nav-item'. $active .'"><a class="nav-link black-text" href="/'.$k.'">'.$v[0].'</a></li>';
      }
    }
    return $return;
  }

  function generateTable($tableTemplate, $tableContent) {
    $thead = "";
    $tbody = "";
    foreach ($tableTemplate as $v) {
      $thead = $thead."<th scope=\"col\"><b>$v</b></th>";
    }
    foreach ($tableContent as $v) {
      $tbody = $tbody."<tr>";
      foreach ($v as $val) {
        $tbody = $tbody."<td>$val</td>";
      }
      $tbody = $tbody."</tr>";
    }
    $table = "
    <table class=\"table\">
    <thead>
      <tr>
          $thead
      </tr>
    </thead>

    <tbody>
      $tbody
    </tbody>
    </table>
    ";
    return $table;
  }

  function generateCollection($list) {
    $tcollect = "";
    foreach ($list as $v) {
      $tcollect = $tcollect.$v;
    }
    
    $tcollect = "<div class=\"collection\">".$tcollect."</div>";
    return $tcollect;
  }

  function generateList($content) {
    $thead = "";
    $tbody = "";
    foreach ($content as $v) {
      $tbody = $tbody."<li class=\"list-group-item\">$v</li>";
    }
    $table = "
    <ul class=\"list-group\">
      $tbody
    </ul>
    ";
    return $table;
  }

  //return bool en fonction des permissions, par défaut : true
  function checkPerms($perms) {
    global $logged;
    //calculs mathématiques
    $permissionlist = array(
      "LOGGED_OUT" => $logged ? 0 : 1,
      "LOGGED_IN" => $logged ? 1 : 0,
      "ONLY_DEV" => checkUser("discordid"),
      "HIDDEN" => 0
    );
    $perms = explode(" ", $perms);
    foreach ($perms as $v) {
      if (array_key_exists($v, $permissionlist) && $permissionlist[$v] == 0) {
        return false;
      }
    }
    return true;
  }
  function get_cc() {
    if (isset(apache_request_headers()["CF-IPCountry"])) {
      return apache_request_headers()["CF-IPCountry"];
    }
    return ip_info("Visitor", "countrycode");
  }

  function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        //$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        if (isset($_SESSION["ipcc_".$ip])) {
          return $_SESSION["ipcc_".$ip];
        }
        $ipdat = @json_decode(file_get_contents("http://ip-api.com/json/" . $ip));
        if (@strlen(trim($ipdat->countryCode)) == 2) {
            $_SESSION["ipcc_".$ip] = @$ipdat->countryCode;
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->city,
                        "state"          => @$ipdat->regionName,
                        "country"        => @$ipdat->country,
                        "country_code"   => @$ipdat->countryCode,
                        /*"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode*/
                    );
                    break;
                case "address":
                    $address = array($ipdat->country);
                    if (@strlen($ipdat->regionName) >= 1)
                        $address[] = $ipdat->regionName;
                    if (@strlen($ipdat->city) >= 1)
                        $address[] = $ipdat->city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->city;
                    break;
                case "state":
                    $output = @$ipdat->regionName;
                    break;
                case "region":
                    $output = @$ipdat->regionName;
                    break;
                case "country":
                    $output = @$ipdat->country;
                    break;
                case "countrycode":
                    $output = @$ipdat->countryCode;
                    break;
            }
        }
    }
    #return "FR";
    return $output;
  }

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
function cookie($key, $default=NULL) {
  return array_key_exists($key, $_COOKIE) ? $_COOKIE[$key] : $default;
}
function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

 ?>
