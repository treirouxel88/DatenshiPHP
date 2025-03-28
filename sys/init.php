<?php
  if (isset($daten_config["use_session"]) && $daten_config["use_session"]) {
    // server should keep session data for 1 week
    ini_set('session.gc_maxlifetime', 604800);
    session_name("datenshi_".$site_slug);
    session_set_cookie_params(604800);
    session_cache_limiter('nocache');
    session_start();
  }
?>