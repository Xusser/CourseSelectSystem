<?php require_once ('../dbconnection.php'); ?>
<?php
	// 转义
	$stuname = mysqli_real_escape_string($dbcon, $_POST['stuname']);
    $collegeid = mysqli_real_escape_string($dbcon, $_POST['collegeid']);
	$major = mysqli_real_escape_string($dbcon, $_POST['major']);
	$sex = mysqli_real_escape_string($dbcon, $_POST['sex']);
	$class = mysqli_real_escape_string($dbcon, $_POST['class']);
	$stupassword = mysqli_real_escape_string($dbcon, $_POST['stupassword']);
    // 构造查询
	$InsertQuery = "INSERT INTO student (stuname, collegeid, major, sex, class, stupassword) VALUES (('$stuname'), ('$collegeid'), ('$major'), ('$sex'), ('$class'), ('$stupassword'))";
	$ResultRS = mysqli_query($dbcon, $InsertQuery) or die(mysqli_error($dbcon));
	header('Location: manage-stu.php?AddSuccess=true');
?>