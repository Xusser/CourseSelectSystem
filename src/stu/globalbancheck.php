<?php
$Priv_query = "SELECT `global_ban` FROM `privilege`";
$PrivRS = mysqli_query($dbcon, $Priv_query) or die(mysqli_error($dbcon));
$Priv_Recordset = mysqli_fetch_assoc($PrivRS);
// 全局ban人
if($Priv_Recordset['global_ban']==1)
{
	header("Location: ../errors/ban.php");
	exit(1);
}
?>