<?php

/**
 * front controller
 * __________________________________________________________________
 */
class cbGalleryC extends cbPageC
{

  /**
   * cbGallery Hook
   * _________________________________________________________________
   */
  public function index()
  {
    $articleBox  = $this->requestM->getReqVar('articleBox');
    $articleName = $this->requestM->getReqVar('article');
    $articlePage = $this->requestM->getReqVar('articlePage');
    $imgIdx      = $this->requestM->getReqVar('imgIdx');

    try
    {
      $cba = new cbArticleM($articleBox, $articleName);
      $cba->load();
      $data['model'] = $cba->getArticle();
      $data['meta']['articlePage'] = $articlePage;
      $data['meta']['imgIdx'] = $imgIdx;

      $this->view->addDataFromArray($data['model']);
      $this->view->addDataFromArray($data['meta']);

      $this->view->setData('pageTitle', $cba->getArticleHeadline());
      $this->view->setData('metaDescription', $cba->getArticleAbstract());

      $this->view->drawPage();
    }
    catch(Exception $e)
    {
      $this->view->drawPage($e->getMessage());
    }
  }

}

?>
