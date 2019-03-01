<?php require_once ('dbconnection.php'); ?>

<?php
	$MM_redirectLoginSuccess = "tea/welcome-tea.php";
    $MM_redirectLoginFailed = "errors/loginfailed.php";

	// 转义
    $loginUsername = mysqli_real_escape_string($dbcon, $_POST['teaid']);
    $password = mysqli_real_escape_string($dbcon, $_POST['teapassword']);
    // 构造查询
	$LoginRS_query = "SELECT `teaid`, `teaname`, `tea_disable` FROM teacher WHERE teaid=('$loginUsername') AND teapassword=('$password')";
    $LoginRS = mysqli_query($dbcon, $LoginRS_query) or die(mysqli_error($dbcon));
    $loginFoundUser = mysqli_num_rows($LoginRS);
	$row_Recordset1 = mysqli_fetch_assoc($LoginRS);
	//$totalRows_Recordset1 = mysqli_num_rows($LoginRS);
	if ($loginFoundUser) {
		// 当验证通过后，判断是不是被BAN账户
		$Priv_query = "SELECT `tea_ReleaseAllow`, `tea_CourseModAllow`, `tea_StuDelAllow`, `tea_StuFinAllow`, `global_ban` FROM `privilege`";
		$PrivRS = mysqli_query($dbcon, $Priv_query) or die(mysqli_error($dbcon));
		$Priv_Recordset = mysqli_fetch_assoc($PrivRS);
		if($row_Recordset1['tea_disable']==1  || $Priv_Recordset['global_ban']==1){
			header("Location: errors/ban.php");
			exit(1);
		}
		// 启动 Session,重新roll一个
		session_start();
		session_regenerate_id(true);
		
		if($Priv_Recordset['tea_ReleaseAllow']==0)
			$_SESSION['MM_tea_ReleaseAllow'] = 0;//发布课程
		
		if($Priv_Recordset['tea_CourseModAllow']==0)
			$_SESSION['MM_tea_CourseModAllow'] = 0;//修改课程
		
		if($Priv_Recordset['tea_StuDelAllow']==0)
			$_SESSION['MM_tea_StuDelAllow'] = 0;//删除学生
		
		if($Priv_Recordset['tea_StuFinAllow']==0)
			$_SESSION['MM_tea_StuFinAllow'] = 0;//完成课程
		
		$_SESSION['MM_Username'] = $loginUsername; // 这玩意其实是ID
		$_SESSION['MM_RealName'] = $row_Recordset1['teaname']; // 这才是用户名字
        $_SESSION['MM_UserGroup'] = 1; // Teacher=1
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