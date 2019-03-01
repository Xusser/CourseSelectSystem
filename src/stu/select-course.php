<?php require_once ('../dbconnection.php'); ?>
<?php require_once ('../logout.php'); ?>
<?php require_once ('globalbancheck.php'); ?>

<?php
// 防止全局变量造成安全隐患
$login = false;
// 初始化会话
session_start();
// 判断是否登陆
if (isset($_SESSION["login"]) && $_SESSION["login"] === true && $_SESSION["MM_UserGroup"] === 0) {
    //echo "objk";
} else {
    //验证失败，将 $_SESSION["login"] 置为 false
    $_SESSION["login"] = false;
	header("Location: ../index.html");
	//没登录guna
    die("没登录guna");
}

//权限查询
$Priv_query = "SELECT `stu_SelectAllow`, `global_ban` FROM `privilege`";
$PrivRS = mysqli_query($dbcon, $Priv_query) or die(mysqli_error($dbcon));
$Priv_Recordset = mysqli_fetch_assoc($PrivRS);
if($Priv_Recordset['stu_SelectAllow']==0)
{
	$_SESSION['MM_stu_SelectAllow'] = 0;
	header("Location: ../errors/unauthorized.php");
	exit(1);
}
else
{
	$_SESSION['MM_stu_SelectAllow'] = 1;
}
?>

<?php
// 转义
$courseid = mysqli_real_escape_string($dbcon, $_GET['courseid']);
// 构造查询
$query_Recordset1 = "SELECT courseid,coursename,course.teaid,selected,total,classtime,classroom,credit,shangketime,shiyantime,prevcourse,teaname FROM course,teacher WHERE teacher.teaid=course.teaid AND `courseid` = ('$courseid')";
//$query_Recordset1 = "SELECT * FROM `course` WHERE `courseid` = ('$courseid') ORDER BY `courseid` ASC";
$Recordset1 = mysqli_query($dbcon, $query_Recordset1) or die(mysqli_error($dbcon));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

if($row_Recordset1['prevcourse']!='无'){
	// 转义 判断是否已上完先行课程
	$stuid = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
	$coursename = mysqli_real_escape_string($dbcon, $row_Recordset1['prevcourse']);
	// 构造查询
	$query_Recordset5 = "SELECT stucourse.courseid FROM stucourse,course WHERE stucourse.courseid=course.courseid AND stucourse.stuid=('$stuid') AND course.coursename=('$coursename') AND stucourse.fin=1";
	$Recordset5 = mysqli_query($dbcon, $query_Recordset5) or die(mysqli_error($dbcon));
	$isPrevDone = mysqli_num_rows($Recordset5);
	//echo $query_Recordset5;
	//echo $isPrevDone;
	if ($isPrevDone==0) {
		header("Location: ../errors/selectstatus.php?SubmitSuccess=false&isPrevDone=false");
	}
}
?>

<?php
$ConfirmAction = $_SERVER['PHP_SELF'] . "?doConfirm=true";
//把courseid组装上去
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $ConfirmAction.= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doConfirm'])) && ($_GET['doConfirm'] == "true")) {
	
	// 转义
	$stuid = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
	// 构造查询 拉取student信息
	$query_Recordset2 = "SELECT * FROM `student` WHERE `stuid` = ('$stuid')";
	$Recordset2 = mysqli_query($dbcon, $query_Recordset2) or die(mysqli_error($dbcon));
	$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
	
	// 转义
	$stuname = mysqli_real_escape_string($dbcon, $_SESSION['MM_RealName']);
	$collegeid = mysqli_real_escape_string($dbcon, $row_Recordset2['collegeid']);
	$major = mysqli_real_escape_string($dbcon, $row_Recordset2['major']);
	$class = mysqli_real_escape_string($dbcon, $row_Recordset2['class']);
	$teaid = mysqli_real_escape_string($dbcon, $row_Recordset1['teaid']);
	$courseid = mysqli_real_escape_string($dbcon, $row_Recordset1['courseid']);
	$classtime = mysqli_real_escape_string($dbcon, $row_Recordset1['classtime']);
	$coursename = mysqli_real_escape_string($dbcon, $row_Recordset1['coursename']);
	
	// 构造查询 是否重复插入 [正在进行 这个学生 这个课程 只要插入过] 不能再选
	$query_Recordset3 = "SELECT course.coursename FROM course,stucourse WHERE course.courseid=stucourse.courseid AND course.cou_disable=0 AND stucourse.stuid=('$stuid') AND course.coursename=('$coursename')";
	$ResultRS = mysqli_query($dbcon, $query_Recordset3) or die(mysqli_error($dbcon));
    $AlreadyExisted = mysqli_num_rows($ResultRS);
	if ($AlreadyExisted) {//如果重复就返回失败 终止脚本
		header("Location: ../errors/selectstatus.php?SubmitSuccess=false&SubmitExist=true");
		exit;
	}
	// 构造查询 是否重复插入 [已经结束 这个学生 这个课程 通过了] 不能再选
	$query_Recordset3 = "SELECT course.coursename FROM course,stucourse WHERE course.courseid=stucourse.courseid AND course.cou_disable=1 AND stucourse.fin=1 AND stucourse.stuid=('$stuid') AND course.coursename=('$coursename')";
	$ResultRS = mysqli_query($dbcon, $query_Recordset3) or die(mysqli_error($dbcon));
    $AlreadyExisted = mysqli_num_rows($ResultRS);
	if ($AlreadyExisted) {//如果重复就返回失败 终止脚本
		header("Location: ../errors/selectstatus.php?SubmitSuccess=false&SubmitExist=true");
		exit;
	}
	// 构造查询 是否满人 顺便判断是否越权(课程是否已经关闭)
	$query_Recordset4 = "SELECT * FROM `course` WHERE `courseid` = ('$courseid') AND `selected` >= total AND `cou_disable` = 0";
	$ResultRS = mysqli_query($dbcon, $query_Recordset4) or die(mysqli_error($dbcon));
    $AlreadyFull = mysqli_num_rows($ResultRS);
	if ($AlreadyFull) {//如果满人就返回失败 终止脚本
		header("Location: ../errors/selectstatus.php?SubmitSuccess=false&SubmitFull=true");
		exit;
	}
	// 构造查询 插入选课信息
	$UpdateQuery = "INSERT INTO `stucourse` (`stuid`, `stuname`, `collegeid`, `major`, `class`, `teaid`, `courseid`, `classtime`) VALUES (('$stuid'), ('$stuname'), ('$collegeid'), ('$major'), ('$class'), ('$teaid'), ('$courseid'), ('$classtime'));";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	$InsertSuccess = mysqli_affected_rows($dbcon);
	if ($InsertSuccess) {//如果成功插入就+1
		$UpdateQuery = "UPDATE `course` SET `selected` = `selected`+1 WHERE `course`.`courseid` = ('$courseid')";
		$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
		header("Location: ../errors/selectstatus.php?SubmitSuccess=true");
	}
	else{
		header("Location: ../errors/selectstatus.php?SubmitSuccess=false");
	}
	exit;
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>选课确认-学生面板-选课系统</title>
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
				<h2>选课确认</h2>
				<p>选课确认 —— <?php echo $_SESSION['MM_RealName']; ?> [<?php echo $_SESSION['MM_Username']; ?>] 选取 <?php echo $row_Recordset1['coursename']; ?></p>
			</div>
			<div class="row">
				<div class="col-md-8">
					<h3 class="text-center">
						<p class="text-primary">请确认是否选取 [<?php echo $row_Recordset1['coursename']; ?>] 课程？</p>
					</h3>
					<br>
					<table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>项目</th>
                                    <th>确认</th>
                                </tr>
                            </thead>
                            <tbody id="CourseTable">
                                <tr>
                                    <td>课程ID</td>
                                    <td><?php echo $row_Recordset1['courseid']; ?></td>
                                </tr>
								<tr class="table-primary">
                                    <td>课程名</td>
                                    <td><?php echo $row_Recordset1['coursename']; ?></td>
                                </tr>
								<tr>
                                    <td>教师</td>
                                    <td><?php echo $row_Recordset1['teaname']; ?> [<?php echo $row_Recordset1['teaid']; ?>]</td>
                                </tr>
								<tr>
                                    <td>已选人数/总人数</td>
                                    <td><?php echo $row_Recordset1['selected']; ?>/<?php echo $row_Recordset1['total']; ?></td>
                                </tr>
								<tr>
                                    <td>上课时间</td>
                                    <td><?php echo $row_Recordset1['classtime']; ?></td>
                                </tr>
								<tr>
                                    <td>上课地点</td>
                                    <td><?php echo $row_Recordset1['classroom']; ?></td>
                                </tr>
								<tr>
                                    <td>学分</td>
                                    <td><?php echo $row_Recordset1['credit']; ?></td>
                                </tr>
								<tr>
                                    <td>讲授学时</td>
                                    <td><?php echo $row_Recordset1['shangketime']; ?></td>
                                </tr>
								<tr>
                                    <td>实验学时</td>
                                    <td><?php echo $row_Recordset1['shiyantime']; ?></td>
                                </tr>
								<tr class="table-danger">
                                    <td>前置课程</td>
                                    <td><?php echo $row_Recordset1['prevcourse']; ?></td>
                                </tr>
                            </tbody>
                        </table>
						<br>
						<a class="btn btn-lg active btn-block btn-md btn-success" href="<?php echo $ConfirmAction ?>">确认选取</a>
						<br>
				</div>
				<div class="col-md-4">
					 
					<a class="btn active btn-block btn-md btn-primary" href="welcome-stu.php">返回学生面板</a>
						
					<a class="btn btn-danger btn-md btn-block" href="<?php echo $logoutAction ?>" onclick="return confirm('确定要注销吗？')" >退出系统</a>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php mysqli_free_result($Recordset1); ?>