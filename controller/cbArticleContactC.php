<?php

class cbArticleContactC extends cbPageC
{
  public $cba = null;
  public $cbo = null;
  public $articleBox = null;
  public $articleName = null;

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($articleBox, $articleName, $linker, $requestM)
  {
    parent::__construct($linker, $requestM);

    $this->articleBox = $articleBox;
    $this->articleName = $articleName;
    $toEmail = $this->boxes->getBoxByName($articleBox)['email'];

		try
		{
      if (!checkVar($toEmail, 'email'))
      {
        throw new Exception(__CLASS__.': Die übergebene E-Mail-Adresse ist nicht valide.');
      }
 			$this->cbo = new cbContactM($this->articleBox, $this->articleName, $toEmail);

       $this->cba = new cbArticleM($this->articleBox, $this->articleName);
       $this->cba->load();
	  }
    catch(Exception $e)
    {
			throw $e;
    }
  }

  /**
   * push data to view
   * ________________________________________________________________
   */
  protected function dataToView()
  {
    $this->view->setData('articleBox', $this->articleBox);
    $this->view->setData('articleName', $this->articleName);
	  $this->view->setData('articlePage', ($this->requestM->getReqVar('articlePage') !== false) ? $this->requestM->getReqVar('articlePage') : 0);
    $this->view->setData('pageTitle', $this->cba->getArticleHeadline());
    $this->view->setData('metaDescription', $this->cba->getArticlePageAbstract(0));
    $this->view->addDataFromArray($this->cba->getArticle());
    $this->view->setData('boxNameAlias', $this->boxes->getBoxByName($this->articleBox)['alias']);
  }

  /**
   * Contact Hook
   * _________________________________________________________________
   */
  public function index()
  {
    try
    {
      $this->dataToView();

      $this->view->setData('senderMail', '');
      $this->view->setData('message', '');

      $this->view->drawPage();
    }
    catch (Exception $e)
    {
      $this->view->drawPage($e->getMessage());
    }
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

    $this->dataToView();

    try
    {
      $this->cbo->sendEmail($senderMail, $message, $captcha, $captchaCookie, $isBot);
      $this->view->setData('isSuccess', true);
      $this->view->setData('statMsg', 'Ihre e-mail wurde abgeschickt. Danke, dass Sie mit uns in Kontakt getreten sind.');
    }
    catch(Exception $e)
    {
      $this->view->setData('isSuccess', false);
      $this->view->setData('statMsg', $e->getMessage());
      $this->view->setData('senderMail', $senderMail);
      $this->view->setData('message', $message);
    }

    $this->view->drawPage();
  }

}

?>
