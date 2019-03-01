<?php require_once ('../dbconnection.php'); ?>
<?php
	// 转义
    $TName = mysqli_real_escape_string($dbcon, $_POST['teaname']);
    $TPass = mysqli_real_escape_string($dbcon, $_POST['teapassword']);
	$TSex = mysqli_real_escape_string($dbcon, $_POST['sex']);
    $TCollege = mysqli_real_escape_string($dbcon, $_POST['collegename']);
    // 构造查询
	$InsertQuery = "INSERT INTO teacher (teaname, sex, collegename, teapassword) VALUES (('$TName'), ('$TSex'), ('$TCollege'), ('$TPass'))";
	$ResultRS = mysqli_query($dbcon, $InsertQuery) or die(mysqli_error($dbcon));
    //$Result1 = mysql_query($insertSQL, $selectcoursesystem) or die(mysql_error());
	header('Location: manage-tea.php?AddSuccess=true');
?>