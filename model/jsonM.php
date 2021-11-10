<?php

class jsonM
{
  /**
   * load json
   * _________________________________________________________________
   */
  public static function load($file)
  {
    if (($json = @file_get_contents($file)) !== false)
    {
      $dJson = json_decode($json, true);
      if (json_last_error() == JSON_ERROR_NONE)
      {
        return $dJson;
      }
      else
      {
        throw new Exception(__CLASS__.': '.json_last_error_msg());
      }
    }
    else
    {
      throw new Exception(__CLASS__.': Konnte JSON-Datei nicht einlesen ('.$file.')');
    }
  }

  /**
   * save json
   * _________________________________________________________________
   */
  public static function save($file, $data)
  {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    
    if (json_last_error() == JSON_ERROR_UTF8)
    {
      $data = self::makeUtf8($data);
      $json = json_encode($data, JSON_PRETTY_PRINT);
    }

    if (json_last_error() === JSON_ERROR_NONE)
    {
      $pathParts = pathinfo($file);
      $dir = $pathParts['dirname'];
      if (!file_exists($dir))
      {
        if (mkdir($dir, 0777, true) == false)
        {
          throw new Exception(__CLASS__.': Konnte Pfad für die zu schreibende JSON-Datei nicht erstellen ('.$file.')');
        }
      }
      
      if (@file_put_contents($file, $json) !== false)
      {
        return true;
      }
      else
      {
        throw new Exception(__CLASS__.': Konnte JSON-Datei nicht schreiben ('.$file.')');
      }
    }
    else
    {
      throw new Exception(__CLASS__.': Konnte JSON nicht erzeugen ('.$file.'), '.json_last_error_msg());
    }
  }

  /**
   * UTF8 encode
   * _________________________________________________________________
   */
  public static function makeUtf8($var)
  {
    if (is_array($var))
    {
      foreach ($var as $key => $value)
      {
        $var[$key] = self::makeUtf8($value);
      }
    }
    elseif (is_string($var))
    {
      return mb_convert_encoding($var, "UTF-8", "UTF-8");
    }
    return $var;
  }
}

?>
