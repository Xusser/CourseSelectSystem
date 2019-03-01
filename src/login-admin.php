<?php require_once ('dbconnection.php'); ?>

<?php
	$MM_redirectLoginSuccess = "admin/welcome-admin.php";
    $MM_redirectLoginFailed = "errors/loginfailed.php";

	// 转义
    $loginUsername = mysqli_real_escape_string($dbcon, $_POST['adminid']);
    $password = mysqli_real_escape_string($dbcon, $_POST['adminpassword']);
    // 构造查询
	$LoginRS__query = "SELECT adminid, adminpassword FROM `admin` WHERE adminid=('$loginUsername') AND adminpassword=('$password')";
    $LoginRS = mysqli_query($dbcon, $LoginRS__query) or die(mysqli_error($dbcon));
    $loginFoundUser = mysqli_num_rows($LoginRS);
	$row_Recordset1 = mysqli_fetch_assoc($LoginRS);
	$totalRows_Recordset1 = mysqli_num_rows($LoginRS);
	if ($loginFoundUser) {
		// 当验证通过后，启动 Session,重新roll一个
		session_start();
		session_regenerate_id(true);
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['MM_RealName'] = $row_Recordset1['adminid']; // 狗管理没有名字
        $_SESSION['MM_UserGroup'] = 2; // Admin=2
		// 判断登录用变量
		$_SESSION["login"] = true;
		//检查是否要返回前一个url
		if (isset($_SESSION['PrevUrl']) && false) {
            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
        }
        header("Location: " . $MM_redirectLoginSuccess);
    } else {
        header("Location: " . $MM_redirectLoginFailed);
    }
?>