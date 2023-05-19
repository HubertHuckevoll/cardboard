<?php

namespace cb\view\fragment;

/**
 * a class for creating hrefs, links and routes
 * used by all views based on cbBaseVF
 * __________________________________________________________________
 */
class cbLinkVF
{
  public $routes = array();

  /**
   * constructor
   * not the usual parameters, so we have to overwrite the constructor
   * ________________________________________________________________
   */
  function __construct()
  {
  }

  /**
   * add a route creator
   * pass a a regular expression that matches a query
   * as first parameter and the function that converts
   * this query to a route as second parameter
   * hint: basically an inverted .htaccess file
   * ________________________________________________________________
   */
  public function add($query, $onQueryMatch)
  {
    $this->routes[] = array('query' => $query, 'onQueryMatch' => $onQueryMatch);
  }

  /**
   * create a href for links / forms
   * returns a route if a matching route has been defined
   * and added via our add function
   * ________________________________________________________________
   */
  public function href($ep, $paramsA)
  {
    $href = $ep;
    $max = count($paramsA);
    $i = 0;

    if ($max > 0)
    {
      $href .= '?';
    }

    foreach ($paramsA as $paramK => $paramV)
    {
      $href .= $paramK.'='.urlencode($paramV);
      if ($i < ($max-1))
      {
        $href .= '&amp;';
      }
      $i++;
    }

    $href = $this->href2route($href);

    return $href;
  }

  /**
   * create a link
   * pass get params, link text and attributes
   * ________________________________________________________________
   */
  public function link($ep, $paramsA, $textS, $attrA = array())
  {
    $erg = '';
    $attrs = '';

    $href = $this->href($ep, $paramsA);

    if (count($attrA) > 0)
    {
      foreach ($attrA as $attrK => $attrV)
      {
        $attrs .= ' '.$attrK.'="'.$attrV.'"';
      }
    }

    $erg .= '<a href="'.$href.'"'.$attrs.'>'.$textS.'</a>';

    return $erg;
  }

  /**
   * do the href to route translation
   * ________________________________________________________________
   */
  protected function href2route($href)
  {
    $matches = array();

    foreach ($this->routes as $route)
    {
      if (($ret = preg_match($route['query'], $href, $matches)) === 1)
      {
        $href = $route['onQueryMatch']($matches);
        break;
      }
    }

    return $href;
  }

  /**
   * Some Shortcuts...
   */

  /**
   * cbArticleLink
   * _________________________________________________________________
   */
  public function cbArticleLink($ep, $mod, $hook, $articleBox, $articleName, $articlePage = 0)
  {
    return $this->href($ep, ['mod' => $mod, 'hook' => $hook, 'articleBox' => $articleBox, 'article' => $articleName, 'articlePage' => (int)$articlePage]);
  }

  /**
   * cbArticleLinkToGalleryImg
   * _________________________________________________________________
   */
  public function cbArticleLinkToGalleryImg($ep, $mod, $hook, $articleHook, $articleBox, $articleName, $articlePage = 0, $imgIdx = 0)
  {
    return $this->href($ep, ['mod' => $mod, 'hook' => $hook, 'articleHook' => $articleHook, 'articleBox' => $articleBox, 'article' => $articleName, 'articlePage' => (int)$articlePage, 'imgIdx' => $imgIdx]);
  }

  /**
   * cbBoxLink
   * _________________________________________________________________
   */
  public function cbBoxLink($ep, $mod, $hook, $articleBox, $boxPage = 0)
  {
    return $this->href($ep, ['mod' => $mod, 'hook' => $hook, 'articleBox' => $articleBox, 'boxPage' => (int)$boxPage]);
  }

  /**
   * cbBoxLinkFromArticle
   * infamous BACK link...
   * _______________________________________________________________
   */
  public function cbBoxLinkFromArticle($ep, $mod, $hook, $articleBox, $articleName = '')
  {
    if ($articleName == '')
    {
      $articleName = (int)0;
    }
    return $this->href($ep, ['mod' => $mod, 'hook' => $hook, 'articleBox' => $articleBox, 'boxPage' => $articleName]);
  }
}

?>
