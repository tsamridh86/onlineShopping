<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="welcomePage.css">
</head>

<!--
This page is exclusive to the seller only, i will add the extension to redirect the page if the person using is not a seller
Using the relation :
item ( sellerId int , itemName varchar(50),shape varchar(20),color varchar(20), category varchar(20), itemId int primary key, price int , imgLoc varchar(50))

-->
<?php
	session_start();
	
	//unauthorized users will be redirected from this page.
	if(empty($_SESSION['userType'])) header("location:welcomePage.php");
	if($_SESSION['userType']=='C') header("location:welcomePage.php");
	
	//this is to avoid the execution of adding into the database if everything is ready
	if(!empty($_POST['itemName']) && !empty($_POST['price']) && !empty($_POST['brand'])&& is_uploaded_file($_FILES['image']['tmp_name']) && !empty($_POST['category1']) && !empty($_POST['category2']) && !empty($_POST['shape']) && !empty($_POST['color']))
	{
		//connect to the database & check whether as table exists for users
		//some one may try login without the execution of the user table existence.
		require 'config.php';
		//import image to the server page
		$file_temp = $_FILES['image']['tmp_name'];
		$file_name = $_FILES['image']['name'];
		
		
		//if the same name of the file exists then rename it adding numbers to it
		$i = 0 ;
		while(file_exists("images/".$file_name))
		{
			if(!$i) //if($i ==0) this is used if there is only one copy
			$file_name = substr($file_name,0,-4).$i.substr($file_name, -4);
			else 	//this is used if there is more than one copy available 
				$file_name = substr($file_name,0,-5).$i.substr($file_name, -4);
			$i++;
		}
		$i = 0 ; 	//reset the value for i, since the loop has already run it's course.
		move_uploaded_file($file_temp,"images/".$file_name);	//this uploads it into the server

		//combining categories
		$category = $_POST['category1']."_".$_POST['category2'];

		//there are two submit buttons, that determines whether it is to be kept on Bidding or sale, so
		if($_POST['type']=='Keep On Bidding') $type = 'B';
		else $type = 'S';

		//insert into the table
		$ins = "insert into items (brand,itemName,sellerId,price,category,shape,color,imgLoc,type) values ('".$_POST['brand']."','".$_POST['itemName']."',".$_SESSION['userId'].",".$_POST['price'].",'".$category."','".$_POST['shape']."','".$_POST['color']."','"."images/".$file_name."','".$type."');";
		$connect->query($ins);
		echo "Please wait for the admin approval.";
		$connect->close();
	}
?>

<!-- This division is for the items that are going to be added to database -->
<div style="position: relative; top: 10px; left: 10px; border: 2px solid black; width: 50%; margin: 10px; padding: 10px;">
<form method = "post" action = "sellerPage.php" enctype="multipart/form-data">	<!-- enctype is for image transfer   -->
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
		<td>Enter the brand of the item : </td>
		<td><input type = 'text' name = 'brand' required></td>
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
		<td><select name = 'category1'>
				<option value = 'men'>				Men 			</option>
				<option value = 'women'>			Women 			</option>
				<option value = 'kids'>				Kids		 	</option>
			</select>
		</td>	
	</tr>
	<tr>
		<td>Enter a type of the item:</td>
		<td><select name = 'category2'>
				<option value = 'sunglasses'>			Sunglasses		</option>
				<option value = 'eyeglasses'>			Eyeglasses		</option>
			</select>
		</td>	
	</tr>
	<tr>
		<td>Enter a image of the item : </td>
		<td><input type = "file" name="image"/></td>
	</tr>
	<tr>
		<td><input type = "submit" name = 'type' value = "Keep On Sale"></td>
		<td><input type = "submit" name = 'type' value = "Keep On Bidding"></td>
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
		require 'config.php';

		// now, to actually display the things inside here to the seller
		$que = "select itemId, itemName from items where sellerId =".$_SESSION['userId']." and status = 'Y';";
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
		require 'config.php';

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
		require 'config.php';

		//the objective here is to display what items needs to delievered to what person in what quantity
		//so using the natural join between items, orders and users, we can obtain all of this
		//there is no need to create user table if not exists, you would not be in this page if you were not a seller lol


		//this is to only display the items that have been ordered
		//this ain't gonna be a easy one
		$que = "select userName, itemName , quantity, price from (items inner join orders on items.itemId = orders.itemId) inner join users on orders.custId = users.userId where sellerId = ".$_SESSION['userId']." and type = 'S' and status = 'Y';";
		$result = $connect->query($que);
		echo "<table width = 90% style = 'padding : 5px; margin : 5px;'>
				<th> Items to be delievered </th>
				<tr>
					<td> Customer Name </td>
					<td> Item to be delievered </td>
					<td> Quantity </td>
					<td> Rate </td>
					<td> Grand total </td>
				</tr>";

				// The value of grand total is not stored in database because it is a derived attribute & hence causes a wastage of space.
		while($row = $result->fetch_assoc())
		{
			echo "	<tr>
						<td>".$row['userName']."</td>
						<td>".$row['itemName']."</td>
						<td>".$row['quantity']."</td>
						<td>".$row['price']."</td>
						<td>".($row['quantity']*$row['price'])."</td>
				 	</tr>	";
		}
		echo "</table>";

		//this is for the items that are for bidding
		$que = "select items.price , users.userName , items.itemName from items inner join users on users.userId = items.custId where sellerId = ".$_SESSION['userId']." and type = 'B' and status = 'Y';";
		$result = $connect->query($que);
		echo "<table width = 90% style = 'padding : 5px; margin : 5px;'>
				<th> Current bidding prices</th>
				<tr>
					<td> Customer Name </td>
					<td> Item to be delievered </td>
					<td> Current Price </td>
				</tr>";

				// The value of grand total is not stored in database because it is a derived attribute & hence causes a wastage of space.
		while($row = $result->fetch_assoc())
		{
			echo "	<tr>
						<td>".$row['userName']."</td>
						<td>".$row['itemName']."</td>
						<td>".$row['price']."</td>
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