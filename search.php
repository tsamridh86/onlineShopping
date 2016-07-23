<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<!-- Latest compiled and minified
JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<head>
	<style>
	p {font-family: sans-serif; }
	p {font-weight: bold; }
	</style>
</head>
<div style='position: absolute; top : 5px; left: 10px; z-index: 1;'>
	<h4> Is this what you were looking for? </h4>
</div>
<div style='position: absolute; top : 10px; right: 100px;z-index: 1;'>
	<?php
		session_start();
		if(empty($_SESSION['userType']))
			echo "
				<a class='login' href= 'SignUp.php'>Sign Up | </a>
				<a class='login' href = 'LoginPage.php'>Login</a>";
		else
		{
			echo "<a class='login' href= WelcomePage.php ><span class='glyphicon glyphicon-user' aria-hidden='true'>" . $_SESSION['userName'] . "</span></a>
			<a class='login' href= 'NLI.php'><span class='glyphicon glyphicon-closed'aria-hidden='true'> | Logout</a>";
		}
		
	?>
</div>

<?php
	//cat is for the navigational bar
    require 'config.php';
	
	//searching part here
	$que = "select * from items where status = 'Y' and ";
	if(!empty($_GET['query'])) // this is for search bar
		$que = $que."itemName like '%".$_GET['query']."%' or brand like '%".$_GET['query']."%' and ";
	if(!empty($_GET['shape'])) //side navigation bar
		$que = $que."shape = '".$_GET['shape']."' and "; 
	if(!empty($_GET['color']))
		$que = $que."color = '".$_GET['color']."' and ";
	if(!empty($_GET['category1']))
		$que = $que."category like '".$_GET['category1']."%' and ";
	if(!empty($_GET['category2']))
		$que = $que."category like '%".$_GET['category2']."' and ";
	if(!empty($_GET['cat']))
		$que = $que."category = '".$_GET['cat']."' and ";
	if(!empty($_GET['seller']))
		$que = $que."sellerid = ".$_GET['seller']." and ";
	if(!empty($_GET['type']))
		$que = $que."type = '".$_GET['type']."' and ";
	if(!empty($_GET['brand']))
		$que = $que."brand = '".$_GET['brand']."' and ";
	$que = substr($que,0,-4);
	$que = $que.";";
	$result = $connect->query($que);
	echo "	<form method = get action = 'search.php'>
			<div style ='position:relative; top : 30px; left : 10px; border:2px solid grey; margin: 10px; box-shadow: 10px 10px 5px 	#DCDCDC; width:270px; height : 80%; overflow:auto; padding : 3px;'>
			Enter the shape of the item.<br><br>";
	$sidebar = "select distinct shape from items where status = 'Y';";
	$sidebar = $connect->query($sidebar);
	while($prop = $sidebar->fetch_assoc())
		echo "<input name = 'shape' type = 'radio' value = ".$prop['shape'].">".$prop['shape']."<br><br>";
		
		//color of items
	echo "	Enter the color of the item:<br><br>";
	$sidebar = "select distinct color from items where status = 'Y';";
	$sidebar = $connect->query($sidebar);
	while($prop = $sidebar->fetch_assoc())
		echo "<input name = 'color' type = 'radio' value = ".$prop['color'].">".$prop['color']."<br><br>";
		
	//brand of the item
	echo "	Enter the brand of the item:<br><br>";
	$sidebar = "select distinct brand from items where status = 'Y';";
	$sidebar = $connect->query($sidebar);
	while($prop = $sidebar->fetch_assoc())
		echo "<input name = 'brand' type = 'radio' value = ".$prop['brand'].">".$prop['brand']."<br><br>";
		
	//category1 of item
	echo "	Enter the category of the item:<br><br>";
	echo "<input type = 'radio' name = 'category1' value = 'men'>Men<br><br>";
	echo "<input type = 'radio' name = 'category1' value = 'women'>Women<br><br>";
	echo "<input type = 'radio' name = 'category1' value = 'kids'>Kids<br><br>";
		
	//category2 of item
	echo " Enter the type of item: <br><br>";
	echo "<input type = 'radio' name = 'category2' value = 'sunglasses'>Sunglasses<br><br>";
	echo "<input type = 'radio' name = 'category2' value = 'eyeglasses'>Eyeglasses<br><br>";
	//search by seller
	echo "Enter the seller you want to buy from: <br><br>";
	$sidebar = "select distinct items.sellerid as sellid, users.userName as uname from items inner join users on users.userId = items.sellerId where items.status = 'Y'";
	$sidebar = $connect->query($sidebar);
	while ($prop = $sidebar->fetch_assoc())
		echo "<input name = 'seller' type = 'radio' value = ".$prop['sellid'].">".$prop['uname']."<br><br>";
		
	//search if the item is being sold or on bid
	echo "Search for items on sale or bid<br><br>";
	echo "<input name = 'type' type = 'radio' value = 'B'>Bidding Only<br><br>";
	echo "<input name = 'type' type = 'radio' value = 'S'>For Sale<br><br>";
	//final input button
	echo "<input type = submit value = submit></div></form>";
			
	//for displaying search results
	echo "<div style='overflow : auto; position: absolute; top: 50px; left: 330px; height: 80%; width: 70%;'>";
	echo "<form method = get action = 'orderPage.php'>";
		while($row = $result->fetch_assoc())
		{
			$locs = $row['imgLoc'];		
			$len = 0;
			while($row['category'][$len++]!="_");
			$cat1 = substr($row['category'],0,$len-1);
			$cat2 = substr($row['category'],$len);
			//to display actual name
			$que = "select userName from users where userId = ".$row['sellerId'].";";
			$sellerName = $connect->query($que);
			$sellerName = $sellerName->fetch_assoc();
			echo "<div style='position: relative; height : 380px; width : 75%; top: 30px; left : 10px; border:2px solid grey; margin: 10px; box-shadow: 10px 10px 5px 	#DCDCDC; '>";
			echo "<div style='position: absolute; top: 10px; left: 10px;'>";
				echo "<img src = '$locs' height = 220 px width = 220px align = left>";
			echo "</div>";
			echo "<div style='position: absolute; top: 10px; left: 270px;'>";
			echo "<p> Sold By  : ".$sellerName['userName']."</p>";
			echo "<p> Color : ".$row['color']."</p>";
			echo "<p> Shape : ".$row['shape']."</p>";
			echo "<p> Item Name : ".$row['brand']." ".$row['itemName']."</p>";
			echo "<p> Price  : ".$row['price']."</p>";
			echo "<p> Category : ".$cat1."</p>";
			echo "<p> Category : ".$cat2."</p>";
			if($row['type']=='B')
				{
					$que = "select userName from items inner join users on items.custId = users.userId where itemId = ".$row['itemId'].";";
					$custName = $connect->query($que);
					$custName = $custName->fetch_assoc();
					echo "<p> Latest Bidder : ".$custName['userName']."</p>";
					echo "<p> Time remaining : ".calcTime($row['deadLine'])."</p>";
				}
				if($row['type']=='S')
				echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
				else
					echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Bid</button>";
			echo "</div>";
			echo "</div>";
		}
		echo "</form>";
	echo "</div>";
	$connect->close();
?>
