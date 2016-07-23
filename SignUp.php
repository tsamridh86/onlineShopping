<!DOCTYPE html>
<html lang="en">
<head>
  <title>SignUp</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<?php
	if (!empty($_POST['userName']) && !empty($_POST['password'])  && !empty($_POST['authority']))
	{
		require 'config.php';
		$test = "select userName from users";
		$allUsers = $connect->query($test);
		$flag = 0;
		while($test = $allUsers->fetch_assoc())
		{
			if($test['userName']==$_POST['userName']) $flag = 1;
		}
		if ($flag) echo "This User Name already exists, please choose another one.";
		else
		{
			$que = "insert into users (userName,pswd,autho) values ('".$_POST['userName']."','".$_POST['password']."','".$_POST['authority']."');";
			$connect->query($que);
			$id = "select userId from users where userName = '".$_POST['userName']."' and pswd = '".$_POST['password']."';";
			$res = $connect->query($id);
			$id = $res->fetch_assoc();
			session_start();
			$_SESSION['userType'] = $_POST['authority'];
			$_SESSION['userName'] = $_POST['userName'];
			$_SESSION['userId'] = $id['userId'];
			if($_POST['authority'] == 'C')
				header("location:customer.php");
			else if ($_POST['authority'] == 'A')
				header("location:adminPage.php");
			else if ($_POST['authority'] == 'S')
				header("location:sellerPage.php");
		}
		$connect->close();
	}
?>

<div class="container">
  <form method = "post" action = signUp.php>
 
    <div class="form-group">
      <label for="userName">Username:</label>
      <input type="text" class="form-control" name="userName" placeholder="Enter name">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" name="password" placeholder="Enter password">
	  <p class="warning">The password must be 1-25 characters long.</p>
    </div>
	
	<td>Authority level : </td>
			<td>
				<select name = 'authority'>
					<option value="C">Customer</option>
					<option value="S">Seller</option>
					<option value="A">Administrator</option>
				</select>
			</td>
    <div class="checkbox">
      <label><input type="checkbox"> Remember me</label>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
	
			<td><a href="welcomePage.php">Cancel</a></td>
		</tr>
  </form>
</div>
</html>

