<?php
//By:PortWatcher
//blog:www.portwatcher.net

session_start();

require_once("templates/header.php");
echo '<body>';
echo '<header>
		<div id="logo"><img src="/images/pw.png"></div>
		<strong>QuickShell v0.3 Browser front</strong></header><br>';

//登录验证
$pass = $_POST["pass"];
if($pass=="yourpass")
{
	$_SESSION["admin"]="portwatcher";
}
//登出
if($_GET["do"]=="logout")
{
	unset($_SESSION["admin"]);
	header("location:index.php");
}
//功能显示，如果未登录就显示登录窗口
if(isset($_SESSION["admin"]))
{
	require("templates/menu.php");
	echo '<br>';
	switch ($_GET["do"])
	{
		case "cmd": require_once("cmd.php");break;
		case "file": require_once("files.php");break;
		case "log": require_once("log.php");break;
		case "view": require_once("view.php");break;
		case "log": require_once("log.php");break;
		case "screen": require_once("screen.php");break;
	}
}
else
{
	echo '<div id="login">';
	echo '<form action = "index.php" method="post">';
	echo '<p align=center><input type="password" name="pass" />';
	echo '<input type="submit" value="login"></p>';
	echo '</div>';
}


echo '<br>';
echo '<footer>codz by <a href="http://www.pwhack.me" target="_blank">PortWatcher</a></footer>';
echo '</body>';
echo '</html>';



?>