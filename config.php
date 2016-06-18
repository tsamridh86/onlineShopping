<?php
	/*
		The contents of this page are very important for the execution of the code in the other pages
		a require command has been used in the nessecary pages to import this data if required.
	*/

	//This portion contains the function to calculate the time remaining and display the correct format
	//The parameter $date is assumed to be in timestamp format that is the time is completely displayed in seconds.
	function calcTime ($deadLine)
	{
		$currentDate  = $_SERVER['REQUEST_TIME'];
		$timeRemaining = $deadLine - $currentDate;
		if($timeRemaining <= 0) return 0;
		$dum = $timeRemaining / (60*60*24*365);	//converted into years
		$yrs = floor($dum);						//removed the decimal part if any
		$dum = (($dum - $yrs)*365);				//converted into days
		$days = floor($dum);					//removed the decimal part if any
		$dum = (($dum-$days)*24)				//converted into hrs
		$hrs = floor($dum);						//removed the decimal part if any
		$dum = (($dum-$hrs)*60)					//converted into mins
		$min = floor($dum);						//removed decimals if any
		$dum = (($dum-$min)*60)					//converted into seconds
		$sec = floor($dum);						//removed decimals
		$str = '';
		if($yrs) $str = $str.$yrs." years ";
		if($days) $str = $str.$days." days ";
		if($hrs) $str = $str.$hrs." hours ";
		if($min) $str = $str.$min." minutes ";
		if($sec) $str = $str.$sec." seconds ";
		$str = $str."remaining.";



	}







	//This portion is for the connection to the database & to activate the $connect variable as a link	
		//connect to the server
		$connect = mysqli_connect("localhost","root","") or die ("Unable to connect to MySQL Sever.");
		
		//create the database if it does not exists & login ito it
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
			/*	create table if it does not exists
			users (userId int primary key, userName varchar(20), pswd varchar(20), autho char(1))
		*/
		$que = "create table if not exists users ( userId int primary key auto_increment, userName varchar(20), pswd varchar(20), autho char(1));";
		$connect->query($que);
		/*
			create table of item if it does not exists
			items ( itemId int primary key, itemName varchar(50), sellerId int , price int , imgLoc varchar(50), category varchar(25), shape varchar(20),color(20));
			there is a foreign key reference here, where item is the sub table & users is the supertable
			key reference is of sellerId & userId
			this reference has been made because, a seller also has to be a user in order to post an item, hence a user with an invalid id will not be allowed to be inside the database.
		*/
		$que = "create table if not exists items (itemId int primary key auto_increment, brand varchar(25), itemName varchar (50) , sellerId int references users(userId) , shape varchar (20),color varchar (30), price int , imgLoc varchar(255),category varchar(50),type char(1),custId int default null,status char(1) default 'N', deadLine int default NULL);";
		$connect->query($que);
		/*
			create a table of orders if it does not exists
			orders(orderId int primary key, custId int, itemId int , quantity int);
			there is a foreign key reference here also, because a customer must be inside the database before one may place an order.
			The sellerId has been removed from this table because it can be obtained by using :
				items natural join orders
				this returns us the sellerId, this can be further utilized by retrieving the name from the table users.
			Hence, the attribute is considered redundant & was removed.
		*/
		$que = "create table if not exists orders ( orderId int primary key auto_increment, custId int references users(userId) , itemId int references items(itemId) on delete set null, quantity int );";
		$connect->query($que);
?>