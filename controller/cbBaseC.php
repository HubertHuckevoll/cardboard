<?php

class cbBaseC
{
  public $requestM = null;

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($requestM = null)
  {
    $this->requestM = ($requestM !== null) ? $requestM : new cbRequestM();
  }

  /**
   * execute a controller function dynamically
   * _________________________________________________________________
   */
  public function exec($method)
  {
    if (method_exists($this, $method))
    {
      return $this->$method();
    }
    else
    {
      throw new Exception('Unknown function call "'.$method.'" for object "'.get_class($this).'".');
    }
  }
}