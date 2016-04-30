<!DOCTYPE html>
<!-- These css maybe shifted onto another page for convieniece-->
<style type="text/css">
	a.login
{
	position:relative;
	top:10px; right:10px;
	font-size:20px;
	text-decoration:none;
	color:brown;
}

a.logout:in
{
	background: yellow;
	color: green;
}

</style>

<!-- for the search bar -->
<div style="position: absolute; top : 10px; right:200px;">
	<form method="get" action=""> 
		<table cellpadding="0px" cellspacing="0px"> 
			<tr> 
			<td style="border-style:solid none solid solid;border-color:#4B7B9F;border-width:1px;">
				<input type="text" name="zoom_query" style="width:400px; border:0px solid; height:40px; padding:0px 3px; position:relative; font-size: 25px;"> 
			</td>
			<td style="border-style:solid;border-color:#4B7B9F;border-width:1px;"> 
				<input type="submit" value="" style="border-style: none; background: url('magnifyingGlass.png') no-repeat; width: 40px; height: 40px; background-size: 100% 100%;">
			</td>
			</tr>
		</table>
	</form>
</div>

<!-- Login tab -->
<div style="position: absolute; top : 10px; right: 10px;">
	<a class="login" href= "SignUp.php">Sign Up | </a>

	<a class="login" href = "LoginPage.php">Login</a>
</div>

<!-- This div is for the website name / logo -->
<div style="position: absolute; top: 10px; left: 10px;">
	myStore
</div>