<?php require_once ('../dbconnection.php'); ?>
<?php
if ((isset($_GET['stuid'])) && ($_GET['stuid'] != "")) {
    // 转义
    $SID = mysqli_real_escape_string($dbcon, $_GET['stuid']);
    // 构造查询
	
	$UpdateQuery = "UPDATE `student` SET `stu_disable` = '1' WHERE `student`.`stuid` = ('$SID');";
	//$DeleteQuery = "DELETE FROM student WHERE stuid=('$SID')";
    $ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
    header('Location: manage-stu.php?stuid='.$_GET['stuid'].'&DelSuccess=true');
}
?>