<?php require_once ('dbconnection.php'); ?>

<?php
	$MM_redirectLoginSuccess = "stu/welcome-stu.php";
    $MM_redirectLoginFailed = "errors/loginfailed.php";

	// 转义
    $loginUsername = mysqli_real_escape_string($dbcon, $_POST['stuid']);
    $password = mysqli_real_escape_string($dbcon, $_POST['stupassword']);
    // 构造查询
	$LoginRS_query = "SELECT `stuid`, `stuname`, `stu_disable` FROM student WHERE stuid=('$loginUsername') AND stupassword=('$password')";
    $LoginRS = mysqli_query($dbcon, $LoginRS_query) or die(mysqli_error($dbcon));
    $loginFoundUser = mysqli_num_rows($LoginRS);
	$row_Recordset1 = mysqli_fetch_assoc($LoginRS);
	//$totalRows_Recordset1 = mysqli_num_rows($LoginRS);
	if ($loginFoundUser) {
		// 当验证通过后，判断是不是被BAN账户
		$Priv_query = "SELECT `stu_SelectAllow`, `global_ban` FROM `privilege`";
		$PrivRS = mysqli_query($dbcon, $Priv_query) or die(mysqli_error($dbcon));
		$Priv_Recordset = mysqli_fetch_assoc($PrivRS);
		if($row_Recordset1['stu_disable']==1 || $Priv_Recordset['global_ban']==1){
			header("Location: errors/ban.php");
			exit(1);
		}
		// 启动 Session,重新roll一个
		session_start();
		session_regenerate_id(true);
		if($Priv_Recordset['stu_SelectAllow']==0)
			$_SESSION['MM_stu_SelectAllow'] = 0;
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['MM_RealName'] = $row_Recordset1['stuname']; // 这才是用户名字
        $_SESSION['MM_UserGroup'] = 0; // Student=0
		// 判断登录用变量
		$_SESSION["login"] = true;
		// 检查是否要返回前一个url
		if (isset($_SESSION['PrevUrl']) && false) {
            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
        }
        header("Location: " . $MM_redirectLoginSuccess);
    } else {
        header("Location: " . $MM_redirectLoginFailed);
    }
?>