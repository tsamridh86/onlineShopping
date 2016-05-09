<head>
<style>
 p {
 	font-family: sans-serif;

 }
</style>
</head>


<div style='position: absolute; top : 10px; left: 10px;'>
	Is this what you were looking for?
</div>


<!-- Login tab will only be displayed if there is no user logged in.
	 & for a logged in user there will be a logout option -->
<div style='position: absolute; top : 10px; right: 10px;'>
<?php
	
	if(!$_SESSION['userType'])
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
	/*
	The user has entered the item name that s/he requires, so search for it & display them accordingly
	*/
	$item = $_GET['query'];
	//connect to the database & all the things we have been thru lol
	$connect = mysqli_connect("localhost","root","");
	$dbstart = "create database if not exists shops;";
	$connect->query($dbstart);
	mysqli_select_db($connect , "shops");
	$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(25));";
	$connect->query($que);
	$que = "select * from items where itemName like  '%".$item."%';";
	$result = $connect->query($que);
	echo "<form method = get action = 'orderPage.php'>";
	while($row = $result->fetch_assoc())
	{
		$locs = $row['imgLoc'];		//since the array name has '' the things got coomplex
		$que = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($que);
		$sellerName = $sellerName->fetch_assoc();
		echo "<div style='position: relative; height : 250px; width : 90%; top: 30px; left : 10 px; border:2px solid black; margin: 10px;'>";
		echo "<div style='position: absolute; top: 10px; left: 10px;'>";
		echo "<img src = '$locs' height = 200 px width = 200px align = left>";
		echo "</div>";
		echo "<div style='position: absolute; top: 10px; left: 250px;'>";
		echo "<p> Sold By  : ".$sellerName['userName']."</p>";
		echo "<p> Item Name : ".$row['itemName']."</p>";
		echo "<p> Price  : ".$row['price']."</p>";
		echo "<p> Category : ".$row['category']."</p>";
		echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
		echo "</div>";
		echo "</div>";
	}
	echo "</form>";
	$connect->close();
?>