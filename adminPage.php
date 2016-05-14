<head>
<link rel="stylesheet" href="welcomePage.css">
<style type="text/css">
	table{ font-family: sans-serif; width: 100%; text-align: center;}
	div { padding : 5px; margin: 5px; border:2px solid black;  }
</style>
</head>

<?php

	//only admin authority, may pass here
	session_start();
	if($_SESSION['userType']!='A')
		echo "You are not authorized to access the contents of this page.";
	else if($_SESSION['userType']=='C')
		header("location:welcomePage.php");
	else if($_SESSION['userType']=='S')
		header("location:sellerPage.php");
	else
	{
		require 'config.php';
		
		//to show stats, count the items on page
		$userData = "select * from users;";
		$itemData = "select * from items;";
		$orderData = "select * from orders;";

		$userData = $connect->query($userData);
		$itemData = $connect->query($itemData);
		$orderData = $connect->query($orderData);

		$noOfUsers = mysqli_num_rows($userData);
		$noOfItems = mysqli_num_rows($itemData);
		$noOfOrder = mysqli_num_rows($orderData);

		//this division is used to show the stats
		echo"
				<div style = 'width : 50%; top : 10px; left : 10px;'>
					<table style ='padding: 5px;'>
						<th>Stats</th>
						<tr>
							<td> Total no. of users </td>
							<td> Total no. of items </td>
							<td> Total no. of orders</td>
						</tr>
						<tr>
							<td>".$noOfUsers."</td>
							<td>".$noOfItems."</td>
							<td>".$noOfOrder."</td>
						</tr>
					</table>
				</div>
			";

		
		//This div is used to display all the items in the database.
		echo "
				<div style = 'height: 500px; overflow:auto;'>
				<p>All the items on the database:</p>
				";

		while($row = $itemData->fetch_assoc())
		{
		$locs = $row['imgLoc'];		//since the array name has '' the things got complex
		
		//we only have the seller id of the person, this query is used to display it's actual name.
		$que = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($que);
		$sellerName = $sellerName->fetch_assoc();
		//this is to properly display inside a division for every item, (because this thing is in a while loop everyting is printed accordingly)
		
		echo "<div style='height : 270px; width : 70%; top: 30px; left : 10px;'>";
		echo "<div style=' top: 10px; left: 10px; border:none;'>";
		echo "<img src = '$locs' height = 220 px width = 220px align = left>";
		echo "</div>";
		echo "<div style=' top: 10px; left: 270px; border:none;'>";
		echo "<p> Sold By  : ".$sellerName['userName']."</p>";
		echo "<p> Color : ".$row['color']."</p>";
		echo "<p> Shape : ".$row['shape']."</p>";
		echo "<p> Item Name : ".$row['itemName']."</p>";
		echo "<p> Price  : ".$row['price']."</p>";
		echo "<p> Category : ".$row['category']."</p>";
		echo "</div>";
		echo "</div>";
		}
		echo "</div>";	

		//This div is for all the users in the database
		echo "
				<div style = 'height: 300px; overflow:auto;'>
				<p>All the users on the database:</p>
				<table>
					<tr>
						<td>User Id</td>
						<td>User Name </td>
						<td>User Authority</td>
					</tr>
				";

		while($row = $userData->fetch_assoc())
		{
			if($row['autho']=='A') $authority = "Administrator";
			else if($row['autho']=='C') $authority = "Customer";
			else if($row['autho']=='S') $authority = "Seller";
			echo "<tr>
					<td>".$row['userId']."</td>
					<td>".$row['userName']."</td>
					<td>".$authority."</td>
					</tr>";
		}
		echo "</table></div>";

		//This division is for all the orders in the database
		//this ain't gonna be a easy one
		$que = "select * from (items natural join orders) inner join users on custId = users.userId;";
		$result = $connect->query($que);
		
		echo "
				<div style = 'height: 300px; overflow:auto;'>
				<p>All the users on the database:</p>
				<table>
					<tr>
						<td>Order Id</td>
						<td>Customer Name </td>
						<td>Seller Name</td>
						<td>Item Name</td>
						<td>Quantity</td>
					</tr>
				";

		while($row = $result->fetch_assoc())
		{
			$sellerName = "select userName from users where userId = ".$row['sellerId'];
			$sellerName = $connect->query($sellerName);
			$sellerName = $sellerName->fetch_assoc();
			echo "	<tr>
						<td>".$row['orderId']."</td>
						<td>".$row['userName']."</td>
						<td>".$sellerName['userName']."</td>
						<td>".$row['itemName']."</td>
						<td>".$row['quantity']."</td>
				 	</tr>	";
		}
		echo "</table></div>";
	}
?>



<!-- This is for the logout tab above -->
<div style='position: absolute; top : 10px; right: 10px; border:none;'>
<?php
	echo "<a class='login' href= #>Welcome ".$_SESSION['userName']."</a>";
	echo "<a class='login' href= 'NLI.php'> | Logout</a>";

?>
</div>