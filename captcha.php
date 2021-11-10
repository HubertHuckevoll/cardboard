<?php

/*-----------------------------------------------------
  Captcha Class
  -----------------------------------------------------*/
  class captchaClass
  {
    protected $captchaLength = 5;

    // createCaptcha
    //------------------------------------------------------------------------------------
    function createCaptcha()
    {
      // Captcha Code
      $code = $this->createRandomCode();

      //mt_srand($this->make_seed());
      //$code = mt_rand(10000, 99999);

      // Größe des Bildes festlegen
      $font = 5;
      $schrifthoehe = imagefontheight($font);
      $schriftbreite = imagefontwidth($font) * strlen($code);
      $breite = $schriftbreite + 20;
      $hoehe = $schrifthoehe + 20;
      $beschriftungx = (($breite - $schriftbreite) / 2);
      $beschriftungy = (($hoehe - $schrifthoehe) / 2);
      $bild = imagecreate($breite, $hoehe);
      $background = imagecolorallocate($bild, 204, 204, 204);
      $rahmen_c = imagecolorallocate($bild, 0, 0, 0);

      // Arbeitsfläche leeren
      imagefilledrectangle($bild, 0, 0, $breite, $hoehe, $background);

      // Schwarzen Rahmen
      imagerectangle($bild, 0, 0, $breite-1, $hoehe-1, $rahmen_c);

      // Schrift einsetzen
      $startx = $beschriftungx;
      $starty = $beschriftungy;
      $schriftbreite = 0;
      $z = 0;
      $code = (string) $code;
      for ($i = 0; $i < strlen($code); $i++)
      {
        $starty = $beschriftungy;
        $startx = $startx + $schriftbreite;
        mt_srand((double)microtime()*10000000);
        $f = mt_rand(3, 5);
        $schriftbreite = imagefontwidth($f);

        mt_srand((double)microtime()*10000000);
        $z = mt_rand(-1, 1);
        $z = $z * $f * 1.2;
        $starty = $starty + $z;

        mt_srand((double)microtime()*10000000);
        $c = mt_rand(0, 102);
        $caption_color = imagecolorallocate($bild, $c, $c, $c);

        imagestring($bild, $f, $startx, $starty, $code[$i], $caption_color);
      }

      // Bild erzeugen
      setcookie('captchaCode', $code, 0, '/');
      header('Content-Type: image/jpeg', true);
      imagejpeg($bild);
      imagedestroy($bild);
    }

    // Seed the random number generator
    //------------------------------------------------------------------------------------
    function make_seed()
    {
      list($usec, $sec) = explode(' ', microtime());
      return (float) $sec + ((float) $usec * 100000);
    }

    // Creates a random password
    //------------------------------------------------------------------------------------
    function createRandomCode()
    {
      $chars = "abcdefghijkmnpqrstuvwxyz123456789:;!$()[]{}";
      mt_srand($this->make_seed());
      $i = 0;
      $pass = '';

      while ($i <= ($this->captchaLength-1))
      {
        $num = mt_rand() % 43; // 33
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
      }

      return $pass;
    }
  }

$captcha = new captchaClass();
$captcha->createCaptcha();

?>
