<?php require_once ('../dbconnection.php'); ?>
<?php
if ((isset($_GET['collegeid'])) && ($_GET['collegeid'] != "")) {
    // 转义
    $collegeid = mysqli_real_escape_string($dbcon, $_GET['collegeid']);
    // 构造查询
	$UpdateQuery = "UPDATE `college` SET `col_disable` = '1' WHERE `college`.`collegeid` = ('$collegeid');";
    $ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
    header('Location: manage-college.php?collegeid='.$_GET['collegeid'].'&delete=true');
}
?>