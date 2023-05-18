<?php
namespace cb\view\fragment;

trait cbGalleryVF
{
  /**
   * draw gallery
   * ___________________________________________________________________
   */
  public function renderGallery()
  {
    $files = $this->data['images'];
    $imgIdx = $this->data['imgIdx'];
    $fileN = $files[$imgIdx]['file'];
    $desc  = $files[$imgIdx]['fileInfo'];

    $prev = isset($files[$imgIdx - 1]['file']) ? ($imgIdx - 1) : (count($files) - 1);
    $next = isset($files[$imgIdx + 1]['file']) ? ($imgIdx + 1) : 0;

    $nextAction  = $this->linker->cbArticleLinkToGalleryImg($this->viewHints['ep'], $this->viewHints['mod'], $this->viewHints['hook'], $this->viewHints['articleHook'], $this->data['articleBox'], $this->data['articleName'], $this->data['articlePage'], $next);
    $prevAction  = $this->linker->cbArticleLinkToGalleryImg($this->viewHints['ep'], $this->viewHints['mod'], $this->viewHints['hook'], $this->viewHints['articleHook'], $this->data['articleBox'], $this->data['articleName'], $this->data['articlePage'], $prev);
    $closeAction = $this->linker->cbArticleLink($this->viewHints['ep'], $this->viewHints['articleMod'], $this->viewHints['articleHook'], $this->data['articleBox'], $this->data['articleName'], $this->data['articlePage']);

    $erg = '<div class="'.$this->data['articleBox'].' '.$this->data['articleName'].'">
            <div id="abcGallery">
              <div id="abcGalleryToolbar">
                <a class="aL" href="'.$prevAction.'" title="Voriges Bild">&laquo;</a>
                <a class="aL" href="'.$nextAction.'" title="Nächstes Bild">&raquo;</a>
                <a href="'.$closeAction.'" title="Zur&uuml;ck">x</a>
              </div>
              <div id="abcGalleryDesc">'.$desc.'</div>
            </div>

            <div id="abcGallerySep"></div>

            <div id="abcGalleryImgBox">
              <a class="aL" href="'.$nextAction.'">
                 <img alt="'.$fileN.'" src="'.$fileN.'" style="cursor: pointer; border: none;" />
              </a>
            </div>
          </div>';

    return $erg;
  }
}

?>
