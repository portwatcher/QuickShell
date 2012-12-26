<?php
require("dbconn.php");

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