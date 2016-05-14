<head>
<link rel="stylesheet" href="welcomePage.css">
</head>

<?php
	session_start();
	if(!empty($_SESSION['userType']) && $_SESSION['userType'] == 'C' && !empty($_POST['itemId']) && !empty($_POST['quantity']))
	{
		//connect to the database & stuff
		require 'config.php';
		
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