<?php

/**
 * This class describes cbSitemapM.
 */

class cbSitemapM
{
  public $links = array();
  public $linker = null;

  /**
   * Konstruktor
   * ________________________________________________________________
   */
  public function __construct($linker = null)
  {
    $this->linker = ($linker != null) ? $linker : new \cb\view\fragment\cbLinkVF();
  }

  /**
   * add single Link
   * ________________________________________________________________
   */
	public function addLink($link)
	{
		if (!in_array($link, $this->links))
    {
    	$this->links[] = $link;
		}
	}

  /**
   * add Article Link
   * ________________________________________________________________
   */
  public function addArticle($ep, $hook, $articleBox, $article)
  {
    $this->links[] = $this->ensureURL($this->linker->cbArticleLink($ep, $hook, $articleBox, $article));
  }

  /**
   * add all links of a box
   * ________________________________________________________________
   */
  public function addArticleBox($ep, $boxHook, $articleHook, $articleBox, $indexLink = false)
  {
  	if ($indexLink)
    {
  	  $this->links[] = $this->ensureURL($this->linker->cbBoxLink($ep, $boxHook, $articleBox, 0));
		}

    $cb = new cbBoxM($articleBox);
    $cb->fetchArticles();
    $arts = $cb->getArticleList();

    if (count($arts) > 0)
    {
    	foreach($arts as $art)
      {
    		$this->links[] = $this->ensureURL($this->linker->cbArticleLink($ep, $articleHook, $articleBox, $art));
    	}
    }
  }

  /**
   * make sure we always have a full URL
   * ________________________________________________________________
   */
  protected function ensureURL($url)
  {
    if (!((strpos($url, 'http') === 0) || (strpos($url, 'https') === 0)))
    {
      $url = ltrim($url, '/\\');

      $protocol = ($_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
      $rootURL  = $protocol.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

      $url = $rootURL.'/'.$url;
    }
    return $url;
  }

  /**
   * run
   * ________________________________________________________________
   */
  public function create()
  {
    return $this->links;
  }

}

?>