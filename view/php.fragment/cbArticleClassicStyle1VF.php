<?php

/**
 * This file implements cbArticleClassicStyle1VF.
 * Style 1 = align images left / right of article
 * @author     Konstantinmeyer
 * @since      2021
 */

namespace cb\view\fragment;

trait cbArticleClassicStyle1VF
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
    $backLinkLabel = 'Zurück';
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
   * with alignment of assets
   * IMPORTANT / FIXME?
   * we're not using p tags, as our text might contain rendered
   * cardboard tags that could be block level elements themselves
   * ___________________________________________________________________
   */
  public function renderArticleBody()
  {
    $page = (isset($this->data['articlePage'])) ? $this->data['articlePage'] : 0;
    $pages = (array) $this->data['paginatedText'];
    $numOfPages = count($pages);
    $files = (array) $this->data['downloadFiles'];
    $images = (array) $this->data['images'];
    $textArr = (array) $this->data['paginatedText'][$page];
    $erg = '';

    // Bilder auf Seiten verteilen
    // _________________________________________________________________
    if (($pages > 0) and ($images > 0))
    {
      $imagesPerPage = ceil(count($images) / count($pages)); // = Anzahl Bilder / Anzahl Seiten
      if ($page == 0)
      {
        $imgStartIdx = 0;
      }
      else
      {
        $imgStartIdx = (($page-1) * $imagesPerPage) + $imagesPerPage;
      }
      $imgEndIdx = $imgStartIdx + $imagesPerPage;
    }
    else
    {
      $imagesPerPage = 0;
    }

    // Layout
    // _________________________________________________________________

    // List attachments
    $hasPdf = false;
    if (count($files) > 0)
    {
      $erg .= '<div class="articleAttachments">'.
                 '<div class="articleAttachmentLabel">Download</div>';

      foreach($files as $fileN)
      {
        $fileN = $fileN['file'];
        if (getFileExt($fileN) == 'pdf') $hasPdf = true;

        $iconPath = CB_IMG_ROOT.getFileExt($fileN).'.gif';
        $icon = (file_exists(getPathFS($iconPath))) ? $iconPath : CB_IMG_ROOT.'someFile.gif';

        $erg .= '<div class="articleAttachmentCont">'.
                   '<img class="articleAttachmentIcon" alt="'.getFileExt($fileN).'.gif" src="'.$icon.'">'.
                   '<a href="'.$fileN.'" target="_blank">'.basename($fileN).'</a>'.
                '</div>';
      }

      if ($hasPdf)
      {
        $erg .= '<div class="articleGetAdobe">
                    <a href="http://www.adobe.com/products/acrobat/readstep.html" target="_blank">
                      Adobe Reader downloaden
                    </a>
                    <img alt="Adobe Reader Logo" src="'.CB_IMG_ROOT.'adobeReader.gif'.'">
                 </div>';
      }

      $erg .= '</div>';
    }

    // Align images and text -------------------------------------------------------------------
    $i = $imgStartIdx;

    if (count($textArr) > 0)
    {
      foreach($textArr as $textChunk)
      {
        // Absatz öffnen
        $erg .= '<div class="articleParagraph">';

        // Bildcontainer und Bild mit Unterschrift
        if (isset($images[$i]) && ($i < $imgEndIdx))
        {
          $imgBox = '';
          $fileN       = $images[$i]['file'];
          $thumb       = $images[$i]['thumb'];
          $imgInfo     = $images[$i]['fileInfo'];
          $thumbWidth  = $images[$i]['thumbWidth'];
          $thumbHeight = $images[$i]['thumbHeight'];
          if (!isset($thumbWidth)) $thumbWidth = 0;
          if (!isset($thumbHeight)) $thumbHeight = 0;
          $abcImgContClass = (bcmod($i, 2) != 0) ? 'articleParagraphImgContRight' : 'articleParagraphImgContLeft';

          if (isset($this->viewHints['galleryHook']) && ($this->viewHints['galleryHook'] != ''))
          {
            $iurl = $this->linker->href(
              $this->viewHints['ep'],
              ['mod'=>$this->viewHints['galleryMod'],
               'hook'=>$this->viewHints['galleryHook'],
               'articleHook'=>$this->viewHints['hook'],
               'articleBox'=>$this->data['articleBox'],
               'article'=>$this->data['articleName'],
               'articlePage'=>$page,
               'imgIdx'=>$i
              ]);
          }
          else
          {
            $iurl = 'javascript:void(0);';
          }

          $imgBox .= '<figure class="articleParagraphImgCont '.$abcImgContClass.'" style="width: '.$thumbWidth.'px;">';
          $imgBox .= '<a href="'.$iurl.'">'
                      .'<img class="articleParagraphImg" alt="'.$thumb.'" src="'.$thumb.'" data-hires="'.$fileN.'">'
                    .'</a>';

          if ($imgInfo)
          {
            $imgBox .= '<figcaption class="articleParagraphImgCaption">'.$imgInfo.'</figcaption>';
          }
          $imgBox .= '</figure>';
          $erg .= $imgBox;
          $i++;
        }

        // Text
        $erg .= '<div class="articleParagraphText">'.$textChunk.'</div>';

        // Paragraph schliessen
        $erg .= '</div>';
      }
    }

    // Falls Restbilder vorhanden - ausgeben
    if (    (count($images) > 0)
         && ($i < count($images))
         && ($page == ($numOfPages-1))
       )
    {
      $erg .= '<div class="articleImgOnlyParagraph">';

      while($i < count($images))
      {
        $fileN       = $images[$i]['file'];
        $thumb       = $images[$i]['thumb'];
        $thumbWidth  = $images[$i]['thumbWidth'];
        $imgInfo     = $images[$i]['fileInfo'];

        if (isset($this->viewHints['galleryHook']) && ($this->viewHints['galleryHook'] != ''))
        {
          $iurl = $this->linker->href($this->viewHints['ep'], ['mod'=>$this->viewHints['galleryMod'], 'hook'=>$this->viewHints['galleryHook'], 'articleHook'=>$this->hook, 'articleBox'=>$this->data['articleBox'], 'article'=>$this->data['articleName'], 'articlePage'=>$page, 'imgIdx'=>$i]);
        }
        else
        {
          $iurl = 'javascript:void();';
        }

        $erg .= '<figure class="articleParagraphImgCont articleParagraphImgContLeft" style="width: '.$thumbWidth.'px;">';
        $erg .= '<a href="'.$iurl.'">'
                  .'<img class="articleParagraphImg" alt="'.$thumb.'" src="'.$thumb.'" data-hires="'.$fileN.'" />'
               .'</a>';

        if ($imgInfo) {
         $erg .= '<figcaption class="articleParagraphImgCaption">'.$imgInfo.'</figcaption>';
        }
        $erg .= '</figure>';
        $i++;
      }
      $erg .= '</div>';
    }

    return $erg;
  }

}

?>
