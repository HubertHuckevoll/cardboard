<?php

class cbArticleCommentsC extends cbPageC
{
  public $articleBox = '';
  public $articleName = '';
  public $articlePage = '';

  // set by __construct and static
  public $cba = null;
  public $cbc = null;
  public $commentsDisabled = false;

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($articleBox, $articleName, $linker, $requestM)
  {
    parent::__construct($linker, $requestM);

    $this->articleBox = $articleBox;
    $this->articleName = $articleName;
    $this->articlePage = ($this->requestM->getReqVar('articlePage') !== false) ? $this->requestM->getReqVar('articlePage') : 0;

    try
    {
      // Prefs
      $prefs = cbArticlePrefsM::getInstance();
      $this->commentsDisabled = $prefs->getPref($this->articleBox, $this->articleName, 'commentsDisabled');

      // Article
      $this->cba = new cbArticleM($this->articleBox, $this->articleName);
      $this->cba->load();

      // Comments
      $this->cbc = new cbCommentsM($this->articleBox);
      if (isset($this->boxes->getBoxByName($this->articleBox)['email']))
      {
        $this->cbc->toEmail = $this->boxes->getBoxByName($this->articleBox)['email'];
      }
      $this->cbc->load($this->articleName);
    }
    catch(Exception $e)
    {
      throw $e;
    }
  }

  /**
   * Helper function
   * ________________________________________________________________
   */
  protected function article2View()
  {
    $this->view->setData('boxNameAlias', $this->boxes->getBoxByName($this->articleBox)['alias']);
    $this->view->setData('articleBox', $this->articleBox);
    $this->view->setData('articleName', $this->articleName);
    $this->view->setData('articlePage', $this->articlePage);
    $this->view->setData('pageTitle', $this->cba->getArticleHeadline());
    $this->view->setData('metaDescription', $this->cba->getArticlePageAbstract($this->articlePage));
    $this->view->addDataFromArray($this->cba->getArticle());
  }

  /**
   * main Hook
   * _________________________________________________________________
   */
  public function index()
  {
    try
    {
      $this->article2View();

      if (!$this->commentsDisabled)
      {
        $this->view->setData('comments', $this->cbc->getComments($this->articleName));
        $this->view->setData('viewOp', 'renderComments');
      }
      else
      {
        $this->view->setData('comments', []);
        $this->view->setData('viewOp', 'renderCommentsDisabled');
      }

      $this->view->drawPage();
    }
    catch (Exception $e)
    {
      $this->view->drawPage($e->getMessage());
    }
  }

  /**
   * Show comments, ajax version
   * _________________________________________________________________
   */
  public function showCommentsAjax()
  {
    $this->article2View();

    $this->view->setData('comments', $this->cbc->getComments($this->articleName));
    $this->view->setData('viewOp', 'renderCommentsAjax');

    $this->view->drawAjax();
  }

  /**
   * Show comments form
   * _________________________________________________________________
   */
  public function showCommentsForm()
  {
    $this->article2View();

    $this->view->setData('comments', []);
    $this->view->setData('viewOp', 'renderCommentsForm');

    $this->view->drawPage();
  }

  /**
   * Show Comments Form, ajax version
   * _________________________________________________________________
   */
  public function showCommentsFormAjax()
  {
    $this->article2View();

    $this->view->setData('comments', []);
    $this->view->setData('viewOp', 'renderCommentsFormAjax');

    $this->view->drawAjax();
  }

  /**
   * Show Comments - Submit
   * _________________________________________________________________
   */
  public function showCommentsSubmit()
  {
    $this->article2View();

    $succ = $this->commentsSubmitEntry();
    if ($succ)
    {
      $this->view->setData('comments', $this->cbc->getComments($this->articleName));
      $this->view->setData('viewOp', 'renderComments');
    }
    else
    {
      $this->view->setData('viewOp', 'renderCommentsForm');
    }

    $this->view->drawPage();
  }

  /**
   * Show Comments Submit - Ajax
   * _________________________________________________________________
   */
  public function showCommentsSubmitAjax()
  {
    $this->article2View();

    $succ = $this->commentsSubmitEntry();
    if ($succ)
    {
      $this->view->setData('comments', $this->cbc->getComments($this->articleName));
      $this->view->setData('viewOp', 'renderCommentsAjax');
    }
    else
    {
      $this->view->setData('viewOp', 'renderCommentsFormAjax');
    }

    $this->view->drawAjax();
  }

  /**
   * Comments submit entry
   * _________________________________________________________________
   */
  public function commentsSubmitEntry()
  {
    $erg = false;
    $articleLink = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?mod='.$this->view->viewHints['mod'].'hook=index&articleBox='.$this->articleBox.'&article='.$this->articleName.'&articlePage='.$this->articlePage;

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
