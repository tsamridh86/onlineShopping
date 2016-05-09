<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="welcomePage.css">
</head>

<!-- This is for the current user that is logged in-->
<?php
	session_start();
?>

<!-- for the search bar -->
<div style="position: absolute; top : 10px; right:400px;">
	<form method="get" action="search.php"> 
		<table cellpadding="0px" cellspacing="0px"> 
			<tr> 
			<td style="border-style:solid none solid solid;border-color:#4B7B9F;border-width:1px;">
				<input type="text" name="query" style="width:800px; border:0px solid; height:40px; padding:0px 3px; position:relative; font-size: 25px;">
			</td>
			<td style="border-style:solid;border-color:#4B7B9F;border-width:1px;"> 
				<input type="submit" value="submit" style="border-style: none; background: url('magnifyingGlass.png') no-repeat; width: 40px; height: 40px; background-size: 100% 100%;">
			</td>
			</tr>
		</table>
	</form>
</div>

<!-- Login tab will only be displayed if there is no user logged in.
	 & for a logged in user there will be a logout option -->
<div style='position: absolute; top : 10px; right: 10px;'>
<?php
	error_reporting(0);
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


<!-- This div is for the website name / logo -->
<div style="position: absolute; top: 10px; left: 10px;">
	myStore
</div>


<!-- This div id for the navigational bar-->
<div style="position: relative; top: 70px; left: 50px;">
	<ul>
		<li><a href="#">Men</a></li>
		<li><a href="#">Women</a>	</li>
		<li><a href="#">Electronics</a></li>
		<li><a href="#">Books & Media</a></li>
		<li><a href="#">Home & Furniture</a></li>
	</ul>
</div>