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

//HTML to STRING
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction.= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Courseform")) {
	// 转义
	$courseid = mysqli_real_escape_string($dbcon, $_GET['courseid']);
    $coursename = mysqli_real_escape_string($dbcon, $_POST['coursename']);
	$total = mysqli_real_escape_string($dbcon, $_POST['total']);
	$classtime = mysqli_real_escape_string($dbcon, $_POST['classtime']);
	$classroom = mysqli_real_escape_string($dbcon, $_POST['classroom']);
	$credit = mysqli_real_escape_string($dbcon, $_POST['credit']);
	$shangketime = mysqli_real_escape_string($dbcon, $_POST['shangketime']);
	$shiyantime = mysqli_real_escape_string($dbcon, $_POST['shiyantime']);
	$prevcourse = mysqli_real_escape_string($dbcon, $_POST['prevcourse']);
    // 构造查询
	$UpdateQuery = "UPDATE `course` SET prevcourse=('$prevcourse'), coursename=('$coursename'), total=('$total'), classtime=('$classtime'), classroom=('$classroom'), credit=('$credit'), shangketime=('$shangketime'), shiyantime=('$shiyantime') WHERE courseid=('$courseid')";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
    header(sprintf("Location: details-course.php?courseid=%s&ModSuccess=true", $courseid));
}
?>

<?php //删除学生课程
if ((isset($_GET['courseid'])) && ($_GET['courseid'] != "") && ($_GET['delstuid']) && ($_GET['delstuid'] != "")) {
    // 转义
    $courseid = mysqli_real_escape_string($dbcon, $_GET['courseid']);
	$stuid = mysqli_real_escape_string($dbcon, $_GET['delstuid']);
    // 构造查询
	$DeleteQuery = "DELETE FROM stucourse WHERE stuid=('$stuid') AND courseid=('$courseid')";
    $ResultRS = mysqli_query($dbcon, $DeleteQuery) or die(mysqli_error($dbcon));
	
	$UpdateQuery = "UPDATE `course` SET `selected` = `selected`-1 WHERE `course`.`courseid` = ('$courseid')";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
    header(sprintf("Location: details-course.php?courseid=%s&stuid=%s&DelSuccess=true", $courseid, $stuid));
	exit;
}
?>

<?php //完成学生课程
if ((isset($_GET['courseid'])) && ($_GET['courseid'] != "") && ($_GET['finstuid']) && ($_GET['finstuid'] != "")) {
    // 转义
    $courseid = mysqli_real_escape_string($dbcon, $_GET['courseid']);
	$stuid = mysqli_real_escape_string($dbcon, $_GET['finstuid']);
    // 构造查询
	$UpdateQuery = "UPDATE `stucourse` SET `fin` = '1' WHERE `stucourse`.`stuid` = ('$stuid') AND `stucourse`.`courseid` = ('$courseid');";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
    header(sprintf("Location: details-course.php?courseid=%s&stuid=%s&FinSuccess=true", $courseid, $stuid));
	exit;
}
?>


<?php
if ((isset($_GET['courseid'])) && ($_GET['courseid'] != "")) {
	
}
	
	// 转义
	$courseid = mysqli_real_escape_string($dbcon, $_GET['courseid']);
    // 构造查询
	
	$query_Recordset1 = "SELECT * FROM `course` WHERE `courseid` = ('$courseid')";
	$Recordset1 = mysqli_query($dbcon, $query_Recordset1) or die(mysqli_error($dbcon));
	$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
	
	$query_Recordset2 = "SELECT * FROM `stucourse` WHERE `courseid` = ('$courseid')";
	$Recordset2 = mysqli_query($dbcon, $query_Recordset2) or die(mysqli_error($dbcon));
	$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
	$totalRows_Recordset2 = mysqli_num_rows($Recordset2);
	
	$query_Recordset3 = "SELECT DISTINCT `coursename` FROM `course` WHERE `courseid` != ('$courseid') ORDER BY `coursename` ASC";
	$Recordset3 = mysqli_query($dbcon, $query_Recordset3) or die(mysqli_error($dbcon));
	$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
	$totalRows_Recordset3 = mysqli_num_rows($Recordset3);
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>课程详情-管理员面板-选课系统</title>
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
					课程详情
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
					<h3 class="text-center">课程信息</h3>
					<form id="Courseform" name="Courseform" method="POST" action="<?php echo $editFormAction; ?>" class="needs-validation" novalidate>
						<table class="table table-striped table-hover table-sm">
							<thead>
								<tr>
                                    <th>课程代码</th>
                                    <th>课程名称</th>
                                    <th>教师编号</th>
                                    <th>已选人数</th>
									<th>总人数</th>
									<!--
									<th>学分</th>
									<th>讲授学时</th>
									<th>实验学时</th>
									<th>先行课程</th>
									<th>操作</th>
									-->
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input required type="text" class="form-control" id="courseid" name="courseid" value="<?php echo $row_Recordset1['courseid']; ?>" readonly="readonly" /></td>
									<td><input required pattern="^.{1,20}$" type="text" class="form-control" id="coursename" name="coursename" value="<?php echo $row_Recordset1['coursename']; ?>"/></td>
									<td><input required type="text" class="form-control" id="teaid" name="teaid" value="<?php echo $row_Recordset1['teaid']; ?>" readonly="readonly"/></td>
									<td><input required type="text" class="form-control" id="selected" name="selected" value="<?php echo $row_Recordset1['selected']; ?>" readonly="readonly"/></td>
									<td><input required pattern="^(1[0-9][0-9]|[1-9][0-9]|[1-9]){1,3}$" type="text" class="form-control" id="total" name="total" value="<?php echo $row_Recordset1['total']; ?>" /></td>
								</tr>
							</tbody>
						</table>
						<table class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th>上课时间</th>
									<th>地点</th>
									<th>学分</th>
									<!--
									<th>讲授学时</th>
									<th>实验学时</th>
									-->
									<th>先行课程</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input required pattern="^.{1,30}$" type="text" class="form-control" id="classtime" name="classtime" value="<?php echo $row_Recordset1['classtime']; ?>" /></td>
									<td><input required pattern="^.{1,30}$" type="text" class="form-control" id="classroom" name="classroom" value="<?php echo $row_Recordset1['classroom']; ?>" /></td>
									<td><input required pattern="^[1-5]{1}$" type="text" class="form-control" id="credit" name="credit" value="<?php echo $row_Recordset1['credit']; ?>" /></td>
									<!--<td><input required hidden type="text" class="form-control" id="shangketime" name="shangketime" value="<?php echo $row_Recordset1['shangketime']; ?>" /></td>
									<td><input required hidden type="text" class="form-control" id="shiyantime" name="shiyantime" value="<?php echo $row_Recordset1['shiyantime']; ?>" /></td>-->
									<td>
										<select class="form-control" id="prevcourse" name="prevcourse" required>
											<option value="无">无</option>
											<?php do { ?>
											<option value="<?php echo $row_Recordset3['coursename']; ?>"><?php echo $row_Recordset3['coursename']; ?></option>
											<?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
											<option disabled>-------</option>
											<option selected value="<?php echo $row_Recordset1['prevcourse']; ?>"><?php echo $row_Recordset1['prevcourse']; ?></option>
										</select>
									</td>
									<td><button type="submit" name="submit" class="btn btn-primary">修改</button></td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" name="MM_update" value="Courseform" />
					</form>
					
					<h3 class="text-center">课程详情</h3>
					<br>
					<div>
					<br>
					<input class="form-control" id="SearchInput" type="text" placeholder="搜索..">
					<br>
						<table class="table table-striped table-hover table-sm">
							<thead>
								<tr>
                                    <th>学生id</th>
                                    <th>学生名称</th>
                                    <th>学院ID</th>
                                    <!--<th>专业</th>-->
									<th>班级</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody  id="StuTable">
								<?php do { ?>
								<tr>
									<td><?php echo $row_Recordset2['stuid']; ?></td>
									<td><?php echo $row_Recordset2['stuname']; ?></td>
									<td><?php echo $row_Recordset2['collegeid']; ?></td>
									<!--<td><?php echo $row_Recordset2['major']; ?></td>-->
									<td><?php echo $row_Recordset2['class']; ?></td>
									<td>
									<a class="btn btn-danger active btn-sm" onclick="return confirm('确定要删除吗？')" href="details-course.php?courseid=<?php echo $row_Recordset1['courseid']; ?>&delstuid=<?php echo $row_Recordset2['stuid']; ?>">删除</a>
									<a class="btn active btn-sm <?php if($row_Recordset2['fin']=='1'){echo "disabled btn-secondary";}else{echo "btn-primary";} ?>" href="details-course.php?courseid=<?php echo $row_Recordset1['courseid']; ?>&finstuid=<?php echo $row_Recordset2['stuid']; ?>"><?php if($row_Recordset2['fin']=='1'){echo "课程已通过";}else{echo "通过课程";} ?></a>
									</td>
								</tr>
								<?php } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2)); ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-4">
					<a class="btn active btn-block btn-md btn-primary" href="manage-course.php">返回课程管理面板</a>
					<a class="btn btn-danger btn-md btn-block" href="<?php echo $logoutAction ?>" onclick="return confirm('确定要注销吗？')" >退出系统</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("#SearchInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#StuTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
	
	function GetQueryString(name)
	{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  decodeURI(r[2]); return null;    //decodeURI()是将encodeURI转换的字符转换回来
	}
	
	$(document).ready(function(){
		
		var url_DelSuccess = GetQueryString("DelSuccess");
		var url_ModSuccess = GetQueryString("ModSuccess");
		var url_FinSuccess = GetQueryString("FinSuccess");
		var url_stuid = GetQueryString("stuid");
		console.log("DelSuccess="+url_DelSuccess);
		console.log("ModSuccess="+url_ModSuccess);
		console.log("FinSuccess="+url_FinSuccess);
		console.log("stuid="+url_stuid);
		
		if(url_ModSuccess == 'true')
		{
			$("#SuccessInfo").show();
			$("#SuccessText").text('【修改信息】: 操作成功');
		}
		else if(url_DelSuccess == 'true' && url_stuid != null)
		{
			$("#SuccessInfo").show();
			$("#SuccessText").text('[学生ID:'+url_stuid+']【删除学生】: 操作成功');
		}
		else if(url_FinSuccess == 'true' && url_stuid != null)
		{
			$("#SuccessInfo").show();
			$("#SuccessText").text('[学生ID:'+url_stuid+']【结束课程】: 操作成功');
		}
		else
		{
			$("#SuccessInfo").hide();
		}
	});
</script>
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
<?php mysqli_free_result($Recordset2); ?>
<?php mysqli_free_result($Recordset3); ?>