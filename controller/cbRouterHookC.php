<?php

/**
 * cbRouterC
 * ___________________________________________________________________
 */
class cbRouterHookC extends cbBaseC
{
  public $ep = '';
  public $hook = '';

  public $pageCntrl = null;

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($pageCntrl, $requestM = null)
  {
    parent::__construct($requestM);

    $this->ep = ($ep == '') ? basename($_SERVER["SCRIPT_NAME"]) : $ep;
    $this->hook = $this->requestM->getReqVar('hook');

    // check hook
    if ($this->hook === false)
    {
      $this->hook = 'index';
    }

    $this->pageCntrl = $pageCntrl;
    $this->pageCntrl->ep = $this->ep;
    $this->pageCntrl->hook = $this->hook;
  }

  /**
   * run!
   * _________________________________________________________________
   */
  public function run()
  {
    try
    {
	    $this->pageCntrl->cbBeforeHookCall();
      $this->pageCntrl->exec($this->hook);
    }
    catch(Exception $e)
    {
      die($e->getMessage());
    }
  }
}

?>
