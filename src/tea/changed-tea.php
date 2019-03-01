<?php require_once ('../dbconnection.php'); ?>
<?php require_once ('../logout.php'); ?>
<?php require_once ('tea-privcheck.php'); ?>

<?php
// 防止全局变量造成安全隐患
$login = false;
// 初始化会话
session_start();
// 判断是否登陆
if (isset($_SESSION["login"]) && $_SESSION["login"] === true && $_SESSION["MM_UserGroup"] === 1) {
    //echo "objk";
} else {
    //验证失败，将 $_SESSION["login"] 置为 false
    $_SESSION["login"] = false;
	header("Location: ../index.html");
	//没登录guna
    die("没登录guna");
}
?>

<?php
$editFormAction = $_SERVER['PHP_SELF'];

//HTML to STRING
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction.= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {
	// 转义
    //$loginUsername = mysqli_real_escape_string($dbcon, $_POST['teaid']);
	$loginUsername = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
    $password = mysqli_real_escape_string($dbcon, $_POST['teapassword']);
    // 构造查询
	$UpdateQuery = "UPDATE teacher SET teapassword=('$password') WHERE teaid=('$loginUsername')";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	// 洗掉所有SESSION变量
    $_SESSION['MM_Username'] = NULL;
    $_SESSION['MM_UserGroup'] = NULL;
    $_SESSION['PrevUrl'] = NULL;
	$_SESSION['login'] = NULL;
    unset($_SESSION['MM_Username']);
    unset($_SESSION['MM_UserGroup']);
    unset($_SESSION['PrevUrl']);
	unset($_SESSION['login']);
	//强制登出
    $updateGoTo = "../index.html";
    //if (isset($_SERVER['QUERY_STRING'])) {
    //    $updateGoTo.= (strpos($updateGoTo, '?')) ? "&" : "?";
    //    $updateGoTo.= $_SERVER['QUERY_STRING'];
    //}
    header(sprintf("Location: %s", $updateGoTo));
}
?>

<?php
$loginUsername = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
$query_Recordset1 = "SELECT * FROM `teacher` WHERE teaid=('$loginUsername')";
$Recordset1 = mysqli_query($dbcon, $query_Recordset1) or die(mysqli_error($dbcon));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改密码-教师面板-选课系统</title>
    <meta name="HandheldFriendly" content="true">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="theme-color" content="#333344">
    <link rel="stylesheet" href="../style/bootstrap.min.css">
      <script src="../style/jquery-3.3.1.slim.min.js"></script>
      <script src="../style/popper.min.js"></script>
      <script src="../style/bootstrap.min.js"></script>
</head>

<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron">
				<h2>
					修改教师密码
				</h2>
				<p>
					请慎重操作
				</p>
			</div>
			<div class="row">
				<div class="col-md-8">
					<form role="form" method="POST" action="<?php echo $editFormAction; ?>">
						<div class="form-group">

							<label for="tealabel1">
								教师id
							</label>
							<input type="text" class="form-control" id="teaid" name="teaid" value="<?php echo $_SESSION['MM_Username']; ?>" readonly="readonly" />
						</div>
						<div class="form-group">

							<label for="tealabel2">
								教师密码
							</label>
							<input type="text" class="form-control" id="teapassword" name="teapassword" value="<?php echo $row_Recordset1['teapassword']; ?>" />
						</div>
						<button type="submit" name="submit" class="btn btn-primary">
							提交
						</button>
						<input type="hidden" name="MM_update" value="form" />
					</form>
				</div>
				<div class="col-md-4">
					 
					<a class="btn active btn-block btn-md btn-primary" href="welcome-tea.php">返回教师面板</a>
						
					<a class="btn btn-danger btn-md btn-block" href="<?php echo $logoutAction ?>" onclick="return confirm('确定要注销吗？')" >退出系统</a>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php mysqli_free_result($Recordset1); ?>