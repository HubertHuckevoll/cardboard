<?php
namespace cb\view\fragment;

class cbCommentsVF extends cbBaseVF
{
  /**
   * draw comments
   * _________________________________________________________________
   */
  public function renderComments()
  {
    $erg = '';
    $erg .= $this->commentsIntro();
    $erg .= $this->comments();
    $erg .= $this->commentsOutro();
    return $erg;
  }

  /**
   * draw comments
   * _________________________________________________________________
   */
  public function renderCommentsAjax()
  {
    return $this->comments();
  }

  /**
   * draw comments form
   * _________________________________________________________________
   */
  public function renderCommentsForm()
  {
    $erg = '';
    $erg .= $this->commentsIntro();
    $erg .= $this->commentsAddForm();
    $erg .= $this->commentsOutro();
    return $erg;
  }

  /**
   * draw comments form ajax
   * _________________________________________________________________
   */
  public function renderCommentsFormAjax()
  {
    return $this->commentsAddForm();
  }

  /**
   * draw comments disabled
   * _________________________________________________________________
   */
  public function renderCommentsDisabled()
  {
    return $this->commentsDisabled();
  }

  /**
   * Comments without UI elements
   * _________________________________________________________________
   */
  protected function comments()
  {
    $erg = '';

    if (isset($this->data['isSuccess']))
    {
      if ($this->data['isSuccess'])
      {
        $erg .= $this->successMsg($this->data['statMsg']);
      }
      else
      {
        $erg .= $this->errorMsg($this->data['statMsg']);
      }
    }

    $i = 0;
    $str = '';
    $strs = '';

    if (($this->data['comments'] !== false) && (count($this->data['comments']) > 0))
    {
      foreach ($this->data['comments'] as $comment)
      {
        $sender = $comment['sender'];
        $time = (int) $comment['time'];

        $msg = $comment['message'];
        $msg = preg_replace('/(http:\/\/.*)(\s|$)/', '<a href="\\1" target="_blank" title="\\1">\\1</a>', $msg);
        $msg = str_replace("\r", '', $msg);
        $msg = str_replace("\n", '<br />', $msg);

        $str = '<div class="comment">';
          $str .= '<div class="commentSenderTime">';
            $str .= $sender.' schrieb am '.date("d.m.y, H:i", $time).' Uhr';
          $str .= '</div>';
          $str .= '<div class="commentMsg">'.$msg.'</div>';
          if ($comment['adminComment'] != '')
          {
            $str .= '<div class="adminComment">';
              $str .= '<div class="adminCommentHead"><strong>'.$_SERVER['SERVER_NAME'].'</strong> merkt an:</div>';
              $str .= '<div class="adminCommentMsg">'.$comment['adminComment'].'</div>';
            $str .= '</div>';
          }
        $str .= '</div>';

        $strs = $str.$strs;
        $i++;
      }

      $erg .= $strs;
    }
    else
    {
      $erg .= '<div class="comment">Keine Eintr&auml;ge.</div>';
    }

    return $erg;
  }

  /**
   * Add Comments - Form
   * _________________________________________________________________
   */
  protected function commentsAddForm()
  {
    $erg = '';

    $actionHref     = $this->ep.'?hook='.$this->hook.'&amp;op=showCommentsSubmit&amp;articleBox='.$this->data['articleBox'].'&amp;article='.$this->data['articleName'].'&amp;articlePage='.$this->data['articlePage'];
    $actionHrefAjax = $this->ep.'?hook='.$this->hook.'&amp;op=showCommentsSubmitAjax&amp;articleBox='.$this->data['articleBox'].'&amp;article='.$this->data['articleName'].'&amp;articlePage='.$this->data['articlePage'];

    if (isset($this->data['isSuccess']))
    {
      if ($this->data['isSuccess']) {
        $erg .= $this->successMsg($this->data['statMsg']);
      } else {
        $erg .= $this->errorMsg($this->data['statMsg']);
      }
    }

    // email is never required and therefore invisible! => SPAM - Protection
    $erg .= '<form id="commentsForm" enctype="application/x-www-form-urlencoded" action="'.$actionHref.'" data-ajax-action="'.$actionHrefAjax.'" method="post">'.
              '<div><strong>Hinweis:</strong>&nbsp;Diese Funktion verwendet "Cookies".</div>'.
              '<div class="veriBox">'.
                 '<div class="commentCaption">Ihre E-Mail:</div>'.
                 '<input type="text" name="email" id="email" value="" />'.
              '</div>'.
              '<div>'.
                 '<div class="commentCaption">Ihr Name:</div>'.
                 '<input type="text" value="'.$this->data['sender'].'" name="sender" id="sender" />'.
              '</div>'.
              '<div>'.
                 '<div class="commentCaption">Ihr Kommentar:</div>'.
                 '<textarea rows="15" name="message" id="message">'.$this->data['message'].'</textarea>'.
              '</div>'.
              '<div>
                 Bitte geben Sie den Code so ein, wie Sie ihn auf dem Bild erkennen.<br />
                 <img src="'.CB_ROOT.'captcha.php?t='.(microtime()*100).'" alt="Captcha"></img>&nbsp;
                 <input type="text" name="captcha" id="captcha" value="" />'.
              '</div>'.
              '<button type="submit">Hinzuf&uuml;gen</button>'.
            '</form>';

    return $erg;
  }

  /**
   * UI for Comments, "Frame"
   * _________________________________________________________________
   */
  protected function commentsIntro()
  {
    $erg = '';

    $showCommentsHref     = $this->ep.'?hook='.$this->hook.'&amp;op=show&amp;articleBox='.$this->data['articleBox'].'&amp;article='.$this->data['articleName'].'&amp;articlePage='.$this->data['articlePage'];
    $showCommentsHrefAjax = $this->ep.'?hook='.$this->hook.'&amp;op=showCommentsAjax&amp;articleBox='.$this->data['articleBox'].'&amp;article='.$this->data['articleName'].'&amp;articlePage='.$this->data['articlePage'];

    $showCommentsFormHref     = $this->ep.'?hook='.$this->hook.'&amp;op=showCommentsForm&amp;articleBox='.$this->data['articleBox'].'&amp;article='.$this->data['articleName'].'&amp;articlePage='.$this->data['articlePage'];
    $showCommentsFormHrefAjax = $this->ep.'?hook='.$this->hook.'&amp;op=showCommentsFormAjax&amp;articleBox='.$this->data['articleBox'].'&amp;article='.$this->data['articleName'].'&amp;articlePage='.$this->data['articlePage'];

    $erg .= '<div id="commentsBox">'.
            '<div class="commentsHeadline">einträge</div>'.
            '<div id="commentsControls">'.
              '<a id="commentsShow" href="'.$showCommentsHref.'" data-ajax-href="'.$showCommentsHrefAjax.'">Anzeigen</a>&nbsp;'.
              '<a id="commentsAdd"  href="'.$showCommentsFormHref.'" data-ajax-href="'.$showCommentsFormHrefAjax.'">Eintragen</a>'.
            '</div>'.
            '<hr />'.
            '<div id="commentsContentBox">';

    return $erg;
  }

  /**
   * UI for Comments, "Frame"
   * _________________________________________________________________
   */
  protected function commentsOutro()
  {
    return '</div></div>';
  }

  /**
   * Kommentare disabled
   * _________________________________________________________________
   */
  protected function commentsDisabled()
  {
    $erg  = '<div id="commentsBox">'.
              '<div id="commentsContentBox">'.
                '<div class="comment">Die Kommentare sind für diesen Artikel (vorübergehend) deaktiviert worden.</div>'.
              '</div>'.
            '</div>';

    return $erg;
  }
}

?>