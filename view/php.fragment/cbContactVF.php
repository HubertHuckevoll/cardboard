<?php
namespace cb\view\fragment;

class cbContactVF extends cbBaseVF
{
  /**
   * Draw / API
   * _________________________________________________________________
   */
  public function render()
  {
    $href = $this->ep.'?hook='.$this->hook.'&amp;op=showContactFormSubmitted&amp;articleBox='.$this->data['articleBox'].'&amp;article='.$this->data['articleName'].'&amp;articlePage='.$this->data['articlePage'];
    $status = '';

    if (isset($this->data['isSuccess']))
    {
      if ($this->data['isSuccess'])
      {
        $status = $this->successMsg($this->data['statMsg']);
      }
      else
      {
        $status = $this->errorMsg($this->data['statMsg']);
      }
    }

    // email is never required and therefore invisible! => SPAM - Protection
    $erg = '<div id="contactBox">'.
              $status.
              '<div id="contactFormBox">'.
                '<div><strong>Hinweis:</strong>&nbsp;Diese Funktion verwendet "Cookies".</div>'.
                '<form enctype="application/x-www-form-urlencoded" action="'.$href.'" method="post">'.
                  '<div class="veriBox">'.
                   '<div class="commentCaption">E-Mail:</div>'.
                   '<input type="text" name="email" id="email" value="" />'.
                  '</div>'.
                  '<div>'.
                    'Ihre E-Mail Adresse:<br />'.
                    '<input type="text" name="senderMail" id="senderMail" value="'.htmlentities($this->data['senderMail']).'" />'.
                  '</div>'.
                  '<div>'.
                    'Nachricht:<br />'.
                    '<textarea name="message" id="message" rows="10">'.htmlentities($this->data['message']).'</textarea>'.
                  '</div>'.
                  '<div>'.
                    'Bitte geben Sie den Code so ein, wie Sie ihn auf dem Bild erkennen.<br />'.
                    '<img src="'.CB_ROOT.'captcha.php?t='.(microtime(true) * 100).'" alt="Captcha"></img>&nbsp;'.
                    '<input type="text" name="captcha" id="captcha" value="" />'.
                  '</div>'.
                  '<div>'.
                    '<button type="submit">Abschicken</button>'.
                  '</div>'.
                '</form>'.
              '</div>'.
           '</div>';

    return $erg;
  }
}

?>