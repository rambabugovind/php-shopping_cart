<!DOCTYPE HTML>
<html>
<head><title>Buy Products</title>
<link href="styles.css" rel="stylesheet" />
</head>
<body>
<div id="div1">

<form action="buy.php" method="GET">
<center>
<select>
  <option selected="selected">Choose one</option>
	<?php
		include 'C:\xampp\htdocs\chromephp-master\ChromePhp.php';
		$catstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&showAllDescendants=true');
		$cat = new SimpleXMLElement($catstr);
		$mcat = $cat -> category -> categories -> category;
		//Chromephp::log($mcat);
		foreach ($mcat as $cats)
		{
			ChromePhp::log($cats); //-> categories -> category);
		}
	?>
</select>
<input type="text" name="search"/>
<input type="submit" value="Search"/>
</center>
</form>
</div>
<?php
//header('Content-Type: application/xml; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors','On');


$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&keyword='.$_GET['search']);
$xml = new SimpleXMLElement($xmlstr);
ChromePhp::log($xml);//prints log in the console window
//print_r ($xml -> categories  -> category -> items -> offer[0]->basePrice);
?>
</body>
</html>
