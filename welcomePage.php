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
				<input type="submit" value="" style="border-style: none; background: url('magnifyingGlass.png') no-repeat; width: 40px; height: 40px; background-size: 100% 100%;">
			</td>
			</tr>
		</table>
	</form>
</div>

<!-- Login tab will only be displayed if there is no user logged in.
	 & for a logged in user there will be a logout option -->
<div style='position: absolute; top : 10px; right: 10px;'>
<?php
	
	if(empty($_SESSION['userType']))
		echo "
			<a class='login' href= 'SignUp.php'>Sign Up | </a>
			<a class='login' href = 'LoginPage.php'>Login</a>";
	else if($_SESSION['userType']=='C')
	{
		echo "<a class='login' href= #>Welcome ".$_SESSION['userName']."</a>";
		echo "<a class='login' href= 'NLI.php'> | Logout</a>";

	}
	else if($_SESSION['userType']=='S') header("location:sellerPage.php");

	
?>
</div>


<!-- This div is for the website name / logo -->
<div style="position: absolute; top: 10px; left: 10px;">
	myStore
</div>


<!-- This div id for the navigational bar-->
<div style="position: relative; top: 70px; left: 50px;">
	<form method="get" action = "search.php">
	<ul>
		<li><button type="submit" name = "cat" value="men"> Men </button></li>
		<li><button type="submit" name = "cat" value="women">Women</button>	</li>
		<li><button type="submit" name = "cat" value="kids">Kids</button></li>
		<li><button type="submit" name = "cat" value="sunglasses">Sunglasses</button></li>
		<li><button type="submit" name = "cat" value="antique">Antiques</button></li>
	</ul>
	</form>
</div>