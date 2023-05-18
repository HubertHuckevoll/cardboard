<?php

/**
 * This file implements cbArticleGDocsStyle0VF.
 * Style 0 = just plain text, no alignment of assets
 *
 * @author     Konstantinmeyer
 * @since      2021
 */

namespace cb\view\fragment;

trait cbArticleGDocsStyle0VF
{

  /**
   * render article
   * ___________________________________________________________________
   */
  public function renderArticle()
  {
    $erg = '';
    $erg .= $this->openArticleWrapper();
    $erg .= $this->socialButtons();
    $erg .= $this->date();
    $erg .= $this->headline();
    $erg .= $this->renderArticleBody();
    $erg .= $this->pageNumbers();
    $erg .= $this->backLink();
    $erg .= $this->closeArticleWrapper();

    return $erg;
  }

  /**
   * open article wrapper
   * main wrapper - add some classes for easier css/js selection
   * ___________________________________________________________________
   */
  protected function openArticleWrapper()
  {
    return '<div class="cbArticle '.$this->data['articleBox'].' '.$this->data['articleName'].'">';
  }

  /**
   * close article wrapper
   * close main wrapper
   * ___________________________________________________________________
   */
  protected function closeArticleWrapper()
  {
    return '</div>';
  }

  /**
   * sharing buttons
   * ___________________________________________________________________
   */
  protected function socialButtons()
  {
    $erg = '';

    // "social" buttons - we use the non-tracking static versions
    $url = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    $erg .= '<div class="sharingButtons">'.
              '<div class="sharingButton"><a href="https://www.facebook.com/sharer/sharer.php?u='.$url.'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><img src="'.CB_IMG_ROOT.'facebook_share.png" alt="Auf Facebook teilen" /></a></div>'.
              '<div class="sharingButton"><a href="http://twitter.com/share?url='.$url.'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><img src="'.CB_IMG_ROOT.'twitter_share.png" alt="Auf Twitter teilen" /></a></div>'.
              '<div class="sharingButton"><a href="mailto:?subject='.urlencode($this->data['headline']).'&amp;body='.$url.'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><img alt="mail icon" src="'.CB_IMG_ROOT.'mail.png" /></a></div>'.
            '</div>';

    return $erg;
  }

  /**
   * Date
   * _______________________________________________________________
   */
  protected function date()
  {
    return '<div class="articleDate">'.$this->fDate($this->data['date']).'</div>';
  }

  /**
   * Headline
   * _________________________________________________________________
   */
   protected function headline()
   {
     $subTitle = isset($this->data['pagesInfo'][$this->data['articlePage']]) ?? '';

     $erg = '<div class="articleHeadline">'.$this->data['headline'];
     if ($subTitle != '')
     {
       $erg .= ' - '.$this->data['pagesInfo'][$this->data['articlePage']];
     }
     $erg .= '</div>';

     return $erg;
   }

  /**
   * back link
   * ___________________________________________________________________
   */
  protected function backLink()
  {
    $backLinkLabel = 'zum Index von "'.$this->data['boxNameAlias'].'"';
    $erg = '';

    if (isset($this->viewHints['backLinkHook']) && ($this->viewHints['backLinkHook'] != ''))
    {
      $erg = '<div class="articleIndexLink">'.
               '[&nbsp;<a href="'.$this->linker->cbBoxLinkFromArticle($this->viewHints['ep'], $this->viewHints['backLinkMod'], $this->viewHints['backLinkHook'], $this->data['articleBox'], $this->data['articleName']).'">'.$backLinkLabel.'</a>&nbsp;]'.
             '</div>';
    }

    return $erg;
  }

  /**
   * Page Numbers
   * ___________________________________________________________________
   */
  protected function pageNumbers()
  {
    $page = $this->data['articlePage'];
    $pages = (array) $this->data['paginatedText'];
    $numOfPages = count($pages);
    $erg = '';

    if (
        ($numOfPages > 1) &&
        ($this->viewHints['pageNumbers'] !== false)
       )
    {
      $erg .= '<div class="cbPageController">';
      if ($page > 0) {
        $erg .= '<a class="cbPrevPage" href="'.$this->linker->cbArticleLink($this->viewHints['ep'], $this->viewHints['mod'], $this->viewHints['hook'], $this->data['articleBox'], $this->data['articleName'], ($page-1)).'">&laquo;</a>';
      }
      $erg .= '<div class="cbPages">';
      for($i = 0; $i < $numOfPages; $i++) {
        if ($page != $i) {
          $erg .= '<a href="'.$this->linker->cbArticleLink($this->viewHints['ep'], $this->viewHints['mod'], $this->viewHints['hook'], $this->data['articleBox'], $this->data['articleName'], $i).'">'.($i+1).'</a>';
        } else {
          $erg .= '<span class="cbCurrentPage">'.($i+1).'</span>';
        }
      }
      $erg .= '</div>';
      if (($page + 1) < $numOfPages) {
        $erg .= '<a class="cbNextPage" href="'.$this->linker->cbArticleLink($this->viewHints['ep'], $this->viewHints['mod'], $this->viewHints['hook'], $this->data['articleBox'], $this->data['articleName'], ($page+1)).'">&raquo;</a>';
      }
      $erg .= '</div>';

      return $erg;
    }
  }

  /**
   * without alignment of assets
   * ___________________________________________________________________
   */
  public function renderArticleBody()
  {
    $page = (isset($this->data['articlePage'])) ? $this->data['articlePage'] : 0;
    $text = $this->data['paginatedText'][$page];
    $erg = '';

    // Styles from GDocs
    // $erg .= '<style>'.$this->data['styles'].'</style>';

    // Text from GDocs
    $erg .= '<div>'.$text.'</div>';

    return $erg;
  }

}

?>
