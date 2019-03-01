<?php require_once ('../dbconnection.php'); ?>
<?php
if ((isset($_GET['teaid'])) && ($_GET['teaid'] != "")) {
    // 转义
    $TID = mysqli_real_escape_string($dbcon, $_GET['teaid']);
    // 构造查询
	$UpdateQuery = "UPDATE `teacher` SET `tea_disable` = '1' WHERE `teacher`.`teaid` = ('$TID');";
	//$DeleteQuery = "DELETE FROM teacher WHERE teaid=('$TID')";
    $ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
    header('Location: manage-tea.php?teaid='.$_GET['teaid'].'&DelSuccess=true');
}
?>