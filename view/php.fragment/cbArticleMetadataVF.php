<?php

namespace cb\view\fragment;

trait cbArticleMetadataVF
{
  /**
   * draw some metadata
   * ___________________________________________________________________
   */
  public function renderMetadata()
  {
    $str = '';
    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $image0 = '';

    if (isset($this->data['images'][0]['file']))
    {
      $image0 = getPathURL($this->data['images'][0]['file']);
    }
    elseif (isset($this->data['inlineImages'][0]))
    {
      $image0 = $this->data['inlineImages'][0];
    }

    $imageStr = ($image0 != '') ? '<meta property="og:image" content="'.$image0.'">' : '';
    $str .= $imageStr;
    $str .= '<meta property="og:title" content="'.htmlentities($this->data['headline']).'">';
    $str .= '<meta property="og:description" content="'.htmlentities($this->data['aAbstract']).'">';
    $str .= '<meta property="og:type" content="Website">';
    $str .= '<meta property="og:url" content="'.$url.'">';
    $str .= '<meta property="og:site_name" content="'.$_SERVER['SERVER_NAME'].'">';

    $imageStr = ($image0 != '') ? '"image": ["'.$image0.'"]' : '';
    $str .= '<script type="application/ld+json">'.
            '{'.
               '"@context": "https://schema.org",'.
               '"@type": "NewsArticle",'.
               '"headline": "'.$this->data['headline'].'",'.
               '"dateModified": "'.$this->data['date'].'",'.
               $imageStr.
            '}'.
            '</script>';

    return $str;
  }
}

?>