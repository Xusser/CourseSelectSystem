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
$RecordQuery = "SELECT * FROM student ORDER BY stuid ASC";
$Recordset1 = mysqli_query($dbcon, $RecordQuery) or die(mysqli_error($dbcon));
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
    <title>学生管理-管理员面板-选课系统</title>
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
					管理学生信息
				</h2>
				<p>
					在这里你可以添加\注销\修改学生信息
				</p>
			</div>
			<div class="row">
				<div class="col-md-8">
				<div class="alert alert-dismissable alert-success" id="SuccessInfo">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<div id="SuccessText"></div>
                </div>
				<div id="card-720407">
						<div class="card">
							<div class="card-header">
								 <a class="card-link collapsed" data-toggle="collapse" data-parent="#card-720407" href="#card-element-174929">添加学生信息</a>
							</div>
							<div id="card-element-174929" class="collapse">
								<div class="card-body">
									<h3>
										添加学生信息
									</h3>
									<form role="form" method="POST" action="add-stu.php" class="needs-validation" novalidate>
										<div class="form-group">
								
											<label for="tealabel1">
												姓名
											</label>
											<input type="text" class="form-control" id="stuname" name="stuname" required />
											<div class="invalid-feedback">此为必填项</div>
										</div>
										<div class="form-group">
											<label for="tealabel2">
												性别
											</label>
											<div class="form-check">
											
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="sex" value="男" required>男<div class="invalid-feedback">请选择性别</div>
												</label>
											</div>
											<div class="form-check">
											
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="sex" value="女" required>女<div class="invalid-feedback">请选择性别</div>
												</label>
											</div>
											 
										</div>
										<div class="form-group">
											
											<!--<label for="tealabel3">
												院系id
											</label>
											<input type="text" class="form-control" id="collegeid" name="collegeid"/>-->
											<div class="form-group">
												<td><label for="courselabel8">所在院系</label></td>
												<td><select class="form-control" id="collegeid" name="collegeid" required>
													<option value="">请选择</option>
													<option disabled>------</option>
													<?php do { ?>
													<option value="<?php echo $row_Recordset2['collegeid']; ?> "><?php echo $row_Recordset2['collegename']; ?>[<?php echo $row_Recordset2['collegeid']; ?>]</option>
													<?php } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2)); ?>
												</select></td>
												<div class="invalid-feedback">请选择院系</div>
											</div>
										</div>
										<div hidden class="form-group">
											
											<label for="tealabel3">
												专业
											</label>
											<input type="text" class="form-control" id="major" name="major" value="Default" />
											<div class="invalid-feedback">此为必填项</div>
										</div>
										<div class="form-group">
											
											<label for="tealabel3">
												班级
											</label>
											<input type="text" class="form-control" id="class" name="class" required />
											<div class="invalid-feedback">此为必填项</div>
										</div>
										<div class="form-group">
											
											<label for="tealabel4">
												初始密码
											</label>
											<input type="text" class="form-control" id="stupassword" name="stupassword" required />
											<div class="invalid-feedback">此为必填项</div>
										</div>
										<button type="submit" name="submit" class="btn btn-primary">
											提交
										</button>
									</form>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								 <a class="collapsed card-link" data-toggle="collapse" data-parent="#card-720407" href="#card-element-676001">学生信息</a>
							</div>
							<div id="card-element-676001" class="collapse show">
								<div class="card-body">
									<h3>
										学生信息
									</h3>
									<table class="table table-striped table-hover table-sm">
										<thead class="thead-dark">
											<tr>
												<th>
													学生id
												</th>
												<th>
													姓名
												</th>
												<th>
													性别
												</th>
												<th>
													院系id
												</th>
												<!--
												<th>
													专业
												</th>
												-->
												<th>
													班级
												</th>
												<th colspan="1">
													操作
												</th>
											</tr>
										</thead>
										<tbody>
										<?php do { ?>
											<tr>
												<td <?php if($row_Recordset1['stu_disable']==1){echo "class=\"table-info\"";} ?>>
												<?php echo $row_Recordset1['stuid']; ?>
												</td>
												<td <?php if($row_Recordset1['stu_disable']==1){echo "class=\"table-info\"";} ?>>
													<?php echo $row_Recordset1['stuname']; ?>
												</td>
												<td <?php if($row_Recordset1['stu_disable']==1){echo "class=\"table-info\"";} ?>>
													<?php echo $row_Recordset1['sex']; ?>
												</td>
												<td <?php if($row_Recordset1['stu_disable']==1){echo "class=\"table-info\"";} ?>>
													<?php echo $row_Recordset1['collegeid']; ?>
												</td>
												<!--<td <?php if($row_Recordset1['stu_disable']==1){echo "class=\"table-info\"";} ?>>
													<?php echo $row_Recordset1['major']; ?>
												</td>-->
												<td <?php if($row_Recordset1['stu_disable']==1){echo "class=\"table-info\"";} ?>>
													<?php echo $row_Recordset1['class']; ?>
												</td>
												<td <?php if($row_Recordset1['stu_disable']==1){echo "class=\"table-info\"";} ?>>
													<a class="btn active btn-primary btn-sm <?php if($row_Recordset1['stu_disable']==1){echo "disabled";}?>" href="modify-stu.php?stuid=<?php echo $row_Recordset1['stuid']; ?>">修改</a>
													<a class="btn active btn-danger btn-sm <?php if($row_Recordset1['stu_disable']==1){echo "disabled";}?>" href="delete-stu.php?stuid=<?php echo $row_Recordset1['stuid']; ?>" onclick="return confirm('确定要注销吗？')">注销</a>
													<?php if($row_Recordset1['stu_disable']==1){echo "<font color=\"red\">账户已注销！</font>";} ?>
												</td>
											</tr>
										<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					 
					<a class="btn active btn-block btn-md btn-primary" href="welcome-admin.php">返回管理员面板</a>
						
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
		var url_AddSuccess = GetQueryString("AddSuccess");
		var url_DelSuccess = GetQueryString("DelSuccess");
		var url_stuid = GetQueryString("stuid");
		console.log("AddSuccess="+url_AddSuccess);
		console.log("DelSuccess="+url_DelSuccess);
		console.log("stuid="+url_stuid);
		if(url_AddSuccess == 'true')
		{
			$("#SuccessInfo").show();
			$("#SuccessText").text('【添加学生】: 操作成功');
		}
		else if(url_DelSuccess == 'true' && url_stuid != null)
		{
			$("#SuccessInfo").show();
			$("#SuccessText").text('[学生ID:'+url_stuid+'] 【注销学生】: 操作成功');
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