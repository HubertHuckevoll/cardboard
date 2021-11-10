<?php

class cbContactM
{
  public $articleBox = '';
  public $articleName = '';
  public $toEmail = '';

  /**
   * Send Contact - Email
   * ___________________________________________________________________________
   */
  function __construct($articleBox, $articleName, $toEmail)
  {
    $this->articleBox = $articleBox;
    $this->articleName = $articleName;
    
    $this->toEmail = $toEmail;
  }

  /**
   * Send Contact - Email
   * ___________________________________________________________________________
   */
  function sendEmail($senderMail, $message, $captcha, $captchaCookie, $isBot)
  {
    if (
           (strlen($senderMail) == 0) || (strlen($message) == 0)
        || ($captcha != $captchaCookie) || ($captchaCookie == '') || ($captcha == '')
        || (strlen($isBot) > 0)
        || (substr(strtolower($message), 0, 7) == 'http://')
        || (substr(strtolower($message), 0, 8) == 'https://')
        || (substr(strtolower($message), 0, 6) == 'ftp://')
        || (checkVar($senderMail, 'email') == false)
       )
    {
      throw new Exception(__CLASS__.': Sie haben keine (gültige) E-Mail Adresse, keine Nachricht oder einen falschen Sicherheitscode angegeben.');
    }
    else
    {
      $msg  = 'Von der Website...'."\r\n\r\n";
      $msg .= 'e-mail des Senders: '.$senderMail."\r\n\r\n";
      $msg .= 'Nachricht des Senders: '."\r\n".$message."\r\n\r\n";
      if (!sendEmail($senderMail, $this->toEmail, 'Kontaktaufnahme', $msg))
      {
        throw new Exception(__CLASS__.': Interner Fehler: Nachricht konnte nicht gesendet werden (Zieladresse konfiguriert?)');
      }
    }
  }

}

?>
