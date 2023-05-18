<?php
namespace cb\view\fragment;

class cbSearchVF extends cbBaseVF
{
  /**
   * draw API
   * _________________________________________________________________
   */
	public function render()
	{
	  $none = true;

    $erg  = '';
	  $erg .= '<div id="searchResultsBox">'.
		    			'<h3>Suchergebnisse</h3>'.
	    				'<ul id="searchResultsList">';

    if (count($this->data) > 0)
    {
			$none = false;
      foreach($this->data as $cbid => $hits)
      {
      	foreach($hits as $hit)
        {
	        $erg .= '<li>'.
	                  '<a href="'.$this->linker->cbArticleLink($this->ep, $this->mod, $this->hook, $cbid, $hit['articleName'], $hit['articlePage']).'">'.$hit['headline'].':</a>'.
	                  '&nbsp;'.$hit['abstract'].
	                '</li>';
      	}
      }
    }
    if ($none)
    {
      $erg .= '<li><strong>Der Suchbegriff wurde leider nicht gefunden.</strong></li>';
    }

	  $erg .= '</ul></div>';

	  return $erg;
  }
}

?>