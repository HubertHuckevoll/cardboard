<?php

class cbBoxesM
{
  public $boxes = null;
  public $boxesF = 'boxes.json';

  /**
   * Load
   * _________________________________________________________________
   */
  public function load()
  {
    try
    {
      $boxes = jsonM::load($this->boxesF);
      $this->boxes = $boxes;
    }
    catch (Exception $e)
    {
      throw $e;
    }
  }

  /**
   * Set
   * _________________________________________________________________
   */
  public function set($boxesF, $boxes)
  {
    try
    {
      return jsonM::save($boxesF, $boxes);
    }
    catch(Exception $e)
    {
      throw $e;
    }
  }

  /**
   * Get
   * _________________________________________________________________
   */
  public function get()
  {
    return $this->boxes;
  }

  /**
   * get Box "Ids"
   * _________________________________________________________________
   */
  public function getBoxNames($exceptionAsLogin=null)
  {
    $boxes = $this->filter('exclude', $exceptionAsLogin);
    $boxIds = array();

    foreach($boxes as $boxLogin => $box)
    {
      $boxIds[] = $box['box'];
    }

    return $boxIds;
  }


  /**
   * get Box by Name
   * _________________________________________________________________
   */
  public function getBoxByName($boxName)
  {
    foreach($this->boxes as $boxLogin => $box)
    {
      if ($box['box'] === $boxName)
      {
        return $box;
      }
    }
    return false;
  }

  /**
   * get Box by Lodschin
   * _________________________________________________________________
   */
  public function getBoxByLogin($login)
  {
    if (isset($this->boxes[$login]))
    {
      return $this->boxes[$login];
    }
    return false;
  }

  /**
   * search
   * _________________________________________________________________
   */
  public function search($searchTerm, $filterOp=null, $filterData=null)
  {
    $erg = array();
    $boxes = $this->filter($filterOp, $filterData);

    foreach ($boxes as $box)
    {
      $cb = new cbBoxM($box['box']);
      $ret = $cb->search($searchTerm);
      if (count($ret) > 0)
      {
        $erg[$box['box']] = $ret;
      }
    }

    return $erg;
  }

  /**
   * getMostRecentArticles of a selection of boxes
   * _________________________________________________________________
   */
  public function getArticleList($from=0, $num=0, $sorting=false, $filterOp=null, $filterData=null)
  {
    $boxes = $this->filter($filterOp, $filterData);
    $articleList = array();
    $finalArticleList = array();

    foreach ($boxes as $artBox)
    {
      $box = $artBox['box'];
      $cbb = new cbBoxM($box);
      $articleList = $cbb->getArticleList();
      foreach($articleList as $articleKey => $article)
      {
        $finalArticleList[$articleKey] = array('box' => $box, 'article' => $article);
      }
    }

    if ($sorting == true)
    {
      krsort($finalArticleList);
    }

    if (($from != 0) || ($num != 0))
    {
      $finalArticleList = array_slice($finalArticleList, $from, $num);
    }

    return $finalArticleList;
  }

  /**
   * getMostRecentArticles of a selection of boxes
   * _________________________________________________________________
   */
  public function getArticles($from=0, $num=0, $filterOp=null, $filterData=null)
  {
    $articles = array();
    $articleList = $this->getArticleList($from, $num, true, $filterOp, $filterData);

    foreach($articleList as $articleEntry)
  	{
  		$box = $articleEntry['box'];
  		$article = $articleEntry['article'];
  		try
  		{
			  $art = new cbArticleM($box, $article);
			  $art->load();
			  $articles[] = $art;
  		}
  		catch (Exception $e)
  		{
  		  dv(__CLASS__.': '.$e->getMessage());
  		}
  	}

    return $articles;
  }

  /**
   * getMostRecentArticles of a selection of boxes
   * _________________________________________________________________
   */
  public function getMostRecentArticles($num, $filterOp=null, $filterData=null)
  {
    return $this->getArticles(0, $num, $filterOp, $filterData);
  }

  /**
   * Get random articles
   * $num = random articles
   * _________________________________________________________________
  */
  public function getRandomArticles($num, $filterOp=null, $filterData=null)
  {
    $articles = array();
    $boxes = $this->filter($filterOp, $filterData);
    $articleList = $this->getArticleList(null, null, false, $filterOp, $filterData);
    $articleKeys = array_keys($articleList);
  	$artNum = count($articleList);

  	for($i = 0; $i <= $num; $i++)
  	{
  		$x = random_int(0, ($artNum-1));

  		$box = $articleList[$articleKeys[$x]]['box'];
  		$article = $articleList[$articleKeys[$x]]['article'];
  		try
  		{
			  $art = new cbArticleM($box, $article);
			  $art->load();
			  $articles[] = $art;
  		}
  		catch (Exception $e)
  		{
        logger::vh($e);
  		}
  	}

    return $articles;
  }

  /**
   * get article
   * _________________________________________________________________
   */
  function getArticle($articleBox, $article)
  {
    try
    {
      $art = new cbArticleM($articleBox, $article);
      $art->load();
      return $art;
    }
    catch(Exception $e)
    {
      throw $e;
    }
  }

  /**
   * filter
   * _________________________________________________________________
   */
  public function filter($filterOp, $filterData)
  {
    $boxes = $this->boxes;
    $dontAdd = false;

    switch ($filterOp)
    {
      case 'include':
        $boxes = array();
        foreach($this->boxes as $boxLogin => $box)
        {
          if (is_array($filterData))
          {
            foreach($filterData as $login)
            {
              if ($login == $boxLogin)
              {
                $boxes[] = $box;
              }
            }
          }
          else
          {
            if ($boxLogin == $filterData)
            {
              $boxes[] = $box;
            }
          }
        }
      break;

      case 'exclude':
        if ($filterData != '')
        {
          $boxes = array();
          foreach($this->boxes as $boxLogin => $box)
          {
            $dontAdd = false;
            if (is_array($filterData))
            {
              foreach($filterData as $login)
              {
                if ($login == $boxLogin)
                {
                  $dontAdd = true;
                  break;
                }
              }
            }
            else
            {
              if ($boxLogin == $filterData)
              {
                $dontAdd = true;
              }
            }

            if ($dontAdd == false)
            {
              $boxes[] = $box;
            }
          }
        }
      break;
    }

    return $boxes;
  }

  /**
   * convert article objects to arrays
   * FIXME: make all methods in this class optionally return the arrays
   * create a goddamn query object
   * ________________________________________________________________
   */
  public function articleObjs2Array($artObjs)
  {
    foreach ($artObjs as $artObj)
    {
      $arts[] = $artObj->getArticle();
    }
    return $arts;
  }

}

?>
