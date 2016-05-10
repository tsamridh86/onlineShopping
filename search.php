<head>
<style>
 p {font-family: sans-serif; }
</style>
</head>


<div style='position: absolute; top : 10px; left: 10px; z-index: 1;'>
	Is this what you were looking for?
</div>


<!-- Login tab will only be displayed if there is no user logged in.
	 & for a logged in user there will be a logout option -->
<div style='position: absolute; top : 10px; right: 10px;z-index: 1;'>
<?php
	session_start();
	if(empty($_SESSION['userType']))
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

<?php
	/*
	The user has entered the item name that s/he requires, so search for it & display them accordingly
	*/
if (!empty($_GET['query']))
	{
	$item = $_GET['query'];
	//connect to the database & all the things we have been thru lol
	$connect = mysqli_connect("localhost","root","");
	$dbstart = "create database if not exists shops;";
	$connect->query($dbstart);
	mysqli_select_db($connect , "shops");
	$que = "create table if not exists items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , price int , imgLoc varchar(50),category varchar(25));";
	$connect->query($que);
	


	$que = "select * from items where itemName like  '%".$item."%'";
	if(!empty($_GET['shape']))
		$que = $que."and shape = '".$_GET['shape']."'";
	$result = $connect->query($que);

	echo "	<form method = get action = 'search.php'>
			<div style ='position:relative; top : 30px; left : 10px; border: 2px solid black; margin : 10px; width:270px; '>
			<input name = 'query' type = hidden value = ".$item.">
			Enter the category of the item.<br><br>
		";

	/*
		You may think the query maybe optimized as select shape, color, category from items;
		but the reading pointer cannot be reset, so bad luck there honey,
		execute the statement thrice instead. or if you're better than me :P

	*/
	// This is to show the shape of items
	$sidebar = "select shape from items;";
	$sidebar = $connect->query($sidebar);	
	while($prop = $sidebar->fetch_assoc())
			echo "<input name = 'shape' type = 'radio' value = ".$prop['shape'].">".$prop['shape']."<br><br>";
	echo "	Enter the color of the item:<br><br>";

	//color of items
	$sidebar = "select color from items;";
	$sidebar = $connect->query($sidebar);
	while($prop = $sidebar->fetch_assoc())
			echo "<input name = 'color' type = 'radio' value = ".$prop['color'].">".$prop['color']."<br><br>";
	echo "	Enter the category of the item:<br><br>";

	//category of item
	$sidebar = "select category from items;";
	$sidebar = $connect->query($sidebar);
	while($prop = $sidebar->fetch_assoc())
			echo "<input name = 'category' type = 'radio' value = ".$prop['category'].">".$prop['category']."<br><br>";	
	echo "
			<input type = submit value = submit>
			</div>
			</form>";		

	//a form is needed to change pages with the data intact
	echo "<form method = get action = 'orderPage.php'>";
	while($row = $result->fetch_assoc())
	{
		$locs = $row['imgLoc'];		//since the array name has '' the things got complex
		
		//we only have the seller id of the person, this query is used to display it's actual name.
		$que = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($que);
		$sellerName = $sellerName->fetch_assoc();
		//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
		echo "<div style='scrollable : yes; position: absolute; top: 30px; left: 300px; height: 80%; width: 70%;'>";
		echo "<div style='position: relative; height : 270px; width : 50%; top: 30px; left : 10px; border:2px solid black; margin: 10px; '>";
		echo "<div style='position: absolute; top: 10px; left: 10px;'>";
		echo "<img src = '$locs' height = 220 px width = 220px align = left>";
		echo "</div>";
		echo "<div style='position: absolute; top: 10px; left: 270px;'>";
		echo "<p> Sold By  : ".$sellerName['userName']."</p>";
		echo "<p> Color : ".$row['color']."</p>";
		echo "<p> Shape : ".$row['shape']."</p>";
		echo "<p> Item Name : ".$row['itemName']."</p>";
		echo "<p> Price  : ".$row['price']."</p>";
		echo "<p> Category : ".$row['category']."</p>";
		echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
		echo "</div>";
		echo "</div>";
		echo "</div>";

	}
	echo "</form>";	//just incase you were wondering the form has to be closed, I opened one before this loop
	$connect->close();
}	
?>