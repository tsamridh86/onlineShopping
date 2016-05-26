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
		
		//if there was an approval request, this shall occur
		if(!empty($_POST['itemId']) && !empty($_POST['approve']))
		{
			$approve = "update items set status = 'Y' where itemId = ".$_POST['itemId'].";";
			$connect->query($approve);
		}


		//to show stats, count the items on page
		$userData = "select * from users;";
		$itemData = "select * from items where status = 'Y';";
		$orderData = "select * from orders;";
		$noOfBidItem = "select itemId from items where type = 'B' and status = 'Y';";
		$noOfBids = "select itemId from items where type = 'B' and custId is not null and status = 'Y';";

		$userData = $connect->query($userData);
		$itemData = $connect->query($itemData);
		$orderData = $connect->query($orderData);
		$noOfBidItem = $connect->query($noOfBidItem);
		$noOfBids = $connect->query($noOfBids);

		$noOfUsers = mysqli_num_rows($userData);
		$noOfItems = mysqli_num_rows($itemData);
		$noOfOrder = mysqli_num_rows($orderData);
		$noOfBidItem  = mysqli_num_rows($noOfBidItem);
		$noOfBids = mysqli_num_rows($noOfBids);

		//this division is used to show the stats
		echo"
				<div style = 'width : 70%; top : 10px; left : 10px;'>
					<table style ='padding: 5px;'>
						<th>Stats</th>
						<tr>
							<td> Total no. of users </td>
							<td> Total no. of items </td>
							<td> Items on Bid </td>
							<td> Items on Sale </td>
							<td> Total no. of orders</td>
							<td> Total no. of bids</td>
						</tr>
						<tr>
							<td>".$noOfUsers."</td>
							<td>".$noOfItems."</td>
							<td>".$noOfBidItem."</td>
							<td>".($noOfItems-$noOfBidItem)."</td>
							<td>".$noOfOrder."</td>
							<td>".$noOfBids."</td>
						</tr>
					</table>
				</div>
			";

		//This div is for the items that await approval from the admin
			$itemPending = "select * from items where status = 'N';";
			$res = $connect->query($itemPending);
			echo "
				<div style = 'height: 500px; overflow:auto;'>
				<form method = 'post' action = 'adminPage.php'>
				<p>Pending requests:</p>
				";
			while($row = $res->fetch_assoc())
			{
				$locs = $row['imgLoc'];		//since the array name has '' the things got complex
				
				//category seperation tarika
				$len = 0;
				while($row['category'][$len++]!="_");
				$cat1 = substr($row['category'],0,$len-1);
				$cat2 = substr($row['category'],$len);
				
				//we only have the seller id of the person, this query is used to display it's actual name.
				$que = "select userName from users where userId = ".$row['sellerId'].";";
				$sellerName = $connect->query($que);
				$sellerName = $sellerName->fetch_assoc();
				//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
		
				echo "<div style='position: relative; height : 330px; width : 70%; top: 30px; left : 10px; border:2px solid black; margin: 10px; '>";
				echo "<div style='position: absolute; top: 10px; left: 10px;border:none;'>";
				echo "<img src = '$locs' height = 220 px width = 220px align = left>";
				echo "</div>";
				echo "<div style='position: absolute; top: 10px; left: 270px;border:none;'>";
				echo "<p> Sold By  : ".$sellerName['userName']."</p>";
				echo "<p> Color : ".$row['color']."</p>";
				echo "<p> Shape : ".$row['shape']."</p>";
				echo "<p> Item Name : ".$row['brand']." ".$row['itemName']."</p>";
				echo "<p> Price  : ".$row['price']."</p>";
				echo "<p> Category : ".$cat1."</p>";
				echo "<p> Type : ".$cat2."</p>";
				echo "<input type = 'hidden' name = 'itemId' value = ".$row['itemId'].">";
				echo "<input type = 'submit' name = 'approve' value = 'approve'>";
				echo "</div>";
				echo "</div>";
			}
			echo "</form></div>";



		//This div is used to display all the items in the database.
		echo "
				<div style = 'height: 500px; overflow:auto;'>
				<p>All the items on the database:</p>
				";

		while($row = $itemData->fetch_assoc())
		{
		$locs = $row['imgLoc'];		//since the array name has '' the things got complex
		
		$len = 0;
		while($row['category'][$len++]!="_");
		$cat1 = substr($row['category'],0,$len-1);
		$cat2 = substr($row['category'],$len);
		//we only have the seller id of the person, this query is used to display it's actual name.
		$que = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($que);
		$sellerName = $sellerName->fetch_assoc();
		//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
		
		echo "<div style='position: relative; height : 330px; width : 50%; top: 30px; left : 10px; border:2px solid black; margin: 10px; '>";
		echo "<div style='position: absolute; top: 10px; left: 10px;border:none;'>";
		echo "<img src = '$locs' height = 220 px width = 220px align = left>";
		echo "</div>";
		echo "<div style='position: absolute; top: 10px; left: 270px;border:none;'>";
		echo "<p> Sold By  : ".$sellerName['userName']."</p>";
		echo "<p> Color : ".$row['color']."</p>";
		echo "<p> Shape : ".$row['shape']."</p>";
		echo "<p> Item Name : ".$row['brand']." ".$row['itemName']."</p>";
		echo "<p> Price  : ".$row['price']."</p>";
		echo "<p> Category : ".$cat1."</p>";
		echo "<p> Type : ".$cat2."</p>";
		if($row['type']=='B')
		{
			$que = "select userName from items inner join users on items.custId = users.userId where itemId = ".$row['itemId'].";";
			$custName = $connect->query($que);
			$custName = $custName->fetch_assoc();
			echo "<p> Latest Bidder : ".$custName['userName']."</p>";
		}
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
		$que = "select * from (items inner join orders on items.itemId = orders.itemId) inner join users on orders.custId = users.userId where type = 'S'";
		$result = $connect->query($que);
		
		echo "
				<div style = 'height: 300px; overflow:auto;'>
				<p>All the orders on the database:</p>
				<table>
					<tr>
						<td>Order Id</td>
						<td>Customer Name </td>
						<td>Seller Name</td>
						<td>Item Name</td>
						<td>Rate</td>
						<td>Quantity</td>
						<td>Grand total</td>
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
						<td>".$row['price']."</td>
						<td>".$row['quantity']."</td>
						<td>".($row['price']*$row['quantity'])."</td>
						</tr>	";
		}
		echo "</table></div>";

		//This div is for all the bids on the page
		$que = "select items.price , users.userName , items.itemName, items.sellerId from items inner join users on users.userId = items.custId where type = 'B'";
		$result = $connect->query($que);
		echo "
				<div style = 'height: 300px; overflow:auto;'>
				<p>All the bidding on the database:</p>
				<table>
					<tr>
						<td>Customer Name </td>
						<td>Seller Name</td>
						<td>Item Name</td>
						<td>Latest Price</td>
					</tr>
				";
		while($row = $result->fetch_assoc())
		{
			$que = "select userName from users where userId = ".$row['sellerId'].";";
			$que = $connect->query($que);
			$que = $que->fetch_assoc();
			echo "	<tr>
						<td>".$row['userName']."</td>
						<td>".$que['userName']."</td>
						<td>".$row['itemName']."</td>
						<td>".$row['price']."</td>
				 	</tr>	";
		}
		echo "</table>";


		$connect->close();
	}
?>



<!-- This is for the logout tab above -->
<div style='position: absolute; top : 10px; right: 10px; border:none;'>
<?php
	echo "<a class='login' href= #>Welcome ".$_SESSION['userName']."</a>";
	echo "<a class='login' href= 'NLI.php'> | Logout</a>";

?>
</div>