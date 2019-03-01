<?php require_once ('../dbconnection.php'); ?>
<?php require_once ('../logout.php'); ?>

<?php
// 防止全局变量造成安全隐患
$login = false;
// 初始化会话
session_start();
// 判断是否登陆
if (isset($_SESSION["login"]) && $_SESSION["login"] === true && $_SESSION["MM_UserGroup"] === 2) {
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
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {
	// 转义
    $teaname = mysqli_real_escape_string($dbcon, $_POST['teaname']);
    $collegename = mysqli_real_escape_string($dbcon, $_POST['collegename']);
	$introduction = mysqli_real_escape_string($dbcon, $_POST['introduction']);
	$sex = mysqli_real_escape_string($dbcon, $_POST['sex']);
	$teaid = mysqli_real_escape_string($dbcon, $_POST['teaid']);
	// 构造查询
	$UpdateQuery = "UPDATE teacher SET teaname=('$teaname'), collegename=('$collegename'), introduction=('$introduction'), sex=('$sex') WHERE teaid=('$teaid')";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
	//header('Location: modify-tea.php');
	header(sprintf("Location: modify-tea.php?teaid=%s&Success=true", $teaid));
}

$colname_Recordset1 = "-1";
if (isset($_GET['teaid'])) {
  $colname_Recordset1 = $_GET['teaid'];
}

$colname_Recordset1 = mysqli_real_escape_string($dbcon, $colname_Recordset1);
// 构造查询
$query_Recordset1 = "SELECT * FROM teacher WHERE teaid = ('$colname_Recordset1')";
$Recordset1 = mysqli_query($dbcon, $query_Recordset1) or die(mysqli_error($dbcon));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

// 构造查询 能选的学院必须是可用的
$query_Recordset2 = "SELECT `collegeid`, `collegename` FROM `college` WHERE col_disable=0 ORDER BY `collegeid` ASC";
$Recordset2 = mysqli_query($dbcon, $query_Recordset2) or die(mysqli_error($dbcon));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>教师修改-管理员面板-选课系统</title>
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
					教师信息修改
				</h2>
				<p>
					请慎重操作
				</p>
			</div>
			<div class="row">
				<div class="col-md-8">
				<div class="alert alert-dismissable alert-success" id="SuccessInfo">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<div id="SuccessText"></div>
                </div>
					<form id="form" name="form" method="POST" action="<?php echo $editFormAction; ?>" class="needs-validation" novalidate>
					<table class="table table-striped table-hover table-sm">
						<thead class="thead-dark">
							<tr>
								<th>项目</th>
								<th>信息</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>教师id</td>
								<td><input required readonly="readonly" type="text" class="form-control" id="teaid" name="teaid" value="<?php echo $row_Recordset1['teaid']; ?>" /><div class="invalid-feedback">此为必填项</div></td>
							</tr>
							<tr>
								<td>姓名</td>
								<td><input required type="text" class="form-control" id="teaname" name="teaname" value="<?php echo $row_Recordset1['teaname']; ?>"/><div class="invalid-feedback">此为必填项</div></td>
							</tr>
							<tr>
								<td>性别</td>
								<td>
									<input required type="radio" class="form-check-input" name="sex" value="男" <?php if($row_Recordset1['sex']=="男") echo("checked");?>>男
									<br>
									<input required type="radio" class="form-check-input" name="sex" value="女" <?php if($row_Recordset1['sex']=="女") echo("checked");?>>女
								</td>
							</tr>
							<tr>
								<!--<td>所属学院</td>
								<td><input type="text" class="form-control" id="collegename" name="collegename" value="<?php echo $row_Recordset1['collegename']; ?>"/></td>-->
								<div class="form-group">
									<td><label for="courselabel8">所在院系</label></td>
									<td><select class="form-control" id="collegename" name="collegename" required>
										<option selected value="<?php echo $row_Recordset1['collegename']; ?>">目前:<?php echo $row_Recordset1['collegename']; ?></option>
										<option disabled>------</option>
										<?php do { ?>
										<option value="<?php echo $row_Recordset2['collegename']; ?>"><?php echo $row_Recordset2['collegename']; ?></option>
										<?php } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2)); ?>
									</select><div class="invalid-feedback">此为必填项</div></td>
								</div>
							</tr>
							<!--
							<tr>
								<td>简介</td>
								<td><input type="text" class="form-control" id="introduction" name="introduction" value="<?php echo $row_Recordset1['introduction']; ?>"/><div class="invalid-feedback">此为必填项</div></td>
							</tr>
							-->
							<tr>
								<td>操作</td>
								<td><button type="submit" name="submit" class="btn btn-primary">提交</button></td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="MM_update" value="form" />
					</form>
					
				</div>
				<div class="col-md-4">
					 
					<a class="btn active btn-block btn-md btn-primary" href="manage-tea.php">返回教师管理面板</a>
						
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

	function GetQueryString(name)
	{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  decodeURI(r[2]); return null;    //decodeURI()是将encodeURI转换的字符转换回来
	}
	
	$(document).ready(function(){
		var url_Success = GetQueryString("Success");
		var url_teaid = GetQueryString("teaid");
		console.log("Success="+url_Success);
		console.log("teaid="+url_teaid);
		if(url_Success == 'true' && url_teaid != null)
		{
			$("#SuccessInfo").show();
			$("#SuccessText").text('[教师ID:'+url_teaid+'] 【修改信息】: 操作成功');
		}
		else
		{
			$("#SuccessInfo").hide();
		}
	});
</script>
</body>
</html>
<?php mysqli_free_result($Recordset1); ?>
<?php mysqli_free_result($Recordset2); ?>