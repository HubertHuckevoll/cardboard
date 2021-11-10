<?php

class cbContactC extends cbBaseC
{
  public $cbo = null;
  public $articleBox = null;
  public $articleName = null;
	public $rInfo = array();

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($boxA, $articleName, $toEmail, $requestM = null)
  {
    parent::__construct($requestM);

    $this->articleBox = $boxA['box'];
    $this->articleName = $articleName;

    $this->rInfo['meta']['articleBox'] = $this->articleBox;
    $this->rInfo['meta']['articleName'] = $this->articleName;
	  $this->rInfo['meta']['articlePage'] = ($this->requestM->getReqVar('articlePage') !== false) ? $this->requestM->getReqVar('articlePage') : 0;

		try
		{
      if (!checkVar($toEmail, 'email'))
      {
        throw new Exception(__CLASS__.': Die übergebene E-Mail-Adresse ist nicht valide.');
      }
 			$this->cbo = new cbContactM($this->articleBox, $this->articleName, $toEmail);
	  }
    catch(Exception $e)
    {
			throw $e;
    }
  }

  /**
   * Show
   * _________________________________________________________________
   */
  public function show()
  {
    $this->rInfo['model']['senderMail'] = '';
    $this->rInfo['model']['message'] = '';

    return $this->rInfo;
  }

  /**
   * Submit form
   * _________________________________________________________________
   */
  public function showContactFormSubmitted()
  {
    $isBot = $this->requestM->getReqVar('email');
    $senderMail = $this->requestM->getReqVar('senderMail');
    $message = $this->requestM->getReqVar('message');
    $captcha = $this->requestM->getReqVar('captcha');
    $captchaCookie = $_COOKIE['captchaCode'];

    try
    {
      $this->cbo->sendEmail($senderMail, $message, $captcha, $captchaCookie, $isBot);
      $this->rInfo['model']['isSuccess'] = true;
      $this->rInfo['model']['statMsg'] = 'Ihre e-mail wurde abgeschickt. Danke, dass Sie mit uns in Kontakt getreten sind.';
    }
    catch(Exception $e)
    {
      $this->rInfo['model']['isSuccess'] = false;
      $this->rInfo['model']['statMsg'] = $e->getMessage();
      $this->rInfo['model']['senderMail'] = $senderMail;
      $this->rInfo['model']['message'] = $message;
    }

    return $this->rInfo;
  }

}

?>
