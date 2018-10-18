<!DOCTYPE html>
<html>
<head>
	<title>Hello World!</title>
</head>
<body>

<?php
	include_once('C:\\PHP\\simplehtmldom_1_5\\simple_html_dom.php');
	$website = $_POST['name'];

	function scraping_website($websiteUrl) {
	    // Create HTML DOM
	    $html = new simple_html_dom();
	    $html->load_file($websiteUrl);

	    /// Find all images
	    foreach($html->find('img') as $element) {
	        $imageUrl = $websiteUrl.$element->src;
	        $filename = basename($imageUrl);
	        $image = file_get_contents($imageUrl);
	        file_put_contents($filename, $image);
	    }
	    
	    // clean up memory
	    $html->clear();
	    unset($html);

	    return;
	}

	scraping_website($website);
	echo 'Done.'.'<br>';
	echo 'Images saved in '.getcwd().'<br>';
?>

</body>
</html>