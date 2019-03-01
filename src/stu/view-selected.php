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
?>


<?php
// 转义
$stuid = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
// 所有属于stuid的课程，按照courseid排序
$query_Recordset1 = "SELECT course.*,stucourse.*,teaname FROM course,stucourse,teacher WHERE course.courseid=stucourse.courseid AND stucourse.teaid=teacher.teaid AND stucourse.stuid=('$stuid') ORDER BY stucourse.fin ASC,stucourse.courseid ASC";
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
    <title>历史已选课程-学生面板-选课系统</title>
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
					历史已选课程
				</h2>
				<p>
					你可以同时使用多个查询框进行筛选
				</p>
			</div>
			<div class="row">
				<div class="col-md-8">
					<!--
					<div class="form-row align-items-center col-auto">
					<div class="col-auto">
						<input class="form-control" id="SearchInput1" type="text" placeholder="模糊搜索...">
					</div>
					<div class="col-auto">
					<select class="form-control" id="StatusControl1">
						<option value="课程进行中" selected>课程进行中</option>
						<option value="课程结束">课程结束</option>
					</select>
					</div>
					<div class="col-auto">
					<select class="form-control" id="StatusControl2">
						<option value="已获学分">已获学分</option>
						<option value="未获学分" selected>未获学分</option>
					</select>
					</div>
					
					<div class="col-auto">
					
					</div>
					</div>
					-->
					<!--startprint-->
				    <table class="table table-striped table-hover table-sm" id="myTable">
                            <thead class="thead-dark">
                                <tr>
                                    <!--<th>课程代码</th>-->
                                    <th>课程名称</th>
                                    <th>教师<!--编号--></th>
                                    <!--
									<th>已选人数</th>
									<th>总人数</th>
									-->
									<th>上课时间</th>
									<th>地点</th>
									<th>先行课程</th>
									<th>学分</th>
									<!--
									<th>讲授学时</th>
									<th>实验学时</th>
									-->
									<th>状态</th>
									<!--<th>操作</th>-->
                                </tr>
                            </thead>
                            <tbody id="CourseTable">
							<?php do { ?>
                                <tr>
                                    <!--
									<td><?php echo $row_Recordset1['courseid']; ?></td>
									-->
                                    <td><?php echo $row_Recordset1['coursename']; ?> [<?php echo $row_Recordset1['courseid']; ?>]</td>
                                    <td><?php echo $row_Recordset1['teaname']; ?><!-- [<?php echo $row_Recordset1['teaid']; ?>]--></td>
									<!--
                                    <td><?php echo $row_Recordset1['selected']; ?></td>
									<td><?php echo $row_Recordset1['total']; ?></td>
									-->
									<td><?php echo $row_Recordset1['classtime']; ?></td>
									<td><?php echo $row_Recordset1['classroom']; ?></td>
									<td><?php echo $row_Recordset1['prevcourse']; ?></td>
									<td><?php echo $row_Recordset1['credit']; ?></td>
									<!--
									<td><?php echo $row_Recordset1['shangketime']; ?></td>
									<td><?php echo $row_Recordset1['shiyantime']; ?></td>
									-->
									<td>
										<?php if($row_Recordset1['cou_disable']==0){echo "<span class=\"badge badge-success\">课程进行中</span>";}else{echo "<span class=\"badge badge-warning\">课程结束</span>";} ?>
										<?php if($row_Recordset1['fin']==1){echo "<span class=\"badge badge-success\">已获学分</span>";}else{echo "<span class=\"badge badge-danger\">未获学分</span>";} ?>
										<!--
										<span class="badge badge-secondary">课程结束/进行中</span>
										<span class="badge badge-secondary">已获学分/未获学分</span>
										-->
									</td>
									<!--<td><a class="btn active btn-success btn-sm"  href="#">Temp</a></td>-->
                                </tr>
							<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
                            </tbody>
                        </table>
						<!--endprint-->
				</div>
				<div class="col-md-4">
					<a class="btn active btn-block btn-md btn-info" href="javascript:doPrint();">打印</a>
					
					<a class="btn active btn-block btn-md btn-primary" href="welcome-stu.php">返回学生面板</a>
						
					<a class="btn btn-danger btn-md btn-block" href="<?php echo $logoutAction ?>" onclick="return confirm('确定要注销吗？')" >退出系统</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="../style/ddtf.js"></script>
<script>
	$('#myTable').ddTableFilter();
</script>
<script>
	/*
	$(document).ready(function(){
		
		$("#SearchInput1").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#CourseTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		
		$("#StatusControl1").change(function() {
			var value = $(this).val().toLowerCase();
			$("#CourseTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		
		$("#StatusControl2").change(function() {
			var value = $(this).val().toLowerCase();
			$("#CourseTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
	*/
	
	function doPrint() {  
        bdhtml=window.document.body.innerHTML;      
        sprnstr="<!--startprint-->";    
        eprnstr="<!--endprint-->";
        prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);      
        prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));      
        window.document.body.innerHTML=prnhtml;   
        window.print();      
	}
</script>
</body>
</html>
<?php mysqli_free_result($Recordset1); ?>