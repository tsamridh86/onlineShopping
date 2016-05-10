<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="welcomePage.css">
</head>

<!--
The instructions above are for the css of the logout options, they need to be modified.
This page is exclusive to the seller only, i will add the extension to redirect the page if the person using is not a seller
Using the relation :
item ( sellerId int , itemName varchar(50),shape varchar(20),color varchar(20), category varchar(20), itemId int primary key, price int , imgLoc varchar(50))

-->
<?php
	session_start();
	//connect to the database & check whether as table exists for users
	//some one may try login without the execution of the user table existence.
	if(!empty($_POST['itemName']) && !empty($_POST['price']) && is_uploaded_file($_FILES['image']['tmp_name']) && !empty($_POST['category'])&& !empty($_POST['shape']) && !empty($_POST['color']))
	{
		$connect = mysqli_connect("localhost","root","");
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
		$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(20),shape varchar(20),color varchar(20));";
		$connect->query($que);
		//import image to the server page
		$file_temp = $_FILES['image']['tmp_name'];
		$file_name = $_FILES['image']['name'];
		move_uploaded_file($file_temp,"images/".$file_name);

		//insert into the table
		$ins = "insert into items (itemName,sellerId,price,category,shape,color,imgLoc) values ('".$_POST['itemName']."',".$_SESSION['userId'].",".$_POST['price'].",'".$_POST['category']."','".$_POST['shape']."','".$_POST['color']."','"."images/".$file_name."');";
		$connect->query($ins);
		$connect->close();
	}
?>

<!-- This division is for the items that are going to be added to database -->
<div style="position: relative; top: 10px; left: 10px; border: 2px solid black; width: 50%; margin: 10px; padding: 10px;">
<form method = "post" action = "sellerPage.php" enctype="multipart/form-data">
<table style="padding: 10px;">
	<th>Keep an item on sale</th>
	<tr>
		<td>Enter the name of the item: </td>
		<td><input type = "text" name = "itemName" required></td>
	</tr>
	<tr>
		<td>Enter the shape of the item: </td>
		<td>
			<select name = 'shape'>
				<option value = 'normal'>	Normal 	</option>
				<option value = 'round'>	Round 	</option>
				<option value = 'full_frame'>Full Frame </option>
				<option value = 'half_frame'>Half Frame </option>
				<option value = 'frameless'>Frameless</option>
				<option value = 'large_frame'>Large Frame </option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Enter the color of the item : </td>
		<td><input type = 'text' name = 'color' required></td>
	</tr>
	<tr>
		<td>Enter the price of the item:</td>
		<td><input type = "number" name = "price" required></td>
	</tr>
	<tr>
	</tr>
	<tr>
		<td>Enter a category of the item:</td>
		<td><select name = 'category'>
				<option value = 'men'>				Men 			</option>
				<option value = 'women'>			Women 			</option>
				<option value = 'kids'>				Kids		 	</option>
				<option value = 'sunglasses'>		Sunglasses		</option>
				<option value = 'antique'>			Antique			</option>
			</select>
		</td>	
	</tr>
	<tr>
		<td>Enter a image of the item : </td>
		<td><input type = "file" name="image"/></td>
	</tr>
	<tr>
		<td><input type = "submit" value = "Keep On Sale"></td>
	</tr>
</table>
</form>
</div>


<!--This division is made if the seller wants to delete thier items 
	The idea is to display all the items that the seller has & will select it from the dropdown menu if he wants to delete it
-->
<div style="position: relative; margin: 10px; border: 2px solid black; width: 50%; top : 10px;left: 10px; padding: 10px;">
<h4>Select an item that you want to remove from the list</h4>
<form method="get" action="sellerPage.php">
<select name = 'toDelete'>
	<option value = 'none'>--Select an item to delete--</option>
	<?php
		//gotta reconnect to the database, because this section is optional, & hence may not be used, when unused the connectivity remains disconnect so no risking here
		$connect = mysqli_connect("localhost","root","");
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
		$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(20),shape varchar(20),color varchar(20));";
		$connect->query($que);

		// now, to actually display the things inside here to the seller
		$que = "select itemId, itemName from items where sellerId =".$_SESSION['userId'].";";
		$result = $connect->query($que);
		while ($row = $result->fetch_assoc())
		{
			echo "<option value = ".$row['itemId'].">".$row['itemName']."</option>";
		}
		$connect->close();
	?>
</select>
<input type="submit" value="delete">
</form>
<?php
	if(!empty($_GET['toDelete']))
	{
		$connect = mysqli_connect("localhost","root","");
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
		$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(20),shape varchar(20),color varchar(20));";
		$connect->query($que);

		//Now to actually delete the item from the items table
		$que = "delete from items where itemId = ".$_GET['toDelete'].";";
		$connect->query($que);

		//it also has to be removed from the orders so
		$que = "delete from orders where itemId = ".$_GET['toDelete'].";";
		$connect->query($que);
		$connect->close();
		echo "Successfully deleted.";
	}
?>
</div>

<!-- This is for the the items that the seller has to deliver to various people -->
<div style="position: relative;margin: 10px; border: 2px solid black; width: 50%; top : 10px;left: 10px; padding: 10px;">
	<?php

		//connect to the database and stuff
		$connect = mysqli_connect("localhost","root","");
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
		
		//create the table items if it does not exists lol
		$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(20),shape varchar(20),color varchar(20));";
		$connect->query($que);

		//create the table of orders if they don't exists
		$que = "create table if not exists orders ( orderId int primary key auto_increment, custId int references users(userId) , itemId int references items(itemId) on delete set null, quantity int );";
		$connect->query($que);

		//the objective here is to display what items needs to delievered to what person in what quantity
		//so using the natural join between items, orders and users, we can obtain all of this
		//there is no need to create user table if not exists, you would not be in this page if you were not a seller lol

		//this ain't gonna be a easy one
		$que = "select userName, itemName , quantity from (items natural join orders) inner join users on custId = users.userId where sellerId =".$_SESSION['userId'].";";
		$result = $connect->query($que);
		echo "<table width = 90% style = 'padding : 5px; margin : 5px;'>
				<tr>
					<td> Customer Name </td>
					<td> Item to be delievered </td>
					<td> Quantity </td>
				</tr>";
		while($row = $result->fetch_assoc())
		{
			echo "	<tr>
						<td>".$row['userName']."</td>
						<td>".$row['itemName']."</td>
						<td>".$row['quantity']."</td>
				 	</tr>	";
		}
		echo "</table>";
		$connect->close();
	?>
</div>


<!-- This is for the logout tab above -->
<div style='position: absolute; top : 10px; right: 10px;'>
<?php
	echo "<a class='login' href= #>Welcome ".$_SESSION['userName']."</a>";
	echo "<a class='login' href= 'NLI.php'> | Logout</a>";

?>
</div>