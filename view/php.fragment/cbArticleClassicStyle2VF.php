<?php

/**
 * This file implements cbArticleClassicStyle2VF.
 * Style 2 = big header image, rest of images on bottom of every page
 * @author     Konstantinmeyer
 * @since      2021
 */

namespace cb\view\fragment;

class cbArticleClassicStyle2VF extends cbArticleFrameVF
{
  public $viewHints = array();

  /**
   * constructor
   * ___________________________________________________________________
   */
  function __construct($ep = '', $hook, $linker = null)
  {
    parent::__construct($ep, $hook, $linker);
  }

  /**
   * with alignment of assets
   * IMPORTANT:
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
                   '<img class="articleAttachmentIcon" alt="'.getFileExt($fileN).'.gif" src="'.$icon.'" />'.
                   '<a href="'.$fileN.'" target="_blank">'.basename($fileN).'</a>'.
                '</div>';
      }

      if ($hasPdf)
      {
        $erg .= '<div class="articleGetAdobe">
                    <a href="http://www.adobe.com/products/acrobat/readstep.html" target="_blank">
                      Adobe Reader downloaden
                    </a>
                    <img alt="Adobe Reader Logo" src="'.CB_IMG_ROOT.'adobeReader.gif'.'" />
                 </div>';
      }

      $erg .= '</div>';
    }

    // Header image -------------------------------------------------------------------
    $i = $imgStartIdx;

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

      $imgBox .= '<figure class="articleHeaderImgFig">';
      $imgBox .= '<img class="articleHeaderImg" alt="'.$fileN.'" src="'.$fileN.'">';
      if ($imgInfo)
      {
        $imgBox .= '<figcaption class="articleHeaderImgCaption">'.$imgInfo.'</figcaption>';
      }
      $imgBox .= '</figure>';
      $erg .= $imgBox;
      $i++;
    }

    if (count($textArr) > 0)
    {
      foreach($textArr as $textChunk)
      {
        // Absatz öffnen
        $erg .= '<div class="articleParagraph">';

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
          $iurl = $this->linker->href($this->ep, array('hook'=>$this->viewHints['galleryHook'], 'articleHook'=>$this->hook, 'articleBox'=>$this->data['articleBox'], 'article'=>$this->data['articleName'], 'articlePage'=>$page, 'imgIdx'=>$i));
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
