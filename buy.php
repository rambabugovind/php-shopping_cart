<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['b']) || $_SESSION['b']==null)
{
$_SESSION['b']=array();
$_SESSION['pId']=array();
}
if (isset($_GET['buy'])) {
  $pId=$_GET['buy'];
  $pstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&productId='.$pId);
  $pxml = new SimpleXMLElement($pstr);
  $parray=array(0=>$pId, 1=>(string)$pxml->categories->category->items->product->images->image[0]->sourceURL,2=>(string)$pxml->categories->category->items->product->name,3=>(string)$pxml->categories->category->items->product->minPrice,4=>(string)$pxml->categories->category->items->product->productOffersURL);

  if(!in_array($pId,$_SESSION['pId'])){
	$_SESSION['b'][$pId]=$parray;
    $_SESSION['pId'][$pId]=$pId;
  }
}
elseif (isset($_GET['clear'])) {
  session_unset();
  $_SESSION['b']=array();
  $_SESSION['pId']=array();
}
elseif (isset($_GET['delete'])) {
  $dpid=$_GET['delete'];
  unset($_SESSION['b'][$dpid]);
  unset($_SESSION['pId'][$dpid]);
}
?>
<html>
<head><title>Buy Products</title>
<link href="styles.css" rel="stylesheet" />
</head>
<body>
<div id="Cart">
<p>Shopping Basket:</p>
<?php
if(!empty($_SESSION['b']))
{
  ?><table border="1">
  <tbody>
  <?php foreach ($_SESSION['b'] as $prodt) {
    if($prodt!='')
    {
      $lnk='buy.php?delete='.$prodt[0];
	  $total=$total+$prodt[3];
      ?><tr>     
      <td><a href="<?php echo $prodt[4]; ?>"><img src='<?php echo $prodt[1]; ?>'></img></a></td>
      <td><?php echo $prodt[2]; ?></td>
      <td><?php echo $prodt[3] ?></td>
      <td><a href= "<?php echo $lnk; ?>">Delete</td>
      </tr>
    <?php }
  } ?>
  </tbody>
  </table>
<?php }
else{
	?> <p>Cart is empty.</p>
<?php } ?>
<p>Total Amount: $<?php echo $total ?></p>



<div id="div1">

<form action="buy.php" method="GET">
<center>
<select name="category">
<?php
		include 'C:\xampp\htdocs\chromephp-master\ChromePhp.php';
		//header('Content-Type: text/xml');
		$catstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&showAllDescendants=true');
		$catni = array();
		$cat = new SimpleXMLElement($catstr);
		$mcat = $cat -> category -> categories -> category;
		foreach ($mcat as $cats)
		{
			//print ($cat->category -> categories->category[0]->name->asXML());
			//include below inside option tag if u need to disable those that dont hav categoryId
			//<?php if($cats->attributes()->id == null) echo(disabled)
				echo ($cats->attributes()->id);
		?>
        <option value="<?php echo (string)($cats->attributes()->id); ?>" class="poption" ><?php echo $cats->name->asXML(); ?></option>
        <?php
		$dcat = $cats->categories -> category;
		if($dcat != null)
		{
			foreach($dcat as $catss){
				$catni[(string)$catss->name] = (string)$catss->attributes()->id;
        ?>
        <option value="<?php echo (string)($catss->attributes()->id); ?>" class="coption" >&nbsp;<?php echo $catss->name->asXML(); ?></option>
		<?php
		$ddcat = $catss->categories -> category;
		if($ddcat != null)
		{
			foreach($ddcat as $catsss){
				$catni[(string)$catsss->name] = (string)$catsss->attributes()->id;
        ?>
        <option value="<?php echo (string)($catsss->attributes()->id); ?>" class="coption" >&nbsp;-><?php echo $catsss->name->asXML(); ?></option>
        <?php
        }}}}}
        ?>

</select>
<input type="text" name="search"/>
<input type="submit" value="Search"/>
</center>
</form>
</div>
<?php

$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId='.$_GET['category'].'&keyword='.$_GET['search']);
$xml = new SimpleXMLElement($xmlstr);
?>
<table border="1">
<?php
$offers = $xml->categories->category->items->offer;
$prods = $xml->categories->category->items->product;
/*To display offers in addition to searched product
if($offers != null)
{
	foreach($offers as $off)
{ ?>
<tr>
<td><a href="buy.php?buy=<?php echo($off['id']); ?>" ><img src=<?php echo ($off->imageList->image->sourceURL); ?>></a></td>
<td><?php echo ($off->name); ?></td>
<td><?php echo ($off->basePrice); ?></td>
<td><?php echo ($off->description); ?></td>
</tr>
<?php } 
}*/
foreach($prods as $prod)
{ ?>
<tr>
<td><a href="buy.php?buy=<?php echo($prod['id']); ?>" ><img src=<?php echo ($prod->images->image->sourceURL); ?>></a></td>
<td><?php echo ($prod->name); ?></td>
<td><?php echo ($prod->minPrice); ?></td>
<td><?php echo ($prod->fullDescription); ?></td>
</tr>
<?php } 
?>
</table>
</body>
</html>
