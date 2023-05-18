<?php

class cbArticleC extends cbPageC
{
  // set by __construct and static
  public $articleBox = '';
  public $articleName = '';
  public $articlePage = '';

  public $cba = null;

  public $rInfo = array();

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  function __construct($articleBox, $articleName, $linker = null, $requestM = null)
  {
    parent::__construct($linker, $requestM);

    $this->articleBox = $articleBox;
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
   * Show Article
   * _________________________________________________________________
   */
  public function index()
  {
    try
    {
      $data = [];
      $pageTitleAddendum = '';

      if (count($this->cba->getArticlePaginatedText()) > 1)
      {
        $pageTitleAddendum = ' ['.($this->articlePage + 1).']';
      }

      $data['meta']['cTitle'] = $this->cba->getArticleHeadline().$pageTitleAddendum;
      $data['meta']['cTeaser'] = $this->cba->getArticlePageAbstract($this->articlePage);
      $data['meta']['articlePage'] = $this->articlePage;

      // FIXME: return only the current page, should reduce memory usage a lot.
      $data['model'] = $this->cba->getArticle();
      $data['model']['boxNameAlias'] = $this->boxes->getBoxByName($this->articleBox)['alias'];

      // append page count for easy access
      $data['meta']['pageCount'] = count($data['model']['paginatedText']);;

      $this->view->addDataFromArray($data['meta']);
      $this->view->addDataFromArray($data['model']);

      $this->view->setData('pageTitle', $data['meta']['cTitle']);
      $this->view->setData('metaDescription', $data['meta']['cTeaser']);

      $this->view->drawPage();
    }
    catch (Exception $e)
    {
      $this->view->drawPage($e->getMessage());
    }

  }
}

?>
