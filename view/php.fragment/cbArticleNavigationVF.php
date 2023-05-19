<?php
namespace cb\view\fragment;

trait cbArticleNavigationVF
{
  public function render()
  {
    $erg = '';
    $sel = '';

    if (count($this->data['model']['pagesInfo']))
    {
      $erg = '<ul>';

      for($i = 0; $i < count($this->data['model']['paginatedText']); $i++)
      {
        $artPage = $this->data['model']['paginatedText'][$i];
        $caption = $this->data['model']['pagesInfo'][$i];
        $sel = ($this->data['meta']['articlePage'] == $i) ? ' class="selected"' : '';
        $url = $this->linker->cbArticleLink($this->ep, $this->mod, $this->hook, $this->data['model']['articleBox'], $this->data['model']['articleName'], $i);
        $erg .= '<li'.$sel.'><a href="'.$url.'">'.$caption.'</a></li>';
      }

      $erg .= '</ul>';
    }

    return $erg;
  }
}

?>