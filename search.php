<head>
<link rel="stylesheet" href="welcomePage.css">
<style>
 p {font-family: sans-serif; }
 p {font-weight: bold; }

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
	the cat is used when the user uses the navigational bar instead, it is given a different name than ategory because it can be confused with the radio button that is available in this page
	*/
	//connect to the database & all the things we have been thru lol
	require 'config.php';
	

	//searching part here
	$que = "select * from items where status = 'Y' and ";
	if(!empty($_GET['query']))
		$que = $que."itemName like '%".$_GET['query']."%' and ";
	if(!empty($_GET['shape']))
		$que = $que."shape = '".$_GET['shape']."' and ";
	if(!empty($_GET['color']))
		$que = $que."color = '".$_GET['color']."' and ";
	if(!empty($_GET['category1']))
		$que = $que."category like '".$_GET['category1']."%' and ";
	if(!empty($_GET['category2']))
		$que = $que."category like '%".$_GET['category2']."' and ";
	if(!empty($_GET['cat']))
		$que = $que."category = '".$_GET['cat']."' and ";
	if(!empty($_GET['seller']))
		$que = $que."sellerid = ".$_GET['seller']." and ";
	if(!empty($_GET['type']))
		$que = $que."type = '".$_GET['type']."' and ";

	// Now that the query has been made, wrench off the unnessecary and that is hanging behind, note that there are 4 characters , and  the space, use substr to wrench it off
	$que = substr($que,0,-4);

	//add the semicolon bro
	$que = $que.";";
	$result = $connect->query($que);

	echo "	<form method = get action = 'search.php'>
			<div style ='position:relative; top : 30px; left : 10px; border: 2px solid black; margin : 10px; width:270px; height : 80%; overflow:auto; padding : 3px;'>
			Enter the shape of the item.<br><br>
		";

	/*
		You may think the query maybe optimized as select shape, color, category from items;
		but the reading pointer cannot be reset, so bad luck there honey,
		execute the statement thrice instead. or if you're better than me :P

	*/
	// This is to show the shape of items
	$sidebar = "select distinct shape from items where status = 'Y';";
	$sidebar = $connect->query($sidebar);	
	while($prop = $sidebar->fetch_assoc())
			echo "<input name = 'shape' type = 'radio' value = ".$prop['shape'].">".$prop['shape']."<br><br>";
	

	//color of items
	echo "	Enter the color of the item:<br><br>";
	$sidebar = "select distinct color from items where status = 'Y';";
	$sidebar = $connect->query($sidebar);
	while($prop = $sidebar->fetch_assoc())
			echo "<input name = 'color' type = 'radio' value = ".$prop['color'].">".$prop['color']."<br><br>";
	
	//category1 of item
	echo "	Enter the category of the item:<br><br>";
	echo "<input type = 'radio' name = 'category1' value = 'men'>Men<br><br>";
	echo "<input type = 'radio' name = 'category1' value = 'women'>Women<br><br>";
	echo "<input type = 'radio' name = 'category1' value = 'kids'>Kids<br><br>";
	
	//category2 of item
	echo " Enter the type of item: <br><br>";
	echo "<input type = 'radio' name = 'category2' value = 'sunglasses'>Sunglasses<br><br>";
	echo "<input type = 'radio' name = 'category2' value = 'eyeglasses'>Eyeglasses<br><br>";

	//search by seller
	echo "Enter the seller you want to buy from: <br><br>";	
	$sidebar = "select distinct items.sellerid as sellid, users.userName as uname from items inner join users on users.userId = items.sellerId where items.status = 'Y'";
	$sidebar = $connect->query($sidebar);
	while ($prop = $sidebar->fetch_assoc())
		echo "<input name = 'seller' type = 'radio' value = ".$prop['sellid'].">".$prop['uname']."<br><br>";
	
	//search if the item is being sold or on bid
	echo "Search for items on sale or bid<br><br>";
	echo "<input name = 'type' type = 'radio' value = 'B'>Bidding Only<br><br>";
	echo "<input name = 'type' type = 'radio' value = 'S'>For Sale<br><br>";

	//final input button
	echo "
			<input type = submit value = submit>
			</div>
			</form>";		

			
	//for displaying search results		
	echo "<div style='overflow : auto; position: absolute; top: 50px; left: 330px; height: 80%; width: 70%;'>";
	//a form is needed to change pages with the data intact
	echo "<form method = get action = 'orderPage.php'>";
	while($row = $result->fetch_assoc())
	{
		$locs = $row['imgLoc'];		//since the array name has '' the things got complex
		
		//category seperation tarika
		$len = 0;
		while($row['category'][$len++]!="_");
		$cat1 = substr($row['category'],0,$len-1);
		$cat2 = substr($row['category'],$len);
		
		//we only have the seller id of the person, this query is used to display it's actual name.
		$que = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($que);
		$sellerName = $sellerName->fetch_assoc();
		//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
		
		echo "<div style='position: relative; height : 330px; width : 70%; top: 30px; left : 10px; border:2px solid black; margin: 10px; '>";
		echo "<div style='position: absolute; top: 10px; left: 10px;'>";
		echo "<img src = '$locs' height = 220 px width = 220px align = left>";
		echo "</div>";
		echo "<div style='position: absolute; top: 10px; left: 270px;'>";
		echo "<p> Sold By  : ".$sellerName['userName']."</p>";
		echo "<p> Color : ".$row['color']."</p>";
		echo "<p> Shape : ".$row['shape']."</p>";
		echo "<p> Item Name : ".$row['brand']." ".$row['itemName']."</p>";
		echo "<p> Price  : ".$row['price']."</p>";
		echo "<p> Category : ".$cat1."</p>";
		echo "<p> Category : ".$cat2."</p>";
		if($row['type']=='B')
		{
			$que = "select userName from items inner join users on items.custId = users.userId where itemId = ".$row['itemId'].";";
			$custName = $connect->query($que);
			$custName = $custName->fetch_assoc();
			echo "<p> Latest Bidder : ".$custName['userName']."</p>";
		}
		if($row['type']=='S')
		echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
		else
			echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Bid</button>";
		echo "</div>";
		echo "</div>";
	}
	echo "</form>";	//just incase you were wondering which form has to be closed, I opened one before this loop
	echo "</div>";
	$connect->close();

?>