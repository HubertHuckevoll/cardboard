<?php
namespace cb\view\fragment;

trait cbSearchVF
{
  /**
   * draw API
   * _________________________________________________________________
   */
	public function renderSearch()
	{
	  $none = true;

    $erg  = '';
	  $erg .= '<ul id="searchResultsList">';
    $data = $this->data['results'];

    if (count($data) > 0)
    {
			$none = false;
      foreach($data as $cbid => $hits)
      {
      	foreach($hits as $hit)
        {
	        $erg .= '<li>'.
	                  '<a href="'.$this->linker->cbArticleLink($this->viewHints['ep'], $this->viewHints[$cbid], 'index', $cbid, $hit['articleName'], $hit['articlePage']).'">'.$hit['headline'].':</a>'.
	                  '&nbsp;'.$hit['abstract'].
	                '</li>';
      	}
      }
    }
    if ($none)
    {
      $erg .= '<li><strong>Der Suchbegriff wurde leider nicht gefunden.</strong></li>';
    }

	  $erg .= '</ul>';

	  return $erg;
  }
}

?>