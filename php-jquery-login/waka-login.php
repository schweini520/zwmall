<?php

	$is_ajax = $_REQUEST['is_ajax'];
	if (empty($_REQUEST['username'])) {
		echo "name_null";
    	return "";
    } else {
    	//$name = test_input($_POST["name"]);
    }

	if (empty($_REQUEST['password'])) {
		echo "pwd_null";
		return "";
	}
	if(isset($is_ajax) && $is_ajax == 1)
	{
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];

		/*
			:: Note to Wakaka Friends
			-----------------------------------------------------------------------------------------
			You can put your MySQL query here to check availability Username & Password from database
		*/

		$con = mysql_connect("127.0.0.1", "root", "xiaozhu2257");
		//echo "<script type='text/javascript'>alert('connected');</script>";

		if (!con)  //If connect have some problem
		{
			die("Error: Have some error when connect to mysql" . mysql_error());
			echo "<br />";
		}

		//First time to connect, and then create database
		if (mysql_query("CREATE DATABASE user_msg_db", $con))
		{
			//if create databse success, and then can create table
			mysql_select_db("user_msg_db", $con);
			$sql = "CREATE TABLE UserMsg (UserName varchar(32), PassWord varchar(32))";
		} else {
			//database have create in lasttime... so we can insert data directly
			mysql_select_db("user_msg_db", $con);
			$sql = "CREATE TABLE UserMsg (UserName varchar(32), PassWord varchar(32))";

			//mysql_query("delete from UserMsg where UserName = 'wakaka'");
			//mysql_query($sql, $con);

			$name = mysql_fetch_array(mysql_query("select UserName from UserMsg where UserName = '$username'"));
			if ($name['UserName'] === $username)
			{
				$result = mysql_query("select PassWord from UserMsg where UserName = '$username'");
				if ($result){
					$row = mysql_fetch_array($result);
					if ($row['PassWord'] === $password) {
						echo "success";
					} else {
						echo "pwd_error";
					}
				}
			} else {
				echo "user_error";
			}

			//mysql_query("INSERT INTO UserMsg (UserName, PassWord) VALUES('$username', '$password')");
/**
			if (!mysql_query($sql, $con))
			{
				die("Error: Insert data error" . mysql_error());
				echo "<br />";
			}
**/
		}

		mysql_query($sql, $con);

		if($username == 'wakaka' && $password == 'design')
		{
			echo "success";
		}
	} else if (isset($is_ajax) && $is_ajax == 2){
		if (empty($_REQUEST['email'])) {
			echo "email_null";
			return "";
		} else {
			$email = test_input($_REQUEST["email"]);
			if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
				echo "email_err";
				return "";
			}
		}

		//echo "is_ajax";
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$flag = 0;
		$con = mysql_connect("127.0.0.1", "root", "xiaozhu2257");
		if (!$con)
		{
			die("Error: SQL connect error!!". mysql_error());
		}

		if (mysql_query("CREATE DATABASE user_msg_db", $con))
		{
			//if create databse success, and then can create table
			mysql_select_db("user_msg_db", $con);
			$sql = "CREATE TABLE UserMsg (UserName varchar(32), PassWord varchar(32))";
		} else {
			mysql_select_db("user_msg_db", $con);
			$result = mysql_query("select UserName from UserMsg where UserName = '$username'");
			while ($name = mysql_fetch_array($result)) {
				if ($name['UserName'] === $username)
				{
					echo "user_exist";
					$flag = 1;
					return "";
				}
			}
			if ($flag === 0){
				//echo "can_insert";
				$sql = "INSERT INTO UserMsg (UserName, PassWord) VALUES('$username', '$password')";
				if (!mysql_query($sql,$con))
				{
					die('Error: ' . mysql_error());
					echo "insert_error";
				}
				echo "insert_success";
			}
		}

		//mysql_query($sql, $con);
	} else if (isset($is_ajax) && $is_ajax == 3){
		if (empty($_REQUEST['rep_password'])) {
			echo "rep_password_null";
			return "";
		} else if (!($_REQUEST['password'] === $_REQUEST['rep_password'])) {
			echo "two_pwd_diff";
			return "";
		}

		$username = $_REQUEST['username'];
		$pwd = $_REQUEST['rep_password'];
		$flag = 1;
		$con = mysql_connect("127.0.0.1", "root", "xiaozhu2257");
		if (!$con)
		{
			die("Error: SQL connect error!!". mysql_error());
		}

		if (mysql_query("CREATE DATABASE user_msg_db", $con))
		{
			//if create databse success, and then can create table
			mysql_select_db("user_msg_db", $con);
			$sql = "CREATE TABLE UserMsg (UserName varchar(32), PassWord varchar(32))";
		} else {
			mysql_select_db("user_msg_db", $con);
			$result = mysql_query("select UserName from UserMsg where UserName = '$username'");

			while ($name = mysql_fetch_array($result)) {
				if ($name['UserName'] === $username)
				{
					$flag = 0;
				}
			}

			if ($flag == 0) {
				//user have register, and then can change password
				mysql_query("update UserMsg set PassWord = '$pwd' where UserName = '$username'");
				echo "update_success";
			} else {
				echo "user_not_exist";
			}
		}
	}

	function test_input($data) {
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}
?>
