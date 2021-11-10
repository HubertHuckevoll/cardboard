<?php
  /**
   * global settings
   * _________________________________________________________________
   */
  date_default_timezone_set('Europe/Berlin');
  setlocale(LC_ALL, 'ge', 'deu', 'german', 'de-DE', 'de_DE.utf8');


  /**
   * important fixed cardboard pathes
   * starting at DOCUMENT_ROOT
   * _________________________________________________________________
   */
	define('CB_ROOT',          DIRECTORY_SEPARATOR.'cardboard'.DIRECTORY_SEPARATOR);
  define('CB_IMG_ROOT',      CB_ROOT.'view'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR);
  define('CB_JS_ROOT',       CB_ROOT.'view'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR);
  define('CB_CSS_ROOT',      CB_ROOT.'view'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR);


	/**
	 * cardboard datastore
	 * _________________________________________________________________
	 */
	define('CB_DATA_ROOT',          DIRECTORY_SEPARATOR.'cardboard.datastore'.DIRECTORY_SEPARATOR);

	// path fragments starting at BOX LEVEL in cardboard datastore
  define('CB_BUILT',              DIRECTORY_SEPARATOR.'_built'.DIRECTORY_SEPARATOR);
  define('CB_DATA_COMMENTS',      DIRECTORY_SEPARATOR.'comments'.DIRECTORY_SEPARATOR);
	define('CB_DATA_TEXT',          DIRECTORY_SEPARATOR.'text'.DIRECTORY_SEPARATOR);
	define('CB_DATA_PREFS',         DIRECTORY_SEPARATOR.'prefs'.DIRECTORY_SEPARATOR);
	define('CB_DATA_ASSETS',        DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR);


	/**
	 * KuPC
	 * _________________________________________________________________
	 */
  define('CB_KUPC_ROOT', DIRECTORY_SEPARATOR.'cardboard.kupc'.DIRECTORY_SEPARATOR);

?>
