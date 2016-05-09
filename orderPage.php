<!-- Login tab will only be displayed if there is no user logged in.
 & for a logged in user there will be a logout option -->
<div style='position: absolute; top : 10px; right: 10px;'>
<?php
	session_start();
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
	
	if(empty($_SESSION['userType']))
		echo "You must be a customer to buy items, please sign up.";
	else if (!empty($_SESSION['userType']) && $_SESSION['userType'] == 'C' && !empty($_GET['itemId']))
	{
		//connect to the database & stuff
		$connect = mysqli_connect("localhost","root","");
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
		

		//create the table of items if it does not exists, no need to worry about the users here, because if there are no users, it will show that you have to be a customer to order items.
		$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(25));";
		$connect->query($que);
		


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
		echo "<p> Category : ".$row['category']."</p>";
		echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
		echo "</div>";
		echo "</form>";
		$connect->close();
	}

?>