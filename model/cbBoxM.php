<?php

class cbBoxM
{
  public $articleBox = '';
  public $articleBoxPath = '';
  public $articleBoxIdxF = '';

  public $articleList = array();
  public $numArticles = null;
  public $meta = array();

  public $hiddenArticleList = array();

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($articleBox)
  {
    if ($articleBox == '')
    {
      throw new Exception(__CLASS__.': Der Name der angeforderten Artikel-Box war leer.');
    }

    $this->articleBox = $articleBox;
    $this->articleBoxPath = CB_DATA_ROOT.$this->articleBox;
    $this->articleBoxIdxF = getPathFS($this->articleBoxPath.CB_BUILT.$this->articleBox.'.index.json');

    if (!file_exists($this->articleBoxIdxF))
    {
      throw new Exception(__CLASS__.': Die Artikel - Box: "'.$articleBox.'" ('.$this->articleBoxIdxF.') scheint nicht zu existieren.');
    }

  }

  /**
   * fetchArticles
   * _________________________________________________________________
   */
  public function fetchArticles()
  {
    $prefs = cbArticlePrefsM::getInstance();
    $articleList = array();
    $hiddenArticleList = array();

    try
    {
      $data = jsonM::load($this->articleBoxIdxF);
      foreach ($data as $articleKey => $article)
      {
        $invisible = $prefs->getPref($this->articleBox, $article['articleName'], 'invisible');
        if ($invisible == false)
        {
          $this->articleList[$articleKey] = $article['articleName'];
          $this->meta[$article['articleName']] = $article;
        }
        elseif ($invisible == true)
        {
          $this->hiddenArticleList[$articleKey] = $article['articleName'];
          $this->meta[$article['articleName']] = $article;
        }
      }
      $this->numArticles = count($this->articleList);
    }
    catch (Exception $e)
    {
      throw $e;
    }
  }

  /**
   * create an Article Object silently
   * _________________________________________________________________
   */
  public function articleFactory($articleName)
  {
    try
    {
      $artObj = new cbArticleM($this->articleBox, $articleName);
      $artObj->load();

      return $artObj;
    }
    catch(Exception $e)
    {
      logger::vh($e->getMessage());

      return false;
    }
  }

  /**
   * get articleList property, "speed-safe"
   * _________________________________________________________________
   */
  public function getArticleList()
  {
    if (count($this->articleList) == 0)
    {
      $this->fetchArticles();
    }
    return $this->articleList;
  }

  /**
   * get hiddenArticleList property, "speed-safe"
   * _________________________________________________________________
   */
  public function getHiddenArticleList()
  {
    if (count($this->hiddenArticleList) == 0)
    {
      $this->fetchArticles();
    }
    return $this->hiddenArticleList;
  }

  /**
   * Suche
   * _________________________________________________________________
   */
  public function search($str)
  {
    $treffer = array();
    $articleList = $this->getArticleList();

    foreach($articleList as $artName)
    {
      $hit = array();
      $artObj = $this->articleFactory($artName);
      if ($artObj)
      {
        $erg = $artObj->search($str);
        if ($erg != null)
        {
          $treffer[] = $erg;
        }
      }
    }

    return $treffer;
  }

  /**
   * Pfad für Artikel zurückgeben
   * _________________________________________________________________
   */
  public function getPathForArticle($article)
  {
    return $this->articleBoxPath.DIRECTORY_SEPARATOR.$article;
  }

  /**
   * return article object
   * _________________________________________________________________
   */
  public function getArticleByName($artName)
  {
    return($this->articleFactory($this->articleList[$artName]));
  }

  /**
   * return array of article objects
   * _________________________________________________________________
   */
  public function getArticlesByName($artNameArr)
  {
    $artObjs = array();
    foreach ($artNameArr as $ano)
    {
      $aO = $this->articleFactory($ano);

      if ($aO) $artObjs[] = $aO;
    }

    return $artObjs;
  }

  /**
   * return full article(s) info as list of article objects
   * _________________________________________________________________
   */
  public function getArticles($from = null, $num = null)
  {
    $arts = array();
    $artNames = $this->getArticleList();
    $artNum = count($artNames);

    // From, To
    $from = ($from != null) ? $from : 0;
    $num  = ($num  != null) ? $num  : $artNum;
    if ($from > $artNum) {$from = 0;}
    if (($from + $num) > $artNum) {$num = $artNum - $from;}

    $artNames = array_slice($artNames, $from, $num);
    $artObjs = $this->getArticlesByName($artNames);

    return $artObjs;
  }

  /**
   * neueste Artikel holen
   * _________________________________________________________________
   */
  public function getMostRecentArticles($num = 1)
  {
    $al = $this->getArticleList();
    $al = array_slice($al, 0, $num);
    $articles = $this->getArticlesByName($al);
    return $articles;
  }

  /**
   * get random articles
   * _________________________________________________________________
   */
  public function getRandomArticles($num)
  {
    $artNames = $this->getArticleList();
    $artKeys = array_keys($artNames);

  	$artNum = count($artNames);
		$arts = array();

  	for($i = 0; $i < $num; $i++)
  	{
  		$x = random_int(0, ($artNum-1));
			$arts[] = $artNames[$artKeys[$x]];
  	}

    $articles = $this->getArticlesByName($arts);

    return $articles;
  }

  /**
   * get the collection of hidden articles
   * _________________________________________________________________
   */
  function getHiddenArticles($from = null, $num = null)
  {
    $arts = array();
    $artNames = $this->getHiddenArticleList();
    $artNum = count($artNames);

    // From, To
    $from = ($from != null) ? $from : 0;
    $num  = ($num  != null) ? $num  : $artNum;
    if ($from > $artNum) { $from = 0; }
    if (($from + $num) > $artNum) {$num = $artNum - $from;}

    $artNames = array_slice($artNames, $from, $num);
    $artObjs = $this->getArticlesByName($artNames);

    return $artObjs;
  }

  /**
   * metadata for article from foldername / gsheet
   * _________________________________________________________________
   */
  public function getArticleMetadata($articleName, $detail = '')
  {
    if ($detail == '')
    {
      return $this->meta[$articleName];
    }
    else
    {
      return $this->meta[$articleName][$detail];
    }
  }

  /**
   * convert collection of Article Objects to Arrays
   * _________________________________________________________________
   */
  public function articleObjs2Array($artObjs)
  {
    $arts = array();

    foreach ($artObjs as $artObj)
    {
      $arts[] = $artObj->getArticle();
    }

    return $arts;
  }

}

?>
