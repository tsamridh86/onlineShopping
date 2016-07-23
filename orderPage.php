<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<!-- Latest compiled and minified
JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<head>
<style>
<link rel="stylesheet" href="welcomePage.css">
 p {font-family: sans-serif;}
</style>
</head>

<div style='position: absolute; top : 10px; right: 10px;'>
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
	//condition for a non-user
	if(empty($_SESSION['userType']))
		echo "You must be a customer to buy items, please sign up.";
	//seller would be redirected
	else if ($_SESSION['userType'] == 'S') header("location:sellerPage.php");
	else if ($_SESSION['userType'] == 'C' && !empty($_GET['itemId']))
	{
		require 'config.php';

		//to get data
		$que = "select * from items where itemId =".$_GET['itemId'].";";
		$res = $connect->query($que);
		$row = $res->fetch_assoc();

		//to display the actual name.
		$que = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($que);
		$sellerName = $sellerName->fetch_assoc();

		//separation of category
		$len = 0;
		while($row['category'][$len++]!="_");
		$cat1 = substr($row['category'],0,$len-1);
		$cat2 = substr($row['category'],$len);
	
		echo "<form method = 'post' action = 'orderComplete.php'>";
		$locs = $row['imgLoc'];
		echo "<div style='position: absolute; top: 10px; left: 10px;border:2px solid grey; margin: 10px; box-shadow: 10px 10px 5px 	#DCDCDC;'>";
		echo "<img src = '$locs' height = 500 px width = 500px align = left>";
		echo "</div>";
		echo "<div style='position: absolute; top: 10px; left: 550px;'>";
		echo "<p> Sold By  : ".$sellerName['userName']."</p>";
		echo "<p> Item Name : ".$row['brand']." ".$row['itemName']."</p>";
		echo "<p> Price  : ".$row['price']."</p>";
		echo "<p> Color : ".$row['color']."</p>";
		echo "<p> Shape : ".$row['shape']."</p>";
		echo "<p> Category : ".$cat1."</p>";
		echo "<p> Category : ".$cat2."</p>";
		if ($row['type']=='S')
		echo "<p> Quantity : <input type = number name = 'quantity' > </p>";
		else 
		{
			echo "<p> Bid a price for this item : <input type = 'number' name = 'quantity'></p>";
			echo "<p> Time remaining : ".calcTime($row['deadLine'])."</p>";
		}
		echo "<input type = hidden name = 'type' value = ".$row['type'].">";
		echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
		echo "</div>";
		echo "</form>";
		$connect->close(); 
	}

?>