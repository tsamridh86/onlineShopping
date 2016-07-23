<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<!-- Latest compiled and minifiedJavaScript -->
<script src="jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!DOCTYPE html>
<head>
	<style type="text/css">
		.list{
			border:none;
			background: none;
		}
	</style>
</head>
<div style="position: absolute; top : -20px; left:10px;">
	<img src="logo.jpg" class="img-circle" alt="cinque Terre" width="150" height="80">
</div>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<div class="container-fluid">
	<div class="row" style="padding-top:5px;">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<form method="get" action="search.php">
				<div class="input-group" style="padding:4px;">
					<input type="text" class="form-control" name = 'query' placeholder="Search for...">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="submit">
						<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>
					</span>
				</div>
			</form>
		</div>
<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 pull-right">
			<?php
			session_start();
			if (empty($_SESSION['userType']))
			header("location:welcomePage.php");
			else if ($_SESSION['userType'] == 'S')
			header("location:sellerPage.php");
			else {
			echo "<a class='login' href= WelcomePage.php ><span class='glyphicon glyphicon-user' aria-hidden='true'>" . $_SESSION['userName'] . "</span></a>
			<a class='login' href= 'NLI.php'><span class='glyphicon glyphicon-closed'aria-hidden='true'> | Logout</a>";
			}
			?>
		</div>
	</div>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<form method="get" action = "search.php">
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Men <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a><button type = "submit" class="list" name = 'cat' value = "men_sunglasses">Sun Glasses</button></a></li>
							<li><a href="#"><button type = "submit" class="list" name = 'cat' value = "men_eyeglasses">Eye Glasses</button></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Women <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#"><button type = "submit" class="list" name = 'cat' value = "women_sunglasses">Sun Glasses</button></a></li>
							<li><a href="#"><button type = "submit" class="list" name = 'cat' value = "men_sunglasses">Eye Glasses</button></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kids <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#"><button type = "submit" class="list" name = 'cat' value = "kids_sunglasses">Sun Glasses</button></a></li>
							<li><a href="#"><button type = "submit" class="list" name = 'cat' value = "kids_sunglasses">Eye Glasses</button></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sale Type <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#"><button type = "submit" class="list" name = 'type' value = "S"> For Sale Only</button></a></li>
							<li><a href="#"><button type = "submit" class="list" name = 'type' value = "B">For Bidding Only</button></a></li>
						</ul>
					</li>
				</ul>
			</form>
		</div>
	</nav>
	<div style="container">
	<img src="1.jpg" class="img-thumbnail" alt="Cinque Terre" width="1200" height="236">
</div>

	<div class="row" style="margin-bottom:20px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class='row'>
				<blockquote>
					<h4 class="heading"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span> Now Trending:</h4>
				<footer>Shop this week's most popular</footer>
			</blockquote>
			<?php
			//connect to the database & stuff
			require 'config.php';
			//immediately update the items on the database and disable the ones that should not be here
			$que = "select * from items where type = 'B' and status = 'Y';";
			$res = $connect->query($que);
			while ($row = $res->fetch_assoc()) {
			if ($row['deadLine'] <= $_SERVER['REQUEST_TIME']) {
			$ups = "update items set status = 'D' where itemId = " . $row['itemId'];
			$connect->query($ups);
			$ups = "insert into orders(custId, itemId, quantity) values (" . $row['custId'] . "," . $row['itemId'] . ",1);";
			$connect->query($ups);
			}
			}
			$que    = "select * from items natural join (select itemId , sum(quantity) as q from orders group by itemId)x where status = 'Y' order by q desc;";
			$result = $connect->query($que);
			$count  = 0;
			echo "<form method = get action = 'orderPage.php'>";
					while ($row = $result->fetch_assoc() and $count < 2) {
					$count = $count + 1;
					
					$locs = $row['imgLoc']; //since the array name has '' the things got complex
					
					//category seperation tarika
					$len = 0;
					while ($row['category'][$len++] != "_");
					$cat1 = substr($row['category'], 0, $len - 1);
					$cat2 = substr($row['category'], $len);
					
					//we only have the seller id of the person, this query is used to display it's actual name.
					$sellerName = "select userName from users where userId = " . $row['sellerId'] . ";";
					$sellerName = $connect->query($sellerName);
					$sellerName = $sellerName->fetch_assoc();
					//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
					echo " <div class='col-xs-5 col-sm-5 col-md-5 col-lg-5' style=' height : 330px; top: 10px; left : 1px; border:2px solid grey; margin: 10px; box-shadow: 10px 10px 5px #888888;'>";
							echo "<div class = 'row' ><div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'style='top: 10px; left: 10px;'>";
									echo "<img src = '$locs' height = 220 px width = 220px align = left>";
							echo "</div>";
							echo "<div class = 'col-xs-6 col-sm-6 col-md-6 col-lg-6' style='position: absolute; top: 10px; left: 270px;'>";
									echo "<p> Sold By  : " . $sellerName['userName'] . "</p>";
									echo "<p> Color : " . $row['color'] . "</p>";
									echo "<p> Shape : " . $row['shape'] . "</p>";
									echo "<p> Item Name : " . $row['brand'] . " " . $row['itemName'] . "</p>";
									echo "<p> Price  : " . $row['price'] . "</p>";
									echo "<p> Category : " . $cat1 . "</p>";
									echo "<p> Category : " . $cat2 . "</p>";
									echo "<button type = submit class='btn btn-primary' name = 'itemId' value =" . $row['itemId'] . ">Order</button>";
							echo "</div></div>";
					echo " </div>";
					
					}
			echo "</form>";
			?>
		</div>
	</div>
</div>
<div class='row' style="margin-bottom:20px;">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<blockquote>
			<h4 class="heading"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> Biddings:</h4>
		<footer>Limited offer</footer>
		<div class='row'>
		</blockquote>
		<?php
		$que    = "select * from items where type = 'B' and status = 'Y' ;";
		$result = $connect->query($que);
		$count  = 0;
		echo "<form method = get action = 'orderPage.php'>";
				while ($row = $result->fetch_assoc() and $count < 2) {
				$count = $count + 1;
				$locs  = $row['imgLoc']; //since the array name has '' the things got complex
				
				//category seperation tarika
				$len = 0;
				while ($row['category'][$len++] != "_");
				$cat1 = substr($row['category'], 0, $len - 1);
				$cat2 = substr($row['category'], $len);
				
				//we only have the seller id of the person, this query is used to display it's actual name.
				$sellerName = "select userName from users where userId = " . $row['sellerId'] . ";";
				$sellerName = $connect->query($sellerName);
				$sellerName = $sellerName->fetch_assoc();
				//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
				
				echo " <div class='col-xs-5 col-sm-5 col-md-5 col-lg-5' style=' height : 380px; top: 10px; left : 1px; border:2px solid grey; margin: 10px; box-shadow: 10px 10px 5px #888888; '>";
						echo "<div class = 'row' ><div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'style='top: 10px; left: 10px;'>";
								echo "<img src = '$locs' height = 220 px width = 220px align = left>";
						echo "</div>";
						echo "<div class = 'col-xs-6 col-sm-6 col-md-6 col-lg-6' style='position: absolute; top: 10px; left: 270px;'>";
								echo "<p> Sold By  : " . $sellerName['userName'] . "</p>";
								echo "<p> Color : " . $row['color'] . "</p>";
								echo "<p> Shape : " . $row['shape'] . "</p>";
								echo "<p> Item Name : " . $row['brand'] . " " . $row['itemName'] . "</p>";
								echo "<p> Price  : " . $row['price'] . "</p>";
								echo "<p> Category : " . $cat1 . "</p>";
								echo "<p> Category : " . $cat2 . "</p>";
								$que      = "select userName from items inner join users on items.custId = users.userId where itemId = " . $row['itemId'] . ";";
								$custName = $connect->query($que);
								$custName = $custName->fetch_assoc();
								echo "<p> Latest Bidder : " . $custName['userName'] . "</p>";
								echo "<p> Time remaining : " . calcTime($row['deadLine']) . "</p>";
								echo "<button type = submit class='btn btn-primary' name = 'itemId' value =" . $row['itemId'] . ">Bid</button>";
						echo "</div></div>";
				echo " </div>";
				
				}
		echo "</form>";
		
		?>
	</div>
	</div>
<div class='row' style="margin-bottom:20px;">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<blockquote>
			<h4 class="heading"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> Latest Arrival:</h4>
		<footer>Just came in!</footer>
		<div class='row'>
		</blockquote>
		<?php
			$que    = "select * from items where status = 'Y' and type = 'S' order by itemId desc;";
			$result = $connect->query($que);
			$count  = 0;
			echo "<form method = get action = 'orderPage.php'>";
			while ($row = $result->fetch_assoc() and $count < 2) {
			$count = $count + 1;
			$locs = $row['imgLoc']; //since the array name has '' the things got complex
					
			//category seperation tarika
			$len = 0;
			while ($row['category'][$len++] != "_");
			$cat1 = substr($row['category'], 0, $len - 1);
			$cat2 = substr($row['category'], $len);
					
			//we only have the seller id of the person, this query is used to display it's actual name.
			$sellerName = "select userName from users where userId = " . $row['sellerId'] . ";";
			$sellerName = $connect->query($sellerName);
			$sellerName = $sellerName->fetch_assoc();
			//this is to properly display inside a division for every item, (because this thing is in a for loop everyting is printed accordingly)
			echo " <div class='col-xs-5 col-sm-5 col-md-5 col-lg-5' style=' height : 330px; top: 10px; left : 1px; border:2px solid grey; margin: 10px; box-shadow: 10px 10px 5px #888888;'>";
			echo "<div class = 'row' ><div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'style='top: 10px; left: 10px;'>";
			echo "<img src = '$locs' height = 220 px width = 220px align = left>";
			echo "</div>";
			echo "<div class = 'col-xs-6 col-sm-6 col-md-6 col-lg-6' style='position: absolute; top: 10px; left: 270px;'>";
			echo "<p> Sold By  : " . $sellerName['userName'] . "</p>";
			echo "<p> Color : " . $row['color'] . "</p>";
			echo "<p> Shape : " . $row['shape'] . "</p>";
			echo "<p> Item Name : " . $row['brand'] . " " . $row['itemName'] . "</p>";
			echo "<p> Price  : " . $row['price'] . "</p>";
			echo "<p> Category : " . $cat1 . "</p>";
			echo "<p> Category : " . $cat2 . "</p>";
			echo "<button type = submit class='btn btn-primary' name = 'itemId' value =" . $row['itemId'] . ">Order</button>";
			echo "</div></div>";
			echo " </div>";
		}
		echo "</form>";
		
		?>
	</div>
	</div>
<div class='row' style="margin-bottom:20px;">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<blockquote>
	<h4 class="heading"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Details:</h4>
		<footer>This belongs to you!</footer>
		</blockquote>
	<?php

$que="select * from orders where custId=".$_SESSION['userId'];
$result = $connect->query($que);
		
		echo "
				
				<table class='table table-bordered'>
					<tr>
						<td>Order Id</td>
				
						<td>Item Name</td>
						<td>Quantity</td>
						<td>Price</d>
						<td>Total</d>
					</tr>
				";

		while($row = $result->fetch_assoc())
		{
			$itemDetails = "select * from items where itemId = ".$row['itemId'];
			$itemDetails = $connect->query($itemDetails);
			$itemDetails = $itemDetails->fetch_assoc();
			echo "	<tr>
						<td>".$row['orderId']."</td>
						<td>".$itemDetails['brand']." ".$itemDetails['itemName']."</td>
						<td>".$row['quantity']."</td>
						<td>".$itemDetails['price']."</td>
						<td>".$row['quantity']*$itemDetails['price']."
						</tr>	";
		}
		echo "</table>";
		$connect->close();
?>
</div>
</div>