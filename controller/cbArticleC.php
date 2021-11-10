<?php

class cbArticleC extends cbBaseC
{
  // set by __construct and static
  public $boxA = null;
  public $articleBox = '';
  public $articleName = '';
  public $articlePage = '';

  public $cba = null;

  public $rInfo = array();

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  function __construct($boxA, $articleName, $requestM = null)
  {
    parent::__construct($requestM);

    $this->boxA = $boxA;
    $this->articleBox = $this->boxA['box'];
    $this->articleName = $articleName;
    $this->articlePage = ($this->requestM->getReqVar('articlePage') !== false) ? $this->requestM->getReqVar('articlePage') : 0;

    try
    {

      $this->cba = new cbArticleM($this->articleBox, $this->articleName);
      $this->cba->load();
    }
    catch(Exception $e)
    {
      throw $e;
    }
  }

  /**
   * Show
   * _________________________________________________________________
   */
  function show()
  {
    $pageTitleAddendum = '';

    if (count($this->cba->getArticlePaginatedText()) > 1)
    {
      $pageTitleAddendum = ' ['.($this->articlePage + 1).']';
    }

    $this->rInfo['meta']['cTitle'] = $this->cba->getArticleHeadline().$pageTitleAddendum;
    $this->rInfo['meta']['cTeaser'] = $this->cba->getArticlePageAbstract($this->articlePage);
    $this->rInfo['meta']['articlePage'] = $this->articlePage;

    // FIXME: return only the current page, should reduce memory usage a lot.
    // It'd also give this stupid controller a meaning again
    $this->rInfo['model'] = $this->cba->getArticle();
    $this->rInfo['model']['boxNameAlias'] = $this->boxA['alias'];

    // append page count for easy access
    $this->rInfo['meta']['pageCount'] = count($this->rInfo['model']['paginatedText']);;

    return $this->rInfo;
  }
}

?>
