<?php

class cbCommentsC extends cbBaseC
{
  public $articleBox = '';
  public $articleName = '';
  public $articlePage = '';
  public $hook = '';

  public $rInfo = array();

  // set by __construct and static
  public $cbc = null;
  public $commentsDisabled = false;

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($boxA, $articleName, $hook, $ep = '', $requestM = null)
  {
    parent::__construct($requestM);

    $this->articleBox = $boxA['box'];
    $this->articleName = $articleName;
    $this->articlePage = ($this->requestM->getReqVar('articlePage') !== false) ? $this->requestM->getReqVar('articlePage') : 0;
    $this->hook = $hook; // we need this only for the link in the e-mail when a comment has been added

    $this->rInfo['meta']['articleBox'] = $this->articleBox;
    $this->rInfo['meta']['articleName'] = $this->articleName;
    $this->rInfo['meta']['articlePage'] = $this->articlePage;

    try
    {
      // Prefs
      $prefs = cbArticlePrefsM::getInstance();
      $this->commentsDisabled = $prefs->getPref($this->articleBox, $this->articleName, 'commentsDisabled');

      // Comments
      $this->cbc = new cbCommentsM($this->articleBox);
      if (isset($boxA['email']))
      {
        $this->cbc->toEmail = $boxA['email'];
      }
      $this->cbc->load($this->articleName);
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
    if (!$this->commentsDisabled)
    {
      $this->rInfo['model']['comments'] = $this->cbc->getComments($this->articleName);
      $this->rInfo['meta']['viewOp'] = 'renderComments';
    }
    else
    {
      $this->rInfo['model']['comments'] = array();
      $this->rInfo['meta']['viewOp'] = 'renderCommentsDisabled';
    }

    return $this->rInfo;
  }

  /**
   * Show comments, ajax version
   * _________________________________________________________________
   */
  public function showCommentsAjax()
  {
    $this->rInfo['model']['comments'] = $this->cbc->getComments($this->articleName);
    $this->rInfo['meta']['viewOp'] = 'renderCommentsAjax';

    return $this->rInfo;
  }

  /**
   * Show comments form
   * _________________________________________________________________
   */
  public function showCommentsForm()
  {
    $this->rInfo['model']['comments'] = array();
    $this->rInfo['meta']['viewOp'] = 'renderCommentsForm';

    return $this->rInfo;
  }

  /**
   * Show Comments Form, ajax version
   * _________________________________________________________________
   */
  public function showCommentsFormAjax()
  {
    $this->rInfo['model']['comments'] = array();
    $this->rInfo['meta']['viewOp'] = 'renderCommentsFormAjax';

    return $this->rInfo;
  }

  /**
   * Show Comments - Submit
   * _________________________________________________________________
   */
  public function showCommentsSubmit()
  {
    $succ = $this->commentsSubmitEntry();
    if ($succ)
    {
      $this->rInfo['model']['comments'] = $this->cbc->getComments($this->articleName);
      $this->rInfo['meta']['viewOp'] = 'renderComments';
    }
    else
    {
      $this->rInfo['meta']['viewOp'] = 'renderCommentsForm';
    }

    return $this->rInfo;
  }

  /**
   * Show Comments Submit - Ajax
   * _________________________________________________________________
   */
  public function showCommentsSubmitAjax()
  {
    $succ = $this->commentsSubmitEntry();
    if ($succ)
    {
      $this->rInfo['model']['comments'] = $this->cbc->getComments($this->articleName);
      $this->rInfo['meta']['viewOp'] = 'renderCommentsAjax';
    }
    else
    {
      $this->rInfo['meta']['viewOp'] = 'renderCommentsFormAjax';
    }

    return $this->rInfo;
  }

  /**
   * Comments submit entry
   * _________________________________________________________________
   */
  public function commentsSubmitEntry()
  {
    $erg = false;
    $articleLink = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?hook='.$this->hook.'&op=show&articleBox='.$this->articleBox.'&article='.$this->articleName.'&articlePage='.$this->articlePage;

    $isBot = $this->requestM->getReqVar('email');
    $message = $this->requestM->getReqVar('message');
    $sender = $this->requestM->getReqVar('sender');
    $captcha = $this->requestM->getReqVar('captcha');

    $captchaCookie = $_COOKIE['captchaCode'];

    try
    {
      $this->cbc->commentsSubmitEntry($this->articleName, $message, $sender, $captcha, $captchaCookie, $isBot, $articleLink);
      $this->rInfo['meta']['isSuccess'] = true;
      $this->rInfo['meta']['statMsg'] = 'Vielen Dank. Ihr Eintrag wurde hinzugefügt.';
      $erg = true;
    }
    catch (Exception $e)
    {
      $this->rInfo['meta']['isSuccess'] = false;
      $this->rInfo['meta']['statMsg'] = $e->getMessage();
      $this->rInfo['meta']['sender'] = $sender;
      $this->rInfo['meta']['message'] = $message;
      $erg = false;
    }

    return $erg;
  }
}

?>
