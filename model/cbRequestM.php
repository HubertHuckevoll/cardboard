<?php

/**
 * a class for creating hrefs, links and routes
 * used by all views based on cbBaseV
 * __________________________________________________________________
 */
class cbRequestM
{
  public $routes = array();

  /**
   * constructor
   * ________________________________________________________________
   */
  function __construct()
  {
  }

  /**
   * add a route parser
   * ________________________________________________________________
   */
  public function add($path, $onPathMatch)
  {
    $this->routes[] = array('path' => $path, 'onPathMatch' => $onPathMatch);
  }

  /**
   * create a href for links / forms
   * returns a route if a matching route has been defined
   * and added via our add function
   * ________________________________________________________________
   */
  public function getReqVar($varName, $stripTags = true)
  {
    $pathInfoArr = $this->parsePathInfo($varName);

    $req = $_GET + $_POST + $pathInfoArr;

    $var = trim($varName);
    $var = preg_replace("/^(content-type:|bcc:|cc:|to:|from:)/im", "", $var);

    if (isset($req[$varName]))
    {
      $var = $req[$varName];

      if (is_string($var) && ($stripTags == true))
      {
        $var = filter_var($var, FILTER_SANITIZE_STRING);
      }

      return $var;
    }

    return false;
  }

  /**
   * do the path_info to "get-param" translation
   * ________________________________________________________________
   */
  protected function parsePathInfo($varName)
  {
    $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $matches = array();
    $result = array();

    foreach ($this->routes as $route)
    {
      if ((preg_match($route['path'], $pathInfo, $matches)) === 1)
      {
        $keyVal = $route['onPathMatch']($matches);
        $result[$keyVal['key']] = $keyVal['val'];
      }
    }

    //file_put_contents('debug.txt', $varName.' - '.print_r($result, true)."\r\n", FILE_APPEND);
    return $result;
  }
}

?>
