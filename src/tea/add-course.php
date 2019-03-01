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

if($_SESSION['MM_tea_ReleaseAllow'] == 0){
	header("Location: ../errors/unauthorized.php");
	exit(1);
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
	$teaid = mysqli_real_escape_string($dbcon, $_SESSION["MM_Username"]);
    $coursename = mysqli_real_escape_string($dbcon, $_POST['coursename']);
	$total = mysqli_real_escape_string($dbcon, $_POST['total']);
	$classtime = mysqli_real_escape_string($dbcon, $_POST['classtime']);
	$classroom = mysqli_real_escape_string($dbcon, $_POST['classroom']);
	$credit = mysqli_real_escape_string($dbcon, $_POST['credit']);
	$shangketime = mysqli_real_escape_string($dbcon, $_POST['shangketime']);
	$shiyantime = mysqli_real_escape_string($dbcon, $_POST['shiyantime']);
	$prevcourse = mysqli_real_escape_string($dbcon, $_POST['prevcourse']);
    // 构造查询
	$UpdateQuery = "INSERT INTO `course` (`courseid`, `coursename`, `teaid`, `selected`, `total`, `classtime`, `classroom`, `credit`, `shangketime`, `shiyantime`, `prevcourse`) VALUES (NULL, ('$coursename'), ('$teaid'), '0', ('$total'), ('$classtime'), ('$classroom'), ('$credit'), ('$shangketime'), ('$shiyantime'), ('$prevcourse'));";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
	header("Location: welcome-tea.php");
}
?>

<?php
	$query_Recordset1 = "SELECT DISTINCT `coursename` FROM `course` ORDER BY `coursename` ASC";
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
    <title>发布课程-教师面板-选课系统</title>
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
					发布课程
				</h2>
                    <p>
                        请填写需要发布的课程信息
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="text-center">
							请填写需要发布的课程信息(教师ID:<?php echo $_SESSION["MM_Username"]; ?>)
						</h3>
						<form role="form" method="POST" action="<?php echo $editFormAction; ?>" class="needs-validation" novalidate>
							<div class="form-group">	
								<label for="courselabel1">课程名称</label>
								<input type="text" class="form-control" id="coursename" name="coursename" required pattern="^.{1,20}$" />
								<div class="invalid-feedback">此为必填项,范围为1-20字符</div>
							</div>
							<div class="form-group">
								<label for="courselabel2">总人数</label>
								<input type="text" class="form-control" id="total" name="total" required pattern="^(1[0-9][0-9]|[1-9][0-9]|[1-9]){1,3}$" />
								<div class="invalid-feedback">此为必填项，范围为1-199</div>
							</div>
							<div class="form-group">
								<label for="courselabel3">上课时间</label>
								<input type="text" class="form-control" id="classtime" name="classtime" required pattern="^.{1,30}$" />
								<div class="invalid-feedback">此为必填项,范围为1-20字符</div>
							</div>
							<div class="form-group">
								<label for="courselabel4">地点</label>
								<input type="text" class="form-control" id="classroom" name="classroom" required pattern="^.{1,30}$" />
								<div class="invalid-feedback">此为必填项,范围为1-20字符</div>
							</div>
							<div class="form-group">
								<label for="courselabel5">学分</label>
								<input type="text" class="form-control" id="credit" name="credit" required pattern="^[1-5]{1}$" />
								<div class="invalid-feedback">此为必填项，范围为1-5</div>
							</div>
							<div class="form-group" hidden>
								<label for="courselabel6">讲授学时</label>
								<input type="number" class="form-control" id="shangketime" name="shangketime" value="50" required />
								<div class="invalid-feedback">此为必填项</div>
							</div>
							<div class="form-group" hidden>
								<label for="courselabel7">实验学时</label>
								<input type="number" class="form-control" id="shiyantime" name="shiyantime" value="10" required />
								<div class="invalid-feedback">此为必填项</div>
							</div>
							<div class="form-group">
								<label for="courselabel8">先行课程</label>
								<select class="form-control" id="prevcourse" name="prevcourse">
									<option selected value="无">无</option>
									<option disabled>------</option>
									<?php do { ?>
									<option value="<?php echo $row_Recordset1['coursename']; ?>"><?php echo $row_Recordset1['coursename']; ?></option>
									<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
								</select>
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
<script>
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
</body>

</html>
<?php mysqli_free_result($Recordset1); ?>