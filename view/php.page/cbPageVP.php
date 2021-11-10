<?php
namespace cb\view\page;

class cbPageVP extends \cb\view\fragment\cbBaseVF
{
  /**
   * Konstruktor
   * ________________________________________________________________
   */
	public function __construct($ep = '', $hook, $linker = null)
	{
		parent::__construct($ep, $hook, $linker);
	}

  /**
   * head content
   * _________________________________________________________________
   */
  protected function additionalHeadData()
  {
    return ''; // overwrite me
  }

  /**
   * main content
   * _________________________________________________________________
   */
  protected function mainContent()
  {
    return ''; // overwrite me
  }

  /**
   * additional content
   * _________________________________________________________________
   */
  protected function additionalContent()
  {
    return '';
  }

  /**
   * output the whole page - sample code
   * FIXME - put this in the not yet existing demo project
   * _________________________________________________________________
   */
  public function drawPage($errMsg = '')
  {
    $erg .= '<!DOCTYPE html>
             <html>
               <head>
                 <base href="'.PROJECT_ROOT_URL.'"></base>
                 <title>'.$this->data['pageTitle'].' - '.$this->data['siteName'].'</title>

                 <link rel="shortcut icon" href="favicon.ico" />
                 <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />

                 <meta name="robots" content="index,follow" />
                 <meta http-equiv="expires" content="0" />
                 <meta name="revisit-after" content="7 days" />
                 <meta name="keywords" content="'.$this->data['metaKeywords'].'" />
                 <meta name="description" content="'.$this->data['metaDescription'].'" />

                 <link rel="stylesheet" type="text/css" href="'.CB_CSS_ROOT.'cbBox.css" />
                 <link rel="stylesheet" type="text/css" href="'.CB_CSS_ROOT.'cbArticle.css" />
                 <link rel="stylesheet" type="text/css" href="'.CB_CSS_ROOT.'cardboardSearch.css" />

                 <link rel="stylesheet" type="text/css" href="'.PROJECT_CSS_URL.'main.css" title="CSS" media="screen" />
                 <link rel="stylesheet" media="screen and (max-width: 600px)" href="'.PROJECT_CSS_URL.'phone.css" />
                 <link rel="stylesheet" media="screen and (max-width: 480px)" href="'.PROJECT_CSS_URL.'phone.css" />'.
                 $this->additionalHeadData().
              '</head>
               <body>
                 <div id="header"></div>
                 <div id="content">'.$this->mainContent().'</div>
                 <div id="additionalContent">'.$this->additionalContent().'</div>
                 <div id="footer"></div>
               </body>
             </html>';

    echo $erg;
  }

  /**
   * output an error
   * _________________________________________________________________
   */
  public function drawErrorPage($errStr)
  {
    $erg .= '<!DOCTYPE HTML>
             <html>
               <head>
                 <title>'.$this->data['pageTitle'].'</title>
                 <link rel="shortcut icon" href="favicon.ico" />
                 <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
                 <meta name="robots" content="no-follow" />
                 <meta http-equiv="expires" content="1" />
                 <meta name="revisit-after" content="365 days" />
                 <meta name="keywords" content="error, page" />
                 <meta name="description" content="Error Page" />
                 <style type="text/css">
                   body {
                     font-family: "Verdana";
                     padding: 0 100px 0 100px;
                   }

                   h1 {
                     color: #bbb;
                     border-bottom: 1px solid #bbb;
                     margin-bottom: 30px;
                   }
                 </style>
               </head>
               <body>
                 <h1>Fehler</h1>
                 <div id="content">'.$errStr.'</div>
               </body>
             </html>';

    echo $erg;
  }

  /**
   * output fragment by Ajax - overwrite
   * _________________________________________________________________
   */
  public function drawAjax()
  {
    echo '';
  }

  /**
   * output data as json - overwrite
   * _________________________________________________________________
   */
  public function drawJson()
  {
    echo '';
  }

}

?>
