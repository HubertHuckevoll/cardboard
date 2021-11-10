<?php
  /**
   * non-cardboard specific helpers
   * _________________________________________________________________
   */
  require_once('helpers.php');
  require_once('logger.php');

  /**
   * cardboard specific helpers
   * _________________________________________________________________
   */
  require_once('globals.php');

  /**
   * Auto loader
   * ________________________________________________________________
   */
  spl_autoload_register(function($className)
  {
    $fname = null;

    $ct = substr($className, -1);

    switch($ct)
    {
      case 'F':
      case 'P':
        $parts = explode('\\', $className);
        $type = $parts[2];
        $class = $parts[3];

        $fname = CB_ROOT.'view'.DIRECTORY_SEPARATOR.'php.'.$type.DIRECTORY_SEPARATOR.$class.'.php';
      break;

      case 'M':
        $fname = CB_ROOT.'model'.DIRECTORY_SEPARATOR.$className.'.php';
      break;

      case 'C':
        $fname = CB_ROOT.'controller'.DIRECTORY_SEPARATOR.$className.'.php';
      break;
    }

    if ($fname !== null)
    {
      $fname = getPathFS($fname);
      if (file_exists($fname))
      {
        require_once($fname);
      }
    }
  });

?>