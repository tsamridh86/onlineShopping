<head>
<link rel="stylesheet" href="welcomePage.css">
</head>

<?php
	session_start();
	if(!empty($_SESSION['userType']) && $_SESSION['userType'] == 'C' && !empty($_POST['itemId']) && !empty($_POST['quantity']))
	{
		//connect to the database & stuff
		$connect = mysqli_connect("localhost","root","");
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
		

		//create the table of items if it does not exists, no need to worry about the users here, because if there are no users, it will show that you have to be a customer to order items.
		$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(25));";
		$connect->query($que);


		//create the table order if it does not exists, all the orders are being saved into the table:
		// orders ( orderId int, userId int , itemId int , quantity int ) , foreign keys will be used, obviously to maintain the consistency of the database
		$que = "create table if not exists orders ( orderId int primary key auto_increment, custId int references users(userId) , itemId int references items(itemId) on delete set null, quantity int );";
		$connect->query($que);
		
		//to finally insert the item to the database
		$order = "insert into orders (custId,itemId,quantity) values (".$_SESSION['userId'].",".$_POST['itemId'].",".$_POST['quantity'].");";
		$connect->query($order);
		$connect->close();
		echo "You have successfully ordered your item. <a href = 'welcomePage.php'> Click here </a> to shop more.";

	}


?>

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