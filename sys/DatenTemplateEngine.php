<?php

 namespace datenshi;


/**
 * Controler component to be extended
 */
class View
{
  private $views;

  function __construct($orderedViewList)
  {
    try {
      foreach ($orderedViewList as $value) {
        if (file_exists("../templates/".$value.".dtp")) {

        } else throw new \Exception("Error Processing Request", 1);
      }
      $this->views = $orderedViewList;
      //var_dump($this->views);
    } catch (\Exception $e) {
      load_err(400);
    }
  }

  function Exec($data=[]) {
    require '../app/globales.php';
    $ucontent = "";
    foreach ($this->views as $v) {
      ob_start();
      include "../templates/".$v.".dtp";
      $pcontent = ob_get_clean();
      if (strpos($pcontent, '{{$viewPrevious}}') !== false) {
        $ucontent = str_replace('{{$viewPrevious}}', $ucontent, $pcontent);
      } else {
        $ucontent = $pcontent;
      }
    }


    #$ucontent = $this->globals($ucontent);
    $ucontent = $this->datavars($ucontent, $data);
    $ucontent = self::datavar2($ucontent, $data);
    $ucontent = $this->renders($ucontent, $data);
    $ucontent = $this->globals($ucontent);
    echo $ucontent;
    // finish, the page is delivered
  }

  private function datavars($ucontent, $data) {
    foreach ($data as $k => $v) {
      try {
        if (strpos($ucontent, '{{$data'.$k.'}}') !== false) {
          $ucontent = str_replace('{{$data'.$k.'}}', $v, $ucontent);
        } else {
          throw new \Exception("Data not exists", 1);
        }
      } catch (\Exception $e) {
        //pass
      }
    }
    return $ucontent;
  }

  private static function datavar($ucontent, $data) {
    foreach ($data as $k => $v) {
      try {
        if (strpos($ucontent, '{{$data'.$k.'}}') !== false) {
          $ucontent = str_replace('{{$data'.$k.'}}', $v, $ucontent);
        } else {
          throw new \Exception("Data not exists", 1);
        }
      } catch (\Exception $e) {
        //pass
      }
    }
    return $ucontent;
  }

  private static function datavar2($ucontent, $data) {
    $regex = '/(?<={{\$).*?:.*?(?=}})/';
    preg_match_all($regex, $ucontent, $matches);
    #return $ucontent;
    if (sizeof($matches[0]) > 0) {
      $matches = $matches[0];
      foreach ($matches as $k => $v) {
        $keys = self::getFunctionNameValue($v);
        if (isset($keys)) {
          switch ($keys["key"]) {
            case 'image':
              try {
                if (is_file("./public_img/".$keys["value"])) {
                  $ucontent = str_replace('{{$'.$v.'}}', '<img class="responsive-img col s12 m6" src="./public_img/'.$keys["value"].'">', $ucontent);
                } else {
                  $ucontent = str_replace('{{$'.$v.'}}', 'The image ('.$keys["value"].') does not exists at the requested location.', $ucontent);
                }
              } catch (\Exception $e) {
                //pass
              }
              break;
            case 'data':
              try {
                if (isset($data[$keys["value"]]) !== false) {
                  $ucontent = str_replace('{{$'.$v.'}}', $data[$keys["value"]], $ucontent);
                } else {
                  throw new \Exception("Data not exists", 1);
                }
              } catch (\Exception $e) {
                $ucontent = str_replace('{{$'.$v.'}}', 'Error: variable does not exists.', $ucontent);
              }
              break;
            case 'include':
                $ucontent = str_replace('{{$'.$v.'}}', self::getTemplateToString($keys["value"], $data), $ucontent);
              break;
            
            default:
                if (strpos($ucontent, '{{$'.$v.'}}') !== false) {
                  $ucontent = str_replace('{{$'.$v.'}}', "Unexpected function", $ucontent);
                } else {
                  throw new \Exception("Global not exists", 1);
                }
              break;
          }
        }
      }
    }
    return $ucontent;
  }

  public static function getTemplateToString($template, $data=[]) {
    require '../app/globales.php';
    if (file_exists("../templates/".$template.".dtp")) {
      ob_start();
      include "../templates/".$template.".dtp";
      $pcontent = ob_get_clean();
      $pcontent = self::datavar($pcontent, $data);
      $pcontent = self::datavar2($pcontent, $data);
      return $pcontent;
    } else return "";
  }

  private function globals($ucontent) {
    $regex = '/(?<={{\$global).*?(?=}})/';
    preg_match_all($regex, $ucontent, $matches);
    #return $ucontent;
    if (sizeof($matches[0]) > 0) {
      $matches = $matches[0];
      foreach ($matches as $k => $v) {
        try {
          if (strpos($ucontent, '{{$global'.$v.'}}') !== false) {
            $ucontent = str_replace('{{$global'.$v.'}}', $GLOBALS[$v], $ucontent);
          } else {
            throw new \Exception("Global not exists", 1);
          }
        } catch (\Exception $e) {
          //pass
        }
      }
    }
    return $ucontent;
  }

  private function globalConvert($camelCaseVarname) {
      require "../app/globals.php";
      switch ($camelCaseVarname) {
        case 'SiteName':
          return $site_name;
          break;
        case 'SiteDesc':
          return $site_description;
          break;
        case 'SiteAuthor':
          return $site_author;
          break;
        case 'SiteIcon':
          return $site_favicon;
          break;
        case 'JS':
          return $sys_jsPath;
          break;
        case 'CSS':
          return $sys_cssPath;
          break;
        case 'IMG':
          return $sys_imgPath;
          break;

        default:
          return "";
          break;
      }
  }

  private static function getFunctionNameValue($str) {
    $nTab=explode(":", $str);
    if (isset($nTab[1]) && !empty($nTab[1])) {
      return array("key"=>$nTab[0], "value"=>$nTab[1]);
    }
    return;
  }

  private function renders($ucontent, $data) {
      if (strpos($ucontent, '{{$menuRender}}') !== false) {
        $ucontent = str_replace('{{$menuRender}}', menuRenderFromPages($data["page"] ?: ""), $ucontent);
      }
    //menuRenderFromPages($page);
    return $ucontent;
  }
}
 ?>
