<?php require_once ('../dbconnection.php'); ?>
<?php
	// 转义
	$collegename = mysqli_real_escape_string($dbcon, $_POST['collegename']);
    // 构造查询
	$InsertQuery = "INSERT INTO `college` (`collegeid`, `collegename`, `col_disable`) VALUES (NULL, ('$collegename'), '0');";
	//echo $InsertQuery;
	$ResultRS = mysqli_query($dbcon, $InsertQuery) or die(mysqli_error($dbcon));
	header('Location: manage-college.php?Success=true');
?>