<head>
<link rel="stylesheet" href="welcomePage.css">
</head>

<?php
	session_start();
	if(!empty($_SESSION['userType']) && $_SESSION['userType'] == 'C' && !empty($_POST['itemId']) && !empty($_POST['quantity']))
	{
		//connect to the database & stuff
		require 'config.php';
		

		//to finally insert the item to the database if it is on sale:
		if($_POST['type']=='S')
		{
		$order = "insert into orders (custId,itemId,quantity) values (".$_SESSION['userId'].",".$_POST['itemId'].",".$_POST['quantity'].");";
		$connect->query($order);
		
		$itemData = "select itemName, price from items where itemId = ".$_POST['itemId'];
		$itemData = $connect->query($itemData);
		$itemData = $itemData->fetch_assoc();
		echo "<table>
				<th> Order Review : </th>
					<tr>
						<td> Item Name : </td>
						<td>".$itemData['itemName']."</td>
					</tr>
					<tr>
						<td> Quantity : </td>
						<td>".$_POST['quantity']."</td>
					</tr>
					<tr>
						<td> Price : </td>
						<td>".$itemData['price']."</td>
					</tr>
					<tr>
						<td> Grand total : </td>
						<td>".($itemData['price']*$_POST['quantity'])."</td>

					</tr>	
				</table>
				";
		echo "You have successfully ordered your item. <a href = 'welcomePage.php'> Click here </a> to shop more.";
		}

		//if it was bid then display the new price & change the value in the items page instead.
		else
		{
			$order = "update items set custId = ".$_SESSION['userId']." , price = ".$_POST['quantity']." where itemId = ".$_POST['itemId'].";";
			$connect->query($order);
			$itemData = "select itemName, price from items where itemId = ".$_POST['itemId'];
			$itemData = $connect->query($itemData);
			$itemData = $itemData->fetch_assoc();
			echo "<table>
					<th> Order Review : </th>
						<tr>
							<td> Item Name : </td>
							<td>".$itemData['itemName']."</td>
						</tr>
						<tr>
							<td> Price : </td>
							<td>".$itemData['price']."</td>
						</tr>
					</table>
					";
				echo "You have bid your amount.<a href = 'welcomePage.php'> Click here </a> to shop more.";	
			}

		$connect->close();

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