<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<!-- Latest compiled and minified
JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="welcomePage.css">
</head>

<?php
	session_start();
	
	//unauthorized users will be redirected from this page.
	if(empty($_SESSION['userType'])) header("location:welcomePage.php");
	if($_SESSION['userType']=='C') header("location:welcomePage.php");
	require 'config.php';
	if(!empty($_POST['itemName']) && !empty($_POST['price']) && !empty($_POST['brand'])&& is_uploaded_file($_FILES['image']['tmp_name']) && !empty($_POST['category1']) && !empty($_POST['category2']) && !empty($_POST['shape']) && !empty($_POST['color']))
	{
		
		//import image to the server page
		$file_temp = $_FILES['image']['tmp_name'];
		$file_name = $_FILES['image']['name'];
		$i = 0 ;
		while(file_exists("images/".$file_name))
		{
			if(!$i) 
			$file_name = substr($file_name,0,-4).$i.substr($file_name, -4);
				else 
				$file_name = substr($file_name,0,-5).$i.substr($file_name, -4);
			$i++;
		}
			$i = 0 ; 	
			move_uploaded_file($file_temp,"images/".$file_name);	//this uploads it into the server
		//combining categories
		$category = $_POST['category1']."_".$_POST['category2'];
		if($_POST['type']=='Keep On Bidding')
			{
				$type = 'B';
				if(!empty($_POST['deadLine']))
					$deadLine = strtotime($_POST['deadLine']);
				$ins = "insert into items (brand,itemName,sellerId,price,category,shape,color,imgLoc,type,deadLine) values ('".$_POST['brand']."','".$_POST['itemName']."',".$_SESSION['userId'].",".$_POST['price'].",'".$category."','".$_POST['shape']."','".$_POST['color']."','"."images/".$file_name."','".$type."',".$deadLine.");";
			}
		else
		{
		$type = 'S';
		$ins = "insert into items (brand,itemName,sellerId,price,category,shape,color,imgLoc,type) values ('".$_POST['brand']."','".$_POST['itemName']."',".$_SESSION['userId'].",".$_POST['price'].",'".$category."','".$_POST['shape']."','".$_POST['color']."','"."images/".$file_name."','".$type."');";
		}
		$connect->query($ins);
		echo "Please wait for the admin approval.";
		
	}
?>

<div style="position: relative; top: 10px; left: 10px; border: 2px solid black; width: 90%; margin: 10px; padding: 10px; border:2px solid grey; margin: 50px; box-shadow: 10px 10px 5px 	#DCDCDC; ">
	<form method = "post" action = "sellerPage.php" enctype="multipart/form-data">	
	<table class="table table-stripped">
		<th>Keep an item on sale</th>
		<tr>
			<td>Enter the name: </td>
			<td><input type = "text" name = "itemName" required></td>
		</tr>
		<tr>
			<td>Enter the shape: </td>
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
			<td>Enter the brand : </td>
			<td><input type = 'text' name = 'brand' required></td>
		</tr>
		<tr>
			<td>Enter the color : </td>
			<td><input type = 'text' name = 'color' required></td>
		</tr>
		<tr>
			<td>Enter the price:</td>
			<td><input type = "number" name = "price" required></td>
		</tr>
		<tr>
			<td>Enter the deadline(for bidding):</td>
			<td><input type = "date" name = "deadLine"></td>
		</tr>
		<tr>
		</tr>
		<tr>
			<td>Enter a category:</td>
			<td><select name = 'category1'>
				<option value = 'men'>				Men 			</option>
				<option value = 'women'>			Women 			</option>
				<option value = 'kids'>				Kids		 	</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Enter a type:</td>
		<td><select name = 'category2'>
			<option value = 'sunglasses'>			Sunglasses		</option>
			<option value = 'eyeglasses'>			Eyeglasses		</option>
		</select>
	</td>
</tr>
<tr>
	<td>Enter a image : </td>
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
<div style="position: relative; margin:10px; border: 2px solid black; width: 86%; top : 10px;left: 10px; padding:40px;border:2px solid grey; margin: 50px; box-shadow: 10px 10px 5px 	#DCDCDC; ">
<h4>Select an item that you want to remove from the list</h4>
<form method="get" action="sellerPage.php">
<select name = 'toDelete'>
<option value = 'none'>--Select an item to delete--</option> 
<?php
	
	$que = "select itemId, itemName from items where sellerId =".$_SESSION['userId']." and status = 'Y';";
	$result = $connect->query($que);
	while ($row = $result->fetch_assoc())
	{
		echo "<option value = ".$row['itemId'].">".$row['itemName']."</option>";
	}
	
?>
</select>
<input type="submit" value="delete">
</form>
<?php
	if(!empty($_GET['toDelete']))
	{
		
		//Now to actually delete the item from the items table
		$que = "delete from items where itemId = ".$_GET['toDelete'].";";
		$connect->query($que);
		//to remove items from orders
		$que = "delete from orders where itemId = ".$_GET['toDelete'].";";
		$connect->query($que);
		
		echo "Successfully deleted.";
	}
?>
</div>
<!-- items that are to be delivered -->
<div style="position: relative;margin: 10px; border: 2px solid black; width: 50%; top : 10px;left: 10px; padding: 10px; border:2px solid grey; margin: 50px; box-shadow: 10px 10px 5px 	#DCDCDC;">
<?php
	$que = "select userName, itemName , quantity, price from (items inner join orders on items.itemId = orders.itemId) inner join users on orders.custId = users.userId where sellerId = ".$_SESSION['userId'].";";
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
	//items on bid
	$que = "select items.price , users.userName , items.itemName, items.status from items inner join users on users.userId = items.custId where sellerId = ".$_SESSION['userId']." and type = 'B' and status = 'Y' or status = 'D';";
	$result = $connect->query($que);
	echo "<table width = 90% style = 'padding : 5px; margin : 5px;'>
			<th> Current bidding prices</th>
			<tr>
				<td> Customer Name </td>
				<td> Item Name </td>
				<td> Current Price </td>
				<td> Bidding Status </td>
			</tr>";
	
	while($row = $result->fetch_assoc())
	{
		echo "	<tr>
					<td>".$row['userName']."</td>
					<td>".$row['itemName']."</td>
					<td>".$row['price']."</td>";
		if($row['status']=='Y') echo "<td>Open</td>";		
		else echo "<td>Closed</td>";
			echo "</tr>";
	}
	echo "</table>";
	$connect->close();
?>
</div>
<div style='position: absolute; top : 10px; right: 10px;'>
<?php
echo "<a class='login' href= WelcomePage.php ><span class='glyphicon glyphicon-user' aria-hidden='true'>" . $_SESSION['userName'] . "</span></a>
			<a class='login' href= 'NLI.php'><span class='glyphicon glyphicon-closed'aria-hidden='true'> | Logout</a>";
	
?>
</div>