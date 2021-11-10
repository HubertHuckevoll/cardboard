<?php

namespace cb\view\page;

class cbSitemapVP extends \cb\view\fragment\cbBaseVF
{
  /**
   * draw API
   * _________________________________________________________________
   */
	public function draw()
	{
  	$erg = '<?xml version="1.0" encoding="UTF-8"?>'.
					 '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($this->data as $link)
    {
    	$erg .= '<url><loc>'.$link.'</loc></url>';
    }

    $erg .= '</urlset>';

    header('Content-Type: text/xml');
    echo $erg;
  }
}

?>