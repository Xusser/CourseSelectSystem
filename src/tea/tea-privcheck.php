<?php
$Priv_query = "SELECT `tea_ReleaseAllow`, `tea_CourseModAllow`, `tea_StuDelAllow`, `tea_StuFinAllow`, `global_ban` FROM `privilege`";
$PrivRS = mysqli_query($dbcon, $Priv_query) or die(mysqli_error($dbcon));
$Priv_Recordset = mysqli_fetch_assoc($PrivRS);
if($Priv_Recordset['tea_ReleaseAllow']==0)
	$_SESSION['MM_tea_ReleaseAllow'] = 0;
else
	$_SESSION['MM_tea_ReleaseAllow'] = 1;

if($Priv_Recordset['tea_CourseModAllow']==0)
	$_SESSION['MM_tea_CourseModAllow'] = 0;
else
	$_SESSION['MM_tea_CourseModAllow'] = 1;

if($Priv_Recordset['tea_StuDelAllow']==0)
	$_SESSION['MM_tea_StuDelAllow'] = 0;
else
	$_SESSION['MM_tea_StuDelAllow'] = 1;

if($Priv_Recordset['tea_StuFinAllow']==0)
	$_SESSION['MM_tea_StuFinAllow'] = 0;
else
	$_SESSION['MM_tea_StuFinAllow'] = 1;

// 全局ban人
if($Priv_Recordset['global_ban']==1)
{
	header("Location: ../errors/ban.php");
	exit(1);
}
?>