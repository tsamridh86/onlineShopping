<?php
if (!empty($_POST['userName']) && !empty($_POST['password']))
{
	//connect to the database

	//find whether the user is actually there or not

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
			<td><input type = "text" name = "userName"></td>
		</tr>
		<tr>
			<td><input type="submit" value="submit" /></td>
		</tr>
	</table>
</form>