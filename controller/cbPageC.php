<?php

/**
 * cbPageC == basically our front controller.
 * calls whatever the hook says
 * ___________________________________________________________________
 */
class cbPageC extends cbBaseC
{
  public $view = null;
  public $ui = 'generic';

  // linker is used for rewritting standard queries to routes
  public $linker = null;

  public $projectRootURL = ''; // Base - important for URL rewriting

  public $boxes = null;

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($linker = null, $requestM = null)
  {
    parent::__construct($requestM);

    $this->linker = $linker;

    // Read in boxes.json
    try
    {
      $this->boxes = new cbBoxesM();
      $this->boxes->load();
    }
    catch(Exception $e)
    {
      throw $e;
    }
  }

  /**
   * init a view
   * die on error: all is lost if we can't create a view.
   *
   * uiViewName is either just a view name (like "indexV")
   * or a namespaced ui and view name: "view\generic\indexV"
   * _________________________________________________________________
   */
  public function initView($uiViewName, $viewHints)
  {
    // determine view name
    try
    {
      if (strpos($uiViewName, "\\") !== false)
      {
        $vName = $uiViewName;
      }
      else
      {
        $vName = "view\\".$this->ui."\\page\\".$uiViewName;
      }

      // create view
      $this->view = new $vName($viewHints, $this->linker);

      // Some default properties
      define('PROJECT_ROOT_URL', getProjectRootURL().DIRECTORY_SEPARATOR); // Base - important for URL rewriting
      define('PROJECT_CSS_URL', PROJECT_ROOT_URL.'view'.DIRECTORY_SEPARATOR.$this->ui.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR);
      define('PROJECT_JS_URL', PROJECT_ROOT_URL.'view'.DIRECTORY_SEPARATOR.$this->ui.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR);
      define('PROJECT_IMG_URL', PROJECT_ROOT_URL.'view'.DIRECTORY_SEPARATOR.$this->ui.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR);
    }
    catch(Exception $e)
    {
      die($e->getMessage());
    }
  }

  /**
   * called before hook
   * _________________________________________________________________
   */
  public function cbBeforeHookCall()
  {
    return true;
  }

  /**
   * Index - is called when no hook is provided
   * _________________________________________________________________
   */
  public function index()
  {
    return true;
  }

}

?>
