<?php
// 初始化会话
	session_start();

// 登出
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction.= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    // 洗掉所有SESSION变量
    //$_SESSION['MM_Username'] = NULL;
    //$_SESSION['MM_UserGroup'] = NULL;
    //$_SESSION['PrevUrl'] = NULL;
	//$_SESSION['login'] = NULL;
	//$_SESSION['MM_RealName'] = NULL;
    //unset($_SESSION['MM_Username']);
    //unset($_SESSION['MM_UserGroup']);
    //unset($_SESSION['PrevUrl']);
	//unset($_SESSION['login']);
	//unset($_SESSION['MM_RealName']);
	session_start();
	session_unset();
	session_destroy();
    header("Location: ../index.html");
    exit;
}
?>