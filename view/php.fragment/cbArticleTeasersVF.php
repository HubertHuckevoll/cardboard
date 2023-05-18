<?php
namespace cb\view\fragment;

trait cbArticleTeasersVF
{
  public function renderTeasers()
  {
    $str = '';

    foreach ($this->data as $artObj)
    {
      $imgs = array_merge((array) $artObj['images'], (array) $artObj['inlineImages']);
      $max = count($imgs)-1;

      if ($max >= 0)
      {
        $x = mt_rand(0, $max);
        $imgO = $imgs[$x];

        if (isset($imgO['thumb']))
        {
          $imgUrl = $imgO['thumb'];
        }
        else
        {
          $imgUrl = $imgO;
        }

        $href = $this->linker->cbArticleLink($this->viewHints['ep'], $this->viewHints['articleMod'], $this->viewHints['articleHook'], $artObj['articleBox'], $artObj['articleName']);

        $str .= '<figure class="cbArticleTeaser">'.
                  '<a class="cbArticleTeaserLink" href="'.$href.'" title="'.$artObj['headline'].'">'.
                    '<img class="cbArticleTeaserImg" src="'.$imgUrl.'" alt="'.$artObj['headline'].'">'.
                  '</a>'.
                  '<figcaption class="cbArticleTeaserAbstract">'.
                    $artObj['aAbstract'].
                  '</figcaption>'.
                '</figure>';
      }
    }

    return $str;
  }
}

?>