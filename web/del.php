<?php
require("config.php");
$mysqli = new mysqli("localhost",$username,$password,"yourdbname");
if(mysqli_connect_error())
{
	printf("cant connect to the database：%s\n",mysqli_connect_error());
	exit();
}

session_start();

if(isset($_SESSION["admin"]))
{
	if($_GET["id"] != NULL)
	{
		$iddel = $_GET["id"];
		$mysqli->query("DELETE FROM pwrat WHERE id = '$iddel'");
		header("location:index.php?do=log");
	}
}
$mysqli->close();	
?>