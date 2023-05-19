<?php

class cbArticleM
{
  public $articleBox = null;
  public $articleName = null;
  public $articleFile = null;

  public $data = array();

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  function __construct($articleBox, $articleName)
  {
    $this->articleBox = $articleBox;
    $this->articleName = $articleName;
    $this->articleFile = getPathFS(CB_DATA_ROOT.$this->articleBox.CB_BUILT.$this->articleName.'.json');

    if (!file_exists($this->articleFile))
    {
      throw new Exception(__CLASS__.': Der angeforderte Artikel "'.$articleName.'" ('.$this->articleFile.') existiert in der Artikel-Box "'.$articleBox.'" nicht.');
    }
  }

  /**
   * Load
   * ___________________________________________________________________
   */
  public function load()
  {
    try
    {
      $this->data = jsonM::load($this->articleFile);
    }
    catch (Exception $e)
    {
      throw $e;
    }
  }

  /**
   * Suche
   * _________________________________________________________________
   */
  public function search($str)
  {
    if ($this->data['type'] == 'classic')
    {
      return $this->searchClassic($str);
    }
    else
    {
      return $this->searchGDocs($str);
    }
  }

  /**
   * Suche in Classic Article
   * ___________________________________________________________________
   */
  public function searchClassic($str)
  {
    $hit = false;

    if (stripos($this->getArticleHeadline(), $str) !== false) $hit = $this->getArticleHeadline();

    $files = (array) $this->getArticleImages();
    if (count($files) > 0)
    {
      foreach ($files as $file)
      {
        if (isset($file['fname']) && (stripos($file['fname'], $str) !== false)) {$hit = $file['fname']; break;}
        if (isset($file['fileInfo']) && (stripos($file['fileInfo'], $str) !== false)) { $hit = $file['fileInfo']; break;}
      }
    }

    $files = (array) $this->getArticleDownloadFiles();
    if (count($files) > 0)
    {
      foreach ($files as $file)
      {
        if (isset($file['fname']) && (stripos($file['fname'], $str) !== false)) {$hit = $file['fname']; break;}
        if (isset($file['fileInfo']) && (stripos($file['fileInfo'], $str) !== false)) { $hit = $file['fileInfo']; break;}
      }
    }

    $files = (array) $this->getArticleMediaFiles();
    if (count($files) > 0)
    {
      foreach ($files as $file)
      {
        if (isset($file['fname']) && (stripos($file['fname'], $str) !== false)) {$hit = $file['fname']; break;}
        if (isset($file['fileInfo']) && (stripos($file['fileInfo'], $str) !== false)) { $hit = $file['fileInfo']; break;}
      }
    }

    $files = (array) $this->getArticleOtherFiles();
    if (count($files) > 0)
    {
      foreach ($files as $file)
      {
        if (isset($file['fname']) && (stripos($file['fname'], $str) !== false)) {$hit = $file['fname']; break;}
        if (isset($file['fileInfo']) && (stripos($file['fileInfo'], $str) !== false)) { $hit = $file['fileInfo']; break;}
      }
    }

    $textPages = (array) $this->getArticlePaginatedText();
    foreach($textPages as $pageIdx => $textArr)
    {
      foreach($textArr as $text)
      {
        $text = strip_tags($text);

        if (($pos = stripos($text, $str)) !== false)
        {
          $hit = $this->extractHit($text, $str, $pos);
          break 2;
        }
      }
    }

    if ($hit !== false)
    {
      $hit = str_ireplace($str, '<strong>'.$str.'</strong>', $hit);

      $result['articleName'] = $this->getArticleName();
      $result['headline'] = $this->getArticleHeadline();
      $result['date'] = $this->getArticleDate();
      $result['abstract'] = $hit;
      $result['articlePage'] = $pageIdx;

      return $result;
    }

    return null;
  }


  /**
   * Suche in GDocs Article
   * ___________________________________________________________________
   */
  public function searchGDocs($str)
  {
    $hit = false;

    if (stripos($this->getArticleHeadline(), $str) !== false) $hit = $this->getArticleHeadline();

    $textPages = $this->getArticlePaginatedText();
    foreach($textPages as $pageIdx => $text)
    {
      $text = strip_tags($text);
      if (($pos = stripos($text, $str)) !== false)
      {
        $hit = $this->extractHit($text, $str, $pos);
        break;
      }
    }

    if ($hit !== false)
    {
      $hit = str_ireplace($str, '<strong>'.$str.'</strong>', $hit);

      $result['articleName'] = $this->getArticleName();
      $result['headline'] = $this->getArticleHeadline();
      $result['date'] = $this->getArticleDate();
      $result['abstract'] = $hit;
      $result['articlePage'] = $pageIdx;

      return $result;
    }

    return null;
  }

  /**
   * extract search hit. not very elegant.
   * _________________________________________________________________
   */
  protected function extractHit($text, $searchStr, $pos)
  {
    $start = ($pos - 75 > 0) ? $pos - 75 : 0;
    $hit = substr($text, $start, (strlen($searchStr) + 150));
    $hit = '...'.$hit.'...';
    return $hit;
  }

  /* Artikel im Ganzen
    _________________________________________________________________
  */
  public function getArticle($detail = '')
  {
    if ($detail !== '')
    {
      return $this->data[$detail];
    }
    else
    {
      return $this->data;
    }
  }

  /* Artikel Name
    _________________________________________________________________
  */
  public function getArticleName()
  {
    return $this->data['articleName'];
  }

  /* rohen, ungeparsten und gerenderten Textkörper zurückgeben,
     inklusive Überschrift / Datum
    _________________________________________________________________
  */
  public function getArticleText()
  {
    return $this->data['articleText'];
  }

  /* Gerenderten Text als Array zurückliefern
    _________________________________________________________________
  */
  public function getArticlePaginatedText()
  {
    return $this->data['paginatedText'];
  }

  /* Textkörper zurückgeben, ohne Überschrift / Datum
    _________________________________________________________________
  */
  public function getArticleAbstract()
  {
    return $this->data['aAbstract'];
  }

  /**
   * Seitenzusammenfassung abrufen - on the fly
   * FIXME: add this to pagesInfo
   *__________________________________________________________________
   */
  public function getArticlePageAbstract($page)
  {
    return $this->data['pagesAbstracts'][$page];
  }

  /* Artikel-Datum zurückgeben
    _________________________________________________________________
  */
  public function getArticleDate()
  {
    return $this->data['date'];
  }

  /* Artikel Überschrift
    _________________________________________________________________
  */
  public function getArticleHeadline()
  {
    return $this->data['headline'];
  }

  /* Bild(er) zurückgeben
    _________________________________________________________________
  */
  public function getArticleImages($idx = false)
  {
    if ($idx !== false)
    {
      return ($idx < count($this->data['images'])) ? $this->data['images'][$idx] : -1;
    }
    else
    {
      return $this->data['images'];
    }
  }

  /* Downloadbare Datei(en) zurückgeben
    _________________________________________________________________
  */
  public function getArticleDownloadFiles($idx = false)
  {
    if ($idx !== false)
    {
      return ($idx < count($this->data['downloadFiles'])) ? $this->data['downloadFiles'][$idx] : -1;
    }
    else
    {
      return $this->data['downloadFiles'];
    }
  }

  /* Mediendateien zurückgeben
    _________________________________________________________________
  */
  public function getArticleMediaFiles($idx = false)
  {
    if ($idx !== false)
    {
      return ($idx < count($this->data['mediaFiles'])) ? $this->data['mediaFiles'][$idx] : -1;
    }
    else
    {
      return $this->data['mediaFiles'];
    }
  }

  /* Andere Dateien zurückgeben
    _________________________________________________________________
  */
  public function getArticleOtherFiles($idx = false)
  {
    if ($idx !== false)
    {
      return ($idx < count($this->data['otherFiles'])) ? $this->data['otherFiles'][$idx] : -1;
    }
    else
    {
      return $this->data['otherFiles'];
    }
  }

  /**
   * get Styles
   * _________________________________________________________________
   */
  public function getStyles()
  {
    if (isset($this->data['styles']))
    {
      return $this->data['styles'];
    }
    return false;
  }
}

?>
