<?php

class cbCommentsM
{
  public $articleBox = '';
  public $articleName = '';

  public $commentsDir = '';
  public $comments = array();

  public $toEmail = '';

  /**
   * Konstruktor
   * _________________________________________________________________
   */
  public function __construct($articleBox)
  {
    $this->articleBox = $articleBox;

    $this->commentsDir = getPathFS(CB_DATA_ROOT.$this->articleBox.CB_DATA_COMMENTS);
  }

  /**
   * Kommentare laden etc.
   * _________________________________________________________________
   */
  public function load($article)
  {
    $jsonObj = false;

    try
    {
      $jsonObj = jsonM::load($this->getCommentsFile($article));
      $this->comments[$article] = $jsonObj;
      return true;
    }
    catch (Exception $e)
    {
      return false;
    }
  }

  /**
   * API function
   * _________________________________________________________________
   */
  public function getComments($article)
  {
    return (array) $this->comments[$article];
  }

  /**
   * Submit
   * return values: 1 = success, -1 = fehlende Variable, -2 = Fehler beim Schreiben
   * _________________________________________________________________
   */
  public function commentsSubmitEntry($article, $message, $sender, $captcha, $captchaCookie, $isBot, $articleLink)
  {
    if (
           (strlen($sender) == 0) || (strlen($message) == 0)
        || ($captcha != $captchaCookie) || ($captchaCookie == '') || ($captcha == '')
        || (strlen($isBot) > 0)
        || (substr(strtolower($message), 0, 7) == 'http://')
        || (substr(strtolower($message), 0, 8) == 'https://')
        || (substr(strtolower($message), 0, 6) == 'ftp://')
       )
    {
      throw new Exception(__CLASS__.': Sie haben keinen Namen, keine Nachricht oder einen falschen Sicherheitscode angegeben.');
    }
    else
    {
      $newChild = array('sender'  => $sender,
                        'time'    => time(),
                        'message' => $message,
                        'adminComment' => '');

      $this->comments[$article][] = $newChild;

      $ret = $this->save($article);
      if ($ret === false)
      {
        throw new Exception(__CLASS__.': Ihr Eintrag konnte nicht geschrieben werden.');
      }
      else
      {
        if (checkVar($this->toEmail, 'email'))
        {
          $msg  = '';
          $msg .= 'Absender: '.$sender."\r\n\r\n";
          $msg .= 'Link: '."\r\n".$articleLink."\r\n\r\n";
          $msg .= 'Kommentar: '."\r\n".$message."\r\n\r\n";
          sendEmail($this->toEmail, $this->toEmail, 'Neuer Kommentar auf der Webseite', $msg);
        }
      }
    }
  }

  /**
   * Admin-Kommentar aktualisieren
   * _________________________________________________________________
   */
  public function commentsUpdateAdminComment($article, $which, $text)
  {
    if (count($this->comments[$article]) > 0)
    {
      $this->comments[$article][(int)$which]['adminComment'] = $text;

      return $this->save($article);
    }
    return false; // failure
  }

  /**
   * Delete
   * _________________________________________________________________
   */
  public function commentsDeleteEntry($article, $which)
  {
    if (count($this->comments[$article]) > 0)
    {
      array_splice($this->comments[$article], (int)$which, 1);

      return $this->save($article);
    }
    return false; // failure
  }

  /**
   * save
   * _________________________________________________________________
   */
  public function save($article)
  {
    if (count($this->comments[$article]) > 0)
    {
      try
      {
        return jsonM::save($this->getCommentsFile($article), $this->comments[$article]);
      }
      catch (Exception $e)
      {
        throw $e;
      }
    }
    return false;
  }

  /**
   * set Email
   * _________________________________________________________________
   */
  public function setEmail($toEmail)
  {
    $this->toEmail = $toEmail;
  }

  /**
   * get Email
   * _________________________________________________________________
   */
  public function getEmail()
  {
    return $this->toEmail;
  }

  /**
   * rename
   * _________________________________________________________________
   */
  public function renameArticle($article, $newname)
  {
    if (file_exists($this->getCommentsFile($article)))
    {
      if (@rename($this->getCommentsFile($article), $this->getCommentsFile($newname) === false))
      {
        throw new Exception(__CLASS__.': Konnte Kommentardatei für Artikel nicht umbenennen.');
      }
    }
  }

  /**
   * delete
   * _________________________________________________________________
   */
  public function deleteArticle($article)
  {
    if (isset($this->comments[$article]))
    {
      unset($this->comments[$article]);
    }

    if (file_exists($this->getCommentsFile($article)))
    {
      if (@unlink($this->getCommentsFile($article)) === false)
      {
        throw new Exception(__CLASS__.': Konnte Kommentardatei für Artikel nicht löschen.');
      }
    }
  }

  /**
   * determine name of the current comments file
   * __________________________________________________________________
   */
  public function getCommentsFile($article)
  {
    return $this->commentsDir.DIRECTORY_SEPARATOR.$article.'.json';
  }

}

?>
