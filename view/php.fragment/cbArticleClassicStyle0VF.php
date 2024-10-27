<?php

/**
 * This file implements cbArticleClassicStyle0VF.
 * Style 0 = just plain text, no alignment of assets
 *
 * @author     Konstantinmeyer
 * @since      2021
 */

namespace cb\view\fragment;

class cbArticleClassicStyle0VF extends cbArticleFrameVF
{
  public $viewHints = array();

  /**
   * constructor
   * ___________________________________________________________________
   */
  function __construct($ep, $hook, $linker = null)
  {
    parent::__construct($ep, $hook, $linker);
  }

  /**
   * without alignment of assets
   * ___________________________________________________________________
   */
  public function renderArticleBody()
  {
    $page = (isset($this->data['articlePage'])) ? $this->data['articlePage'] : 0;
    $pages = (array) $this->data['paginatedText'];
    $numOfPages = count($pages);
    $textArr = (array) $this->data['paginatedText'][$page];
    $erg = '';

    if (count($textArr) > 0)
    {
      foreach($textArr as $textChunk)
      {
        // Text
        // we're not using p tags, as our text might contain rendered
        // cardboard tags that could be block level elements themselves
        $erg .= '<div class="articleParagraphText">'.$textChunk.'</div>';
      }
    }

    return $erg;
  }

}

?>
