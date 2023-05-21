<?php

class cbSearchC extends cbPageC
{

  /**
   * Search
   * _________________________________________________________________
   */
  public function index()
  {
    $term = $this->requestM->getReqVar('term');

    try
    {
      $erg = $this->boxes->search($term);

      $this->view->setData('results', $erg);
      $this->view->setData('pageTitle', 'Suchergebnisse');
      $this->view->setData('metaDescription', 'Suchergebnisse');

      $this->view->drawPage();

    }
    catch (Exception $e)
    {
      $this->view->drawPage($e->getMessage());
    }
  }
}

?>
