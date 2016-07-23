<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<!-- Latest compiled and minified
JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<head>
	<link rel="stylesheet" href="welcomePage.css">
</head>
<div class="container fluid">
<?php
	session_start();
	if(!empty($_SESSION['userType']) && $_SESSION['userType'] == 'C' && !empty($_POST['itemId']) && !empty($_POST['quantity']))
	{
		//connect to the database
		require 'config.php';
		
		//to insert the item to the database if it is on sale:
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
		echo "You have successfully ordered your item.Thank you!!. <a href = 'welcomePage.php'> Click here </a> to shop more.";
		}
		//if it was bid then display the new price & change the value in the items page instead.
		else
		{
			$check = "select price from items where itemId = ".$_POST['itemId'];
			$check = $connect->query($check);
			$check = $check->fetch_assoc();
			if($check['price']> $_POST['quantity'])
			{
				echo "Your amount is too small for a bid. Please try again.";
			}
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
					echo "You have bid your amount.<a href = 'customer.php'> Click here </a> to shop more.";
			}
		}
		$connect->close();
	}
?>
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
			echo "<a class='login' href= WelcomePage.php ><span class='glyphicon glyphicon-user' aria-hidden='true'>" . $_SESSION['userName'] . "</span></a>
	        <a class='login' href= 'NLI.php'><span class='glyphicon glyphicon-closed'aria-hidden='true'> | Logout</a>";
		}
	?>
</div>