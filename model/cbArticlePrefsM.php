<?php

/**
 * cbArticlePrefsM - singleton pattern
 * FIXME: should this be a regular dependency?
 * ___________________________________________________________
 */

class cbArticlePrefsM
{
  private static $instance = null;
  
  private $prefDefaults = array (
    'invisible' => false,
    'commentsDisabled' => false
  );
  
  private $prefs = array();
  private $isLoaded = array(); // array of booleans

  /**
   * get instance
   * _________________________________________________________________
   */
  public static function getInstance()
  {
		if (self::$instance === null)
		{
		  self::$instance = new cbArticlePrefsM();
		}
		
    return self::$instance;
  }
  
  /**
   * load prefs
   * _________________________________________________________________
   */
  public function load($articleBox)
  {
    if (!$this->isLoaded[$articleBox] === true)
    {
      $prefsF = getPathFS(CB_DATA_ROOT.$articleBox.CB_DATA_PREFS.'prefs.json');
      try
      {
        $dJson = jsonM::load($prefsF);
        $this->prefs[$articleBox] = $dJson;
        $this->isLoaded[$articleBox] = true;
        return true;
      }
      catch (Exception $e)
      {
        return false;
      }
    }
  }
  
  /**
   * force reload by emptying the (corresponding) array
   * _________________________________________________________________
   */
  public function forceReload($articleBox = '')
  {
    if ($articleBox !== '')
    {
      unset($this->prefs[$articleBox]);
      $this->isLoaded[$articleBox] = false;
    }
    else
    {
      unset($this->prefs);
      $this->isLoaded = array();
    }
  }

  /**
   * save Prefs
   * _________________________________________________________________
   */
  public function save($articleBox)
  {
    $prefsF = getPathFS(CB_DATA_ROOT.$articleBox.CB_DATA_PREFS.'prefs.json');
    try
    {
      $ret = jsonM::save($prefsF, $this->prefs[$articleBox]);
      return $ret;
    }
    catch (Exception $e)
    {
      throw $e;
    }
  }
  
  /**
   * cleanupPrefs, remove old Prefs
   * FIXME: not used, where to call?
   * _________________________________________________________________
   */
  public function sanitizePrefs($prefArr, $defaultsName)
  {
    $cleanPrefs = $this->prefDefaults[$defaultsName];
    
    foreach ($cleanPrefs as $cpk => $cpv)
    {
      foreach ($prefArr as $lpk => $lpv)
      {
        if ($lpk == $cpk)
        {
          $cleanPrefs[$cpk] = $lpv;
        }
      }
    }
    
    return $cleanPrefs;
  }
  
  /**
   * get cbArticle pref
   * _________________________________________________________________
   */
  public function getPref($articleBox, $articleName, $key)
  {
    $this->load($articleBox);
    $val = false;

    if (isset($this->prefDefaults[$key]))
    {
      $val = $this->prefDefaults[$key];
      
      if (isset($this->prefs[$articleBox][$articleName][$key]))
      {
        $val = $this->prefs[$articleBox][$articleName][$key];
      }
    }

    return $val;
  }
  
  /**
   * get cbArticle prefs
   * _________________________________________________________________
   */
  public function getPrefs($articleBox, $articleName)
  {
    $this->load($articleBox);
    $val = $this->prefDefaults;

    if (isset($this->prefs[$articleBox][$articleName]))
    {
      $val = $this->prefs[$articleBox][$articleName];
    }

    return $val;
  }
  
  /**
   * set cbArticle pref
   * _________________________________________________________________
   */
  public function setPref($articleBox, $articleName, $key, $val)
  {
    if ($val === 'true')
    {
      $val = true;
    }
    
    if ($val === 'false')
    {
      $val = false;
    }

    $this->load($articleBox);
    $this->prefs[$articleBox][$articleName][$key] = $val;
    
    return $this->save($articleBox);
  }
  
  /**
   * set cbArticle prefs
   * _________________________________________________________________
   */
  public function setPrefs($articleBox, $articleName, $prefs)
  {
    $this->load($articleBox);
    $this->prefs[$articleBox][$articleName] = $prefs;
    
    return $this->save($articleBox);
  }

  /**
   * reset cbArticle pref
   * _________________________________________________________________
   */
  public function resetPref($articleBox, $articleName, $key)
  {
    $this->load($articleBox);
    unset($this->prefs[$articleBox][$articleName][$key]);
    
    return $this->save($articleBox);
  }
  
  /**
   * reset cbArticle prefs
   * _________________________________________________________________
   */
  public function resetPrefs($articleBox, $articleName)
  {
    $this->load($articleBox);
    unset($this->prefs[$articleBox][$articleName]);
    
    return $this->save($articleBox);
  }
  
  /**
   * rename article
   * _________________________________________________________________
   */
  public function renameArticle($articleBox, $oldArticleName, $newArticleName)
  {
    $this->load($articleBox);
    
    if (isset($this->prefs[$articleBox][$oldArticleName]))
    {
      $prefs = $this->prefs[$articleBox][$oldArticleName];
      if (!isset($this->prefs[$articleBox][$newArticleName]))
      {
        $this->prefs[$articleBox][$newArticleName] = $prefs;
        unset($this->prefs[$articleBox][$oldArticleName]);
        return $this->save($articleBox);
      }
    }
    
    return false;
  }

  /**
   * reset ALL cbArticle prefs of box
   * _________________________________________________________________
   */
  public function resetAllPrefs($articleBox)
  {
    $this->load($articleBox);
    unset($this->prefs[$articleBox]);
    
    return $this->save($articleBox);
  }

  private function __construct() {}
  private function __clone() {}
}

?>