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

	    if (!file_exists('images')) {
		    mkdir('images', 0777, true);
		}

	    /// Find all images
	    foreach($html->find('img') as $element) {
	        $imageUrl = make_absolute($element->src, $websiteUrl); 
	        
	        $filename = get_filename($imageUrl);
	        
	        if(is_valid_image_type($filename)) {
	        	echo $filename.'<br>';
	        	$image = file_get_contents($imageUrl);
		        file_put_contents('images/'.$filename, $image);
	        }
	    }
	    
	    // clean up memory
	    $html->clear();
	    unset($html);

	    return;
	}

	scraping_website($website);
	echo '<br>'.'Done.'.'<br>';
	echo 'Images saved in '.getcwd().'<br>';

	function make_absolute($url, $base) 
{
    // Return base if no url
    if( ! $url) return $base;

    // Return if already absolute URL
    if(parse_url($url, PHP_URL_SCHEME) != '') return $url;
    
    // Urls only containing query or anchor
    if($url[0] == '#' || $url[0] == '?') return $base.$url;
    
    // Parse base URL and convert to local variables: $scheme, $host, $path
    extract(parse_url($base));

    // If no path, use /
    if( ! isset($path)) $path = '/';
 
    // Remove non-directory element from path
    $path = preg_replace('#/[^/]*$#', '', $path);
 
    // Destroy path if relative url points to root
    if($url[0] == '/') $path = '';
    
    // Dirty absolute URL
    $abs = "$host$path/$url";
 
    // Replace '//' or '/./' or '/foo/../' with '/'
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for($n = 1; $n > 0; $abs = preg_replace($re, '/', $abs, -1, $n)) {}
    
    // Absolute URL is ready!
    return $scheme.'://'.$abs;
}

function get_filename($url) {

	$filename = basename($url);

	//check if it has any queries after the file extension
	$filenameSplit = explode('?',$filename);

	return $filenameSplit[0];
}

function is_valid_image_type($filename) {
	$pathinfo = pathinfo($filename);
	if(!array_key_exists('extension',$pathinfo)) return False;

	$extension = $pathinfo['extension'];

	if($extension === 'jpg') return True;
	if($extension === 'png') return True;
	return false;
}

?>

</body>
</html>