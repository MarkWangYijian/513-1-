<?php
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>
<html>
    <link href="style.css" rel="stylesheet" type="text/css" media="all" />
    <nav class="navigation">

        <ul class="menu"><li><a href="index.html"><svg class="home" width="30px" height="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M419.492,275.815v166.213H300.725v-90.33h-89.451v90.33H92.507V275.815H50L256,69.972l206,205.844H419.492 z M394.072,88.472h-47.917v38.311l47.917,48.023V88.472z"/></svg><span title="Home">Home</span></a></li>

        <li ><a href="aboutus.html"><svg class="about" width="30px" height="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M407.448,360.474c-59.036-13.617-113.989-25.541-87.375-75.717 c81.01-152.729,21.473-234.406-64.072-234.406c-87.231,0-145.303,84.812-64.072,234.406c27.412,50.482-29.608,62.393-87.375,75.717 c-59.012,13.609-54.473,44.723-54.473,101.176h411.838C461.919,405.196,466.458,374.083,407.448,360.474z"/></svg><span title="About">About</span></a></li>

        <li><a href="upload.html"><svg class="work" width="30px" height="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M201.875,141.084h-30v-30.563c0-11.747,9.523-21.271,21.271-21.271h125.709 c11.747,0,21.271,9.523,21.271,21.271v30.563h-30v-17.063c0-3.739-3.031-6.771-6.771-6.771h-94.709 c-3.739,0-6.771,3.031-6.771,6.771V141.084z M462,161.084V422.75H50V161.084H462z M347.818,228.582l-4.721-10.858 c-44.045,27.402-67.739,48.047-103.599,88.742c-19.074-13.598-29.838-19.846-46.809-28.057l-14.509,13.412 c29.016,26.895,44.784,44.631,68.409,72.456C284.896,295.584,311.497,260.279,347.818,228.582z"/></svg><span title="Work">Careers</span></a></li>

        <li class="active"><a   class="active"href="orderonline.php"><svg class="lab" width="30px" height="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M225.741,122.262c-10.096-4.89-19.027-8.84-27.496-12.287C211.636,74.43,230.974,51,256.001,51 c24.381,0,43.363,22.232,56.707,56.239c-8.562,3.61-17.355,7.632-27.344,12.62C275.138,93.088,263.472,81,256.001,81 C248.31,81,236.177,93.811,225.741,122.262z M256.001,431c-7.471,0-19.135-12.088-29.361-38.86 c-10.084,5.036-18.803,9.016-27.346,12.621C212.64,438.767,231.62,461,256.001,461c25.029,0,44.365-23.43,57.758-58.974 c-8.439-3.436-17.34-7.367-27.496-12.288C275.827,418.19,263.692,431,256.001,431z M174.112,393.154 c-33.35,12.045-83.111,23.78-102.887-5.42c-9.77-14.427-13.416-42.163,24.695-92.376c10.111-13.322,21.816-26.408,33.689-38.419 c26.635-26.545,56.641-52.096,95.738-78.552c-18.822-10.192-39.367-19.936-59.412-27.187c-20.52-7.424-38.586-11.535-52.428-11.535 c-8.842,0-15.404,1.843-17.553,4.93c-2.285,3.281-2.398,13.373,7.744,31.476c1.201-0.132,2.418-0.206,3.652-0.206 c18.225,0,33,14.775,33,33c0,18.227-14.775,33-33,33s-33-14.773-33-33c0-5.902,1.559-11.439,4.273-16.233 c-19.311-33.481-15.328-53.631-7.291-65.177c5.648-8.115,17.551-17.789,42.174-17.789c43.723,0,102.635,28.356,140.564,50.322 c38.51-23.28,99.422-53.988,144.42-53.988c24.723,0,36.639,9.932,42.281,18.264c9.77,14.428,13.416,42.164-24.693,92.377 C362.44,287.316,255.792,363.653,174.112,393.154z M392.185,198.504c26.182-34.497,26.984-52.643,23.75-57.418 c-2.189-3.232-8.545-5.086-17.441-5.086c-33.951,0-85.664,24.93-115.436,41.803c21.982,14.287,42.518,29.346,61.24,44.701 c-7.084,7.058-14.51,13.981-22.102,20.68c-20.783-16.807-43.885-33.264-67.832-48.011c-27.5,17.708-55.186,38.509-80.055,60.672 c18.705,16.243,40.973,33.296,64.725,49.076c-9.086,5.654-18.26,11.073-28.729,16.744c-0.004-0.003-0.006-0.005-0.01-0.007 c-0.078-0.055-0.154-0.109-0.232-0.164c-19.885-13.853-39.51-29.054-57.662-44.965c-49.02,49.168-62.828,84.795-56.336,94.383 c2.189,3.233,8.549,5.088,17.443,5.088c40.871,0,105.748-34.459,144.129-59.173C304.759,286.485,358.495,242.895,392.185,198.504z M433.524,319.63c2.441-4.605,3.83-9.855,3.83-15.432c0-18.225-14.775-33-33-33s-33,14.775-33,33c0,18.227,14.775,33,33,33 c1.521,0,3.016-0.112,4.48-0.312c9.611,17.505,9.453,27.295,7.209,30.519c-2.148,3.086-8.709,4.929-17.551,4.929 c-27.856,0-66.664-16.141-91.65-28.324c-8.357,5.839-18.73,12.77-28.846,19.122c33.494,17.717,82.752,39.202,120.496,39.202 c24.621,0,36.523-9.674,42.172-17.788C448.683,373.03,452.663,352.955,433.524,319.63z"/></svg><span title="Lab">Order</span></a></li>

        <li><a href="contactus.html"><svg class="contact" width="30px" height="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M170.544,239.052L50,146.454v213.142L170.544,239.052z M460.928,103.407H51.416l204.593,157.162L460.928,103.407z M313.355,260.696l-57.364,43.994l-57.454-44.135L50.5,408.593h410.751L313.355,260.696z M341.367,239.212L462,359.845V146.693L341.367,239.212z"/></svg><span title="Contact">Contact</span></a></li>

        <li><a href="register1.php"><svg class="about" width="30px" height="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M407.448,360.474c-59.036-13.617-113.989-25.541-87.375-75.717 c81.01-152.729,21.473-234.406-64.072-234.406c-87.231,0-145.303,84.812-64.072,234.406c27.412,50.482-29.608,62.393-87.375,75.717 c-59.012,13.609-54.473,44.723-54.473,101.176h411.838C461.919,405.196,466.458,374.083,407.448,360.474z"/></svg><span title="About">Register</span></a></li></ul>

    </nav>

    <div class="wrapper111"><h1>Welcome to Luca's Loaves</h1> <img src="logo.png"   style="width:100px;height:100px;position:absolute;right:200px;top:32px;"/>
	
	<p><a href="welcome.php"style="width:100px;height:100px;position:absolute;right:200px;top:150px;">Log out</a>.</p></div>
	<div class="wrapper">
    <div id="welcome" class="container">
	<div id="product-grid">
	<h2>Join our team</h2>
            <p> We are expanding our branches and need experienced baker regularly.</n>
                Send us your detail with the form below</br>
            and we will be in contact shortly</p>
	<div class="txt-heading">Products</div>
	<table><tr><td><?php
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
		<div class="product-item">
			<form method="post" action="orderonline.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?></div>

			<br><a href='get-product-info.php?id=<?php echo $product_array[$key]["id"];?>'target="_blank"onclick="tan()">click</a>
			<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="5" /><br><input type="submit" value="Add to Cart" class="btnAddAction" style="width:150px;height:60px"/></div>
			
			</div>
			</form>
		</div>
	<?php
		}
	}
	?>
</div>
    <div id="welcome" class="container">
        <div class="title">

	

</div>


<div id="shopping-cart">
<div class="txt-heading">Shopping Cart</div>

<a id="btnEmpty" href="orderonline.php?action=empty">Empty Cart</a>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Name</th>
<th style="text-align:left;">Code</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["code"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="orderonline.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr>
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
<td></td>
</tr>
</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>
</div>
        
<link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.css">

<footer>
<h4 class="" style="font-size:24px">English name:Mark</h4> 
<h4 class="" style="font-size:24px">Chinese name:Mark</h4> 
<h4 class="" style="font-size:24px">Student ID:203190631</h4> 
    <h4 class="fa fa-send" style="font-size:24px">Address:36 Garden Ave, Mullumbimby NSW 2482</h4> 
    <h4 class="fa fa-phone-square" style="font-size:24px">Telephone number:15674386258</h4>
        <h4 class="fa fa-envelope" style="font-size:24px">Email:195542263@qq.com</h4> 
  </footer>
          <html>