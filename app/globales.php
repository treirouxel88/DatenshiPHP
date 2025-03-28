<?php
  // website main info and SEO
  $site_slug = "slug";
  $site_name = "name";
  $site_favicon = "icon.png";
  $site_description = "description";
  $site_author = "Urata Akumu";

  // file paths
  $sys_jsPath = "./js";
  $sys_cssPath = "./css";
  $sys_imgPath = "./public_img";
  $sys_errList = array(
      404 => "not found",
      200 => "success",
      400 => "Unknown error",
      500 => "Server error"
    );

  //341482 > Gestion des routes

  // engine config
  $daten_config = array(
    "use_session" => true,
    "enable_oauth2" => false
  );

  // site variables
  $variable1 = "valeur1";

  // DatenshiPHP ver (should not change)
  $backend_ver = "Datenshi 0.11.0";
?>
