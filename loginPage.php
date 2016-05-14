<?php
if (!empty($_POST['userName']) && !empty($_POST['password']))
{
	
	require 'config.php';

	//find whether the user is actually there or not
	$que = "select userId , userName , autho from users where userName = '".$_POST['userName']."' and pswd = '".$_POST['password']."';";
	$result = $connect->query($que);
	$res = $result->fetch_assoc();
	if($res['autho'] == 'A')	//admin is da boss of the page, he shall have the might to remove other users & items.
	{
		session_start();
		$_SESSION['userType'] = 'A';
		$_SESSION['userName'] = $res['userName'];
		$_SESSION['userId'] = $res['userId'];
		header("location:adminPage.php");
	}
	else if ($res['autho'] == 'C')	//customers having the least power of them all, can only order the items.
	{
		session_start();
		$_SESSION['userType'] = 'C';
		$_SESSION['userName'] = $res['userName'];
		$_SESSION['userId'] = $res['userId'];
		header("location:welcomePage.php");
	}
	else if ($res['autho'] == 'S')	//sellers will have the ability to add & remove items, that belong to them.
	{
		session_start();
		$_SESSION['userType'] = 'S';
		$_SESSION['userName'] = $res['userName'];
		$_SESSION['userId'] = $res['userId'];
		header("location:sellerPage.php");
	}
	else
	{
		echo "Sorry, you are not a registered user. <a href = 'signUp.php'>Click here</a> to sign up & join us. :D";
	}
}
?>


<form action = "loginPage.php" method = "post">
	<table>
		<tr>
			<td>Enter your User Name : </td>
			<td><input type = "text" name = "userName"></td>
		</tr>
		<tr>
			<td>Enter your Password : </td>
			<td><input type = "password" name = "password"></td>
		</tr>
		<tr>
			<td><input type="submit" value="Log In" /></td>
			<td><a href="welcomePage.php">Cancel</a></td>
		</tr>
	</table>
</form>