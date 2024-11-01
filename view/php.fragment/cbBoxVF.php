<?php

namespace cb\view\fragment;

class cbBoxVF extends cbBaseVF
{
  public $articleHook = '';

  /**
   * Konstruktor
   * ___________________________________________________________________
   */
  public function __construct($ep, $hook, $articleHook, $linker = null)
  {
    parent::__construct($ep, $hook, $linker);
    $this->articleHook = $articleHook;
  }

  /**
   * Create the index page
   * _________________________________________________________________
   */
  public function render()
  {
    $articleList = (array) $this->data['articleList'];

    // add some classes for easier css/js selection
    $erg = '<div class="'.$this->data['articleBox'].'">';
    $textPadding = '';

    $i = 0;
    if (count($articleList) > 0)
    {
      foreach($articleList as $artObj)
      {
        $http = $this->linker->cbArticleLink($this->ep, $this->articleHook, $artObj['articleBox'], $artObj['articleName']);

        $teaserImg = (isset($artObj['images'][0])) ? $artObj['images'][0] : array();

        $erg .= '<div class="abcArticleOverviewCont">';
        if (isset($teaserImg['thumb']))
        {
          $erg .= '<div class="abcArticleOverviewImg">'.
                    '<a class="abcArticleOverviewImgA" href="'.$http.'">'.
                      '<img src="'.($teaserImg['thumb'] ?? '#').'" alt="'.($teaserImg['thumb'] ?? '#').'" />'.
                    '</a>'.
                  '</div>';
        }
        $erg .= '<div class="abcArticleOverview" style="'.$textPadding.'">'.
                  '<div class="abcArticleOverviewDate">'.$this->fDate($artObj['date']).'</div>'.
                  '<div class="abcArticleOverviewHeadline"><a href="'.$http.'">'.($artObj['headline'] ?? '').'</a></div>'.
                  '<div class="abcArticleOverviewAbstract">'.
                    ($artObj['aAbstract'] ?? '').'...&nbsp;'.
                    '<a class="abcArticleOverviewMoreLink" href="'.$http.'">&raquo;</a>'.
                  '</div>'.
                '</div>'.
            '</div>';
      }

      $erg .= $this->pageNumbers();

      $erg .= '</div>';
    }

    return $erg;
  }

  /**
   * Page Numbers
   * ___________________________________________________________________
   */
  protected function pageNumbers()
  {
    $erg = '';
    $page = $this->data['boxPage'] ?? 0;
    $numOfPages = ceil($this->data['numArticles'] / $this->data['articlesPerPage']);

    if ($numOfPages > 1)
    {
      $erg .= '<div class="cbPageController">';
      if ($page > 0)
      {
        $erg .= '<a class="cbPrevPage" href="'.$this->linker->cbBoxLink($this->ep, $this->hook, $this->data['articleBox'], ($page-1)).'">&laquo;</a>';
      }
      $erg .= '<div class="cbPages">';
      for($i = 0; $i < $numOfPages; $i++)
      {
        if ($page != $i)
        {
          $erg .= '<a href="'.$this->linker->cbBoxLink($this->ep, $this->hook, $this->data['articleBox'], $i).'">'.($i+1).'</a>';
        }
        else
        {
          $erg .= '<span class="cbCurrentPage">'.($i+1).'</span>';
        }
      }
      $erg .= '</div>';
      if (($page + 1) < $numOfPages)
      {
        $erg .= '<a class="cbNextPage" href="'.$this->linker->cbBoxLink($this->ep, $this->hook, $this->data['articleBox'], ($page+1)).'">&raquo;</a>';
      }
      $erg .= '</div>';
    }

    return $erg;
  }

}

?>