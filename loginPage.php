<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<!-- Latest compiled and minified
JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<?php
if (!empty($_POST['userName']) && !empty($_POST['password']))
{
	
	require 'config.php';

	//find whether the user is actually there or not
	$que = "select userId , userName , autho from users where userName = '".$_POST['userName']."' and pswd = '".$_POST['password']."';";
	$result = $connect->query($que);
	$res = $result->fetch_assoc();
	if($res['autho'] == 'A')	
	{
		session_start();
		$_SESSION['userType'] = 'A';
		$_SESSION['userName'] = $res['userName'];
		$_SESSION['userId'] = $res['userId'];
		header("location:adminPage.php");
	}
	else if ($res['autho'] == 'C')	
	{
		session_start();
		$_SESSION['userType'] = 'C';
		$_SESSION['userName'] = $res['userName'];
		$_SESSION['userId'] = $res['userId'];
		header("location:customer.php");
	}
	else if ($res['autho'] == 'S')	
	{
		session_start();
		$_SESSION['userType'] = 'S';
		$_SESSION['userName'] = $res['userName'];
		$_SESSION['userId'] = $res['userId'];
		header("location:sellerPage.php");
	}
	else
	{   
 
		echo "Sorry, you are not a registered user. <a href = 'signUp.php'>Click here</a> to sign up & join us. :)";
	}
}
?>


<div class="container">
  <form method = "post" action = loginPage.php>
    <div class="form-group">
	<label for="userName">Username:</label>
      <input type="text" class="form-control" name="userName" placeholder="Enter name">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" name="password" placeholder="Enter password">
	  <p class="warning">The password must be 1-25 characters long.</p>
    </div>
	
    <div class="checkbox">
      <label><input type="checkbox"> Remember me</label>
    </div>
    <button type="submit" class="btn btn-default" >Login</button
			
  </form>
</div>
</html>

