<?php
if (!empty($_POST['userName']) && !empty($_POST['password']))
{
	//connect to the database & check whether as table exists for users
	//some one may try login without the execution of the user table existence.
	$connect = mysqli_connect("localhost","root","");
	$dbstart = "create database if not exists shops;";
	$connect->query($dbstart);
	mysqli_select_db($connect , "shops");
	$que = "create table if not exists users ( userId int primary key auto_increment, userName varchar(20), pswd varchar(20), autho char(1));";
	$connect->query($que);
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
		header("location:IVL.php");
	}
}
?>


<form action = "loginPage.php" method = "post">
	<table>
		<tr>
			<td>Enter your userName : </td>
			<td><input type = "text" name = "userName"></td>
		</tr>
		<tr>
			<td>Enter your password : </td>
			<td><input type = "password" name = "password"></td>
		</tr>
		<tr>
			<td><input type="submit" value="submit" /></td>
		</tr>
	</table>
</form>