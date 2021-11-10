<?php

/**
 * This file implements cbArticleGDocsStyle0VF.
 * Style 0 = just plain text, no alignment of assets
 *
 * @author     Konstantinmeyer
 * @since      2021
 */

namespace cb\view\fragment;

class cbArticleGDocsStyle0VF extends cbArticleFrameVF
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
