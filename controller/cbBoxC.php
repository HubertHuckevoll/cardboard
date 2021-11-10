<?php

class cbBoxC extends cbBaseC
{
  private $boxA = null;
  private $articleBox = '';
  public  $articlesPerPage = 6;

  private $cbs = null;

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($boxA, $requestM = null)
  {
    parent::__construct($requestM);

    $this->boxA = $boxA;
    $this->articleBox = $this->boxA['box'];

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
   * Index
   * _________________________________________________________________
   */
  public function show()
  {
    $page = $this->getPage();
    $startIdx = $page * $this->articlesPerPage;
    $artObjs = $this->cbs->getArticles($startIdx, $this->articlesPerPage);

    $ret['meta']['cTitle'] = $this->boxA['alias'];
    $ret['meta']['cTeaser'] = $ret['meta']['cTitle'];
		$ret['model']['boxPage'] = $page;
		$ret['model']['articleBox'] = $this->articleBox;
		$ret['model']['articleList'] = $this->cbs->articleObjs2Array($artObjs);
		$ret['model']['numArticles'] = $this->cbs->numArticles;
		$ret['model']['articlesPerPage'] = $this->articlesPerPage;

    return $ret;
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
