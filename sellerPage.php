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

<form method = "post" action = "sellerPage.php" enctype="multipart/form-data">
<table>
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
		<td><input type = "submit" value = "submit"></td>
	</tr>
</table>
</form>

<div style='position: absolute; top : 10px; right: 10px;'>
<?php
	echo "<a class='login' href= #>Welcome ".$_SESSION['userName']."</a>";
	echo "<a class='login' href= 'NLI.php'> | Logout</a>";

?>
</div>