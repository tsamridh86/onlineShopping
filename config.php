<?php
	
	function calcTime ($deadLine)
	{
		$currentDate  = $_SERVER['REQUEST_TIME'];
		$timeRemaining = $deadLine - $currentDate;
		if($timeRemaining <= 0) return 0;
		$dum = $timeRemaining / (60*60*24*365);	//converted into years
		$yrs = floor($dum);						//removed the decimal part if any
		$dum = (($dum - $yrs)*365);				//converted into days
		$days = floor($dum);					//removed the decimal part if any
		$dum = (($dum-$days)*24);				//converted into hrs
		$hrs = floor($dum);						//removed the decimal part if any
		$dum = (($dum-$hrs)*60);				//converted into mins
		$min = floor($dum);						//removed decimals if any
		$dum = (($dum-$min)*60);					//converted into seconds
		$sec = floor($dum);						//removed decimals
		$str = '';
		if($yrs) $str = $str.$yrs." years ";
		if($days) $str = $str.$days." days ";
		if($hrs) $str = $str.$hrs." hours ";
		if($min) $str = $str.$min." minutes ";
		if($sec) $str = $str.$sec." seconds ";
		return $str;
	}	
		//connect to the server
		$connect = mysqli_connect("localhost","root","") or die ("Unable to connect to MySQL Sever.");
		
		//create the database if it does not exists & login ito it
		$dbstart = "create database if not exists shops;";
		$connect->query($dbstart);
		mysqli_select_db($connect , "shops");
			/*	create table if it does not exists
		*/
		$que = "create table if not exists users ( userId int primary key auto_increment, userName varchar(20), pswd varchar(20), autho char(1));";
		$connect->query($que);
		
		$que = "create table if not exists items (itemId int primary key auto_increment, brand varchar(25), itemName varchar (50) , sellerId int references users(userId) , shape varchar (20),color varchar (30), price int , imgLoc varchar(255),category varchar(50),type char(1),custId int default null,status char(1) default 'N', deadLine int default NULL);";
		$connect->query($que);
		$que = "create table if not exists orders ( orderId int primary key auto_increment, custId int references users(userId) , itemId int references items(itemId) on delete set null, quantity int );";
		$connect->query($que);

?>