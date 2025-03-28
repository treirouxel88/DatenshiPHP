<?php
// https://www.w3schools.com/xml/xml_rss.asp
// https://www.w3schools.com/php/php_xml_simplexml_get.asp
//https://www.w3schools.com/php/php_ajax_rss_reader.asp
 class rss {
	var $feed;

	function rss($feed)
	{
		$this->feed = $feed;
	}

	private function parseFlux()
    {
		$rss = simplexml_load_file($this->feed);

	    $rss_split = array();

	    foreach ($rss->channel->item as $item) {
		    $title  	 = (string) $item->title; // Title
		    $link   	 = (string) $item->link; // Url Link
		    $description = (string) $item->description; //Description
		    $rss_split[] = '<div class="">→ <a href="'.$link.'" target="_blank" title="" >'.$title.'</a><hr></div>
			';
    	}

    	return $rss_split;
	}

	function displayFlux($numrows,$head)
	{

	    $rss_split = $this->parseFlux();

	    if (($rss_split))
	    {
	    	$totalFeed = count($rss_split);
		    if ($numrows > $totalFeed) {
		    	$numrows = $totalFeed;
		    }
		    $i = 0;
		    $rss_data = '<br><section class="green-section">
		         <article>
             <h1>
           '.$head.'
             </h1>';
		    while ( $i < $numrows )
		   	{
		      $rss_data .= $rss_split[$i];
		      $i++;
		    }

		    $rss_data.='</article></section>';
	    }

	    return $rss_data;
  	}
}
?>
