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
<div style="position: absolute; top : 60px; right:1px;">
	<input type="submit" value="" style="border-style: none; background: url('pic.jpg') no-repeat; width: 1495px; height: 300px; background-size: 100% 100%;">
</div>
<!-- Login tab will only be displayed if there is no user logged in.
& for a logged in user there will be a logout option -->
<div style='position: absolute; top : 10px; right: 10px;'>
	<?php
		
		if(empty($_SESSION['userType']))
			echo "
				<a class='login' href= 'SignUp.php'>Sign Up | </a>
				<a class='login' href = 'LoginPage.php'>Login</a>";
		else if($_SESSION['userType']=='S') header("location:sellerPage.php");
		else
		{
			echo "<a class='login' href= #>Welcome ".$_SESSION['userName']."</a>
			<a class='login' href= 'NLI.php'> | Logout</a>";
		}
		
		
	?>
</div>
<!-- This div is for the website name / logo -->
<div style="position: absolute; top: 10px; left: 10px;">
	myStore
</div>
<!-- This div id for the navigational bar-->
<div style="position: relative; top: 354px; left: 80px; z-index:2;">
	<form method="get" action = "search.php">
		<ul>
			<li>
				<a href="#">Men</a>
				<ul class="hidden">
					<li><button type = "submit" name = 'cat' value = "men_sunglasses">Sunglasses</button></li>
					<li><button type = "submit" name = 'cat' value = "women_eyeglasses">Eyeglasses</a></li>
				</ul>
			</li>
			<li>
				<a href="#">Women</a>
				<ul class="hidden">
					<li><button type = "submit" name = 'cat' value = "women_sunglasses">Sunglasses</button></li>
					<li><button type = "submit" name = 'cat' value = "women_eyeglasses">Eyeglasses</button></li>
				</ul>
			</li>
			<li>
				<a href="#">Kids</a>
				<ul class="hidden">
					<li><button type = "submit" name = 'cat' value = "kids_sunglasses">Sunglasses</button></li>
					<li><button type = "submit" name = 'cat' value = "kids_eyeglasses">Eyeglasses</button></li>
				</ul>
			</li>
			<li><a href="#">Sale Type</a>
			<ul class="hidden">
				<li><button type = "submit" name = 'type' value = "S">For Sale only</button></li>
				<li><button type = "submit" name = 'type' value = "B">For Bidding only</button></li>
			</ul>
		</li>
	</ul>
</form>
</div>
<div style='position: relative; top : 420px; left:10px; width: 40%;'>
<input type="submit" value="" style="border-style: none; background: url('trend.jpg') no-repeat; width: 450px; height: 120px; background-size: 100%;">
<?php
	//connect to the database & stuff
	require 'config.php';
	/*
		Since, now trending item is the item that has been ordered maximum times by the customers,
		the query select itemId , sum(quantity) as q from orders group by itemId,
		returns the itemId and sum of it's quantity that has been ordered, hence,
		group by has to be used, otherwise sql will blindly sum up quantity from the entire table,
		group by itemId will sum up quantity from the orders table having the same item id,
		this is nested inside next query
		select * from items natural join (select itemId , sum(quantity) as q from orders group by itemId)x order by q desc;
		this will join the table w.r.t. the itemId, & since it is ordered in descending order , the mosr ordered item will show up at the top
		we use a simple counter in for loop, to display 3 top items only.
		Here there is the need to worry about the deactivated items because they are now inserted into the orders table as well as the items table, so they will appear in the natural join, so we need to filter them out.
		So, the above query needs ti be extended a little bit,
		select * from items natural join (select itemId , sum(quantity) as q from orders group by itemId)x  where status = 'Y' order by q desc;
	*/
	$que = "select * from items natural join (select itemId , sum(quantity) as q from orders group by itemId)x where status = 'Y' order by q desc;";
	$result = $connect->query($que);
	$count = 0;
	echo "<form method = get action = 'orderPage.php'>";
		while ($row = $result->fetch_assoc() and $count < 3)
		{
			$count = $count + 1;
			
				$locs = $row['imgLoc'];		//since the array name has '' the things got complex
		
		//category seperation tarika
		$len = 0;
		while($row['category'][$len++]!="_");
		$cat1 = substr($row['category'],0,$len-1);
		$cat2 = substr($row['category'],$len);
		
		//we only have the seller id of the person, this query is used to display it's actual name.
		$sellerName = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($sellerName);
		$sellerName = $sellerName->fetch_assoc();
		//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
		
		echo "<div style='position: relative; height : 300px; width : 100%; top: 10px; left : 1px; border:2px solid grey; margin: 10px; '>";
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
				echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Order</button>";
			echo "</div>";
		echo "</div>";
			
		}
	echo "</form>";
	$connect->close();
?>
</div>
<div style='position: relative; top: -320px; right:-50%; width: 40%;'>
<h1> biddings </h1>

<?php
	//connect to the database & stuff
	require 'config.php';
	
	$que = "select * from items where custId is not null or type = 'B' and status = 'Y' ;";
	$result = $connect->query($que);
	$count = 0;
	echo "<form method = get action = 'orderPage.php'>";
		while ($row = $result->fetch_assoc() and $count < 3)
		{
			$count = $count + 1;
			
				$locs = $row['imgLoc'];		//since the array name has '' the things got complex
		
		//category seperation tarika
		$len = 0;
		while($row['category'][$len++]!="_");
		$cat1 = substr($row['category'],0,$len-1);
		$cat2 = substr($row['category'],$len);
		
		//we only have the seller id of the person, this query is used to display it's actual name.
		$sellerName = "select userName from users where userId = ".$row['sellerId'].";";
		$sellerName = $connect->query($sellerName);
		$sellerName = $sellerName->fetch_assoc();
		//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
		
		echo "<div style='position: relative; height : 330px; width : 100%; top: 10px; left : 1px; border:2px solid grey; margin: 10px; '>";
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
				$que = "select userName from items inner join users on items.custId = users.userId where itemId = ".$row['itemId'].";";
					$custName = $connect->query($que);
					$custName = $custName->fetch_assoc();
					echo "<p> Latest Bidder : ".$custName['userName']."</p>";
				echo "<button type = submit name = 'itemId' value =".$row['itemId'].">Bid</button>";
			echo "</div>";
		echo "</div>";
			
		}
	echo "</form>";
	$connect->close();
?>
</div>