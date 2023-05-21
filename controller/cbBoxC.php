<?php

class cbBoxC extends cbPageC
{
  private $boxA = null;
  private $articleBox = '';
  public  $articlesPerPage = 6;

  private $cbs = null;

  /**
   * Konstruktor
   * overwrite me!
   * _________________________________________________________________
   */
  public function __construct($articleBox, $linker, $requestM)
  {
    parent::__construct($linker, $requestM);
    $this->articleBox = $articleBox;

		try
		{
	    $this->cbs = new cbBoxM($this->articleBox);
    }
    catch(Exception $e)
    {
      throw $e;
    }
  }

  /**
   * index
   * ________________________________________________________________
   */
  public function index()
  {
    try
    {
      $page = $this->getPage();
      $startIdx = $page * $this->articlesPerPage;
      $artObjs = $this->cbs->getArticles($startIdx, $this->articlesPerPage);
      $boxA = $this->boxes->getBoxByName($this->articleBox);

      $this->view->setData('articleList', $this->cbs->articleObjs2Array($artObjs));
      $this->view->setData('articleBox', $this->articleBox);
      $this->view->setData('pageTitle', $boxA['alias']);
      $this->view->setData('metaDescription', $boxA['alias']);
      $this->view->setData('boxPage', $page);
      $this->view->setData('numArticles', $this->cbs->numArticles);
      $this->view->setData('articlesPerPage', $this->articlesPerPage);

      $this->view->drawPage();
    }
    catch (Exception $e)
    {
      $this->view->drawPage($e->getMessage);
    }
  }

  /**
   * get the page to show
   * _________________________________________________________________
   */
  protected function getPage()
  {
    $page = ($this->requestM->getReqVar('boxPage') !== false) ? $this->requestM->getReqVar('boxPage') : 0;

    // if we're coming from a "back to index" link,
    // $page has the name of the article we're coming from.
    // We have to translate the name to an actual page number.
    // Don't fuck easily with this solution - it works pretty well...
    if (!is_numeric($page))
    {
      $keys = $this->cbs->getArticleList();
      $key = array_search($page, $keys);
      $idx = array_search($key, array_keys($keys));

      $page = ceil($idx / $this->articlesPerPage);
      if (bcmod($idx, $this->articlesPerPage) != 0) $page--;
    }

    return $page;
  }

}

?>
