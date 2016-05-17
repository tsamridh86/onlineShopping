<head>
<style>
<link rel="stylesheet" href="welcomePage.css">
 p {font-family: sans-serif;}
</style>
</head>


<!-- Login tab will only be displayed if there is no user logged in.
 & for a logged in user there will be a logout option -->
<div style='position: absolute; top : 10px; right: 10px;'>
<?php
	session_start();
	if(empty($_SESSION['userType']))
		echo "
			<a class='login' href= 'SignUp.php'>Sign Up | </a>
			<a class='login' href = 'LoginPage.php'>Login</a>";
	else 
	{
		echo "<a class='login' href= #>Welcome ".$_SESSION['userName']."</a>";
		echo "<a class='login' href= 'NLI.php'> | Logout</a>";

	}
?>
</div>

<?php
	//a non-user would be stopped here
	if(empty($_SESSION['userType']))
		echo "You must be a customer to buy items, please sign up.";
	//seller would be redirected
	else if ($_SESSION['userType'] == 'S') header("location:sellerPage.php");
	else if ($_SESSION['userType'] == 'C' && !empty($_GET['itemId']))
	{
		//connect to the database & stuff
		require 'config.php';

		//get all the data on the item.
		$que = "select * from items where itemId =".$_GET['itemId'].";";
		$res = $connect->query($que);
		$row = $res->fetch_assoc();

		//we only have the seller id of the person, this query is used to display it's actual name.
		$que = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($que);
		$sellerName = $sellerName->fetch_assoc();

		//this form will redirect to the order complete page
		echo "<form method = 'post' action = 'orderComplete.php'>";
		//show the item to the user, this time a little larger :P
		$locs = $row['imgLoc'];
		echo "<div style='position: absolute; top: 10px; left: 10px;'>";
		echo "<img src = '$locs' height = 500 px width = 500px align = left>";
		echo "</div>";
		echo "<div style='position: absolute; top: 10px; left: 550px;'>";
		echo "<p> Sold By  : ".$sellerName['userName']."</p>";
		echo "<p> Item Name : ".$row['itemName']."</p>";
		echo "<p> Price  : ".$row['price']."</p>";
		echo "<p> Color : ".$row['color']."</p>";
		echo "<p> Shape : ".$row['shape']."</p>";
		echo "<p> Category : ".$row['category']."</p>";
		if ($row['type']=='S')
		echo "<p> Quantity : <input type = number name = 'quantity' > </p>";
		else 
			echo "<p> Bid a price for this item : <input type = 'number' name = 'quantity'></p>";
		echo "<input type = hidden name = 'type' value = ".$row['type'].">";	//the type of the item must be sent discreetly
		echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
		echo "</div>";
		echo "</form>";
		$connect->close();
	}

?>