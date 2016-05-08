<?php
	//This page redirects to itself, so, the database actions will take place if & only if all the parameters are sent.
	if (!empty($_POST['userName']) && !empty($_POST['password']) && !empty($_POST['repassword']) && $_POST['password']==$_POST['repassword'] && !empty($_POST['authority']))
	{
		//connect to the server
		$connect = mysqli_connect("localhost","root","");
		
		//create the database if it does not exists & login ito it
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");

		/*	create table if it does not exists & insert the users
			users (userId int primary key, userName varchar(20), pswd varchar(20), autho char(1))
		*/

		$que = "create table if not exists users ( userId int primary key auto_increment, userName varchar(20), pswd varchar(20), autho char(1));";
		$connect->query($que);

		$que = "insert into users (userName,pswd,autho) values ('".$_POST['userName']."','".$_POST['password']."','".$_POST['authority']."');";
		$connect->query($que);
		$id = "select userId from users where userName = '".$_POST['userName']."' and pswd = '".$_POST['password']."';";
		$res = $connect->query($id);
		$id = $res->fetch_assoc();
		$connect->close(); 
		echo "<script type='text/javascript'> alert(\"Account successfully created\"); </script>";
		session_start();
		$_SESSION['userType'] = $_POST['authority'];
		$_SESSION['userName'] = $_POST['userName'];
		$_SESSION['userId'] = $id['userId'];
		if($_POST['authority'] == 'C')
			header("location:welcomePage.php");
		else if ($_POST['authority'] == 'A')
			header("location:adminPage.php");
		else if ($_POST['authority'] == 'S')
			header("location:sellerPage.php");
	}
?>



<form action = "signUp.php" method = "post">
	<table>
		<tr>
			<td>Enter your user name : </td>
			<td><input name = 'userName' type = 'text' required></td>
		</tr>
		<tr>
			<td>Enter your password : </td>
			<td><input name = 'password' type ='password' required></td>
		</tr>
		<tr>
			<td>Re-enter your password : </td>
			<td><input name = 'repassword' type ='password' required></td>
		</tr>
		<tr>
			<td>Authority level : </td>
			<td>
				<select name = 'authority'>
						<option value="C">Customer</option>
						<option value="S">Seller</option>
						<option value="A">Administrator</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><input type="submit" value="submit" /></td>
		</tr>		
	</table>

</form>