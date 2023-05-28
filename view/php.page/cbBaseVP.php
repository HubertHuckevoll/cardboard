<?php

namespace cb\view\page;

/**
  Base view
  There are 3 types of functions:
  1. functions that start with "draw" are PUBLIC and actually dump content down the wire ("ECHO") - API
  2. functions that start with "render" are PUBLIC and RETURN stuff - API
  2. all other functions are internal. They should be PROTECTED/PRIVATE and RETURN content.

  Also, dont't add stuff to the constructor -
  all views should have the same construction api
  so we can instantiate them programmatically
  ___________________________________________________________________
*/

class cbBaseVP
{
  public $data = array(); // model data

  public $viewHints = [
    'ep' => '',
    'mod' => '',
    'hook' => ''
  ];
  public $useEP = true;
  public $stateParams = array();

  public $linker = null;

  /**
   * Konstruktor
   * the linker can be replaced from outside
   * _________________________________________________________________
   */
  public function __construct(array $viewHints, $linker)
  {
    $this->viewHints = $viewHints;
    $this->linker = $linker;
  }

  /**
   * add Data From Array
   * _________________________________________________________________
   */
  public function addDataFromArray($data)
  {
    $this->data = array_merge($this->data, (array) $data);
  }

  /**
   * replace Data From Array
   * _________________________________________________________________
   */
  public function replaceDataFromArray($data)
  {
    $this->reset();
    $this->data = array_merge($this->data, (array) $data);
  }

  /**
   * set data key
   * _________________________________________________________________
   */
  public function setData($key, $val)
  {
    $this->data[$key] = $val;
  }

  /**
   * get data key
   * _________________________________________________________________
   */
  public function getData($key)
  {
    return $this->data[$key];
  }

  /**
   * reset data
   * _________________________________________________________________
   */
  public function reset()
  {
    $this->data = array();  // model data
  }

  /**
   * execute a draw function dynamically
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
      throw new \Exception('Unknown function call "'.$method.'"');
    }
  }

  /**
   * format date
   * _________________________________________________________________
   */
  public function fDate($timestamp)
  {
    $locale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
    return $formatter->format($timestamp);
  }

  /**
   * Success Message
   * _________________________________________________________________
   */
  public function successMsg($msg, $style = true)
  {
    return $this->msgBox($msg, 'success.png', $style);
  }

  /**
   * Error Message
   * _________________________________________________________________
   */
  public function errorMsg($msg, $style = true)
  {
    return $this->msgBox($msg, 'error.png', $style);
  }

  /**
   * Msg Box
   * FIXME - this shouldn't have inline CSS
   * is it even right to have this here?
   * _______________________________________________________________
   */
  protected function msgBox($msg, $msgImg = 'info.png', $style = true)
  {
    if ($msg instanceof \Exception)
    {
      $msg = $msg->getMessage().'<br><br><strong>Trace</strong><br>'.str_replace("\n", '<br>', $msg->getTraceAsString());
    }

    $caption = pathinfo($msgImg, PATHINFO_FILENAME);
    $bc = '#bbb';
    if ($caption == 'success')
    {
      $bc = '#beb';
    }
    elseif ($caption == 'error')
    {
      $bc = '#ebb';
    }
    $caption = ucfirst($caption);

    if ($style == true)
    {
      $erg = '<div style="border: 1px solid '.$bc.';
                          border-radius: 5px;
                          -moz-border-radius: 5px;
                          -webkit-border-radius: 5px;
                          background-color: #fafafa;
                          padding: 3px;
                          margin-bottom: 5px;
                          min-height: 36px;
                          z-index: 999;">
                <img src="'.CB_IMG_ROOT.$msgImg.'"
                     style="display: block;
                            float: left;
                            margin-right: 10px;"></img>
                <div style="margin-left: 50px;">'.$msg.'</div>
              </div>';
    }
    else
    {
      $erg = '<div class="msgBox">
                <img src="'.CB_IMG_ROOT.$msgImg.'"></img>
                <div class="msgBoxCaption">'.$caption.'</div>
                <div class="msgBoxMsg">'.$msg.'</div>
              </div>';
    }
    return $erg;
  }

}

?>