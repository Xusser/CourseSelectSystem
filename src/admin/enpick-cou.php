<?php require_once ('../dbconnection.php'); ?>
<?php
if ((isset($_GET['courseid'])) && ($_GET['courseid'] != "")) {
    // 转义
    $courseid = mysqli_real_escape_string($dbcon, $_GET['courseid']);
    // 构造查询
	$UpdateQuery = "UPDATE `course` SET `pick_allowed` = '1' WHERE `course`.`courseid` = ('$courseid');";
    $ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
    header('Location: manage-course.php?courseid='.$_GET['courseid'].'&disable=false');
}
?>