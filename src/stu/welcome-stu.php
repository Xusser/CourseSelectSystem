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
	$_SESSION['MM_stu_SelectAllow'] = 0;
else
{
	$_SESSION['MM_stu_SelectAllow'] = 1;
}
?>
<?php
// 构造查询 课程 进行中 可选取
$query_Recordset1 = "SELECT course.*,teacher.teaname FROM `course`,`teacher` WHERE `cou_disable` = 0 AND `pick_allowed` = 1 AND teacher.teaid=course.teaid ORDER BY `course`.`courseid` ASC";
//$query_Recordset1 = "SELECT DISTINCT course.courseid,course.coursename,course.teaid,selected,total,course.classtime,classroom,credit,shangketime,shiyantime,prevcourse,teaname FROM course,teacher,stucourse WHERE teacher.teaid=course.teaid AND stucourse.fin='0' AND course.cou_disable='0' AND course.pick_allowed='1' ORDER BY courseid ASC";
$Recordset1 = mysqli_query($dbcon, $query_Recordset1) or die(mysqli_error($dbcon));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

// 转义
$stuid = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
// 构造查询 个人信息
$query_Recordset2 = "SELECT * FROM `student` WHERE `stuid` = ('$stuid')";
$Recordset2 = mysqli_query($dbcon, $query_Recordset2) or die(mysqli_error($dbcon));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);

// 构造查询 当前学生的 进行中的 未完成的课程
$query_Recordset3 = "SELECT course.coursename,course.classtime,classroom,fin,cou_disable FROM course,stucourse WHERE course.courseid=stucourse.courseid AND stucourse.stuid=('$stuid') AND stucourse.fin='0' AND course.cou_disable='0'";
$Recordset3 = mysqli_query($dbcon, $query_Recordset3) or die(mysqli_error($dbcon));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

// 构造查询
$Recordset4 = mysqli_query($dbcon, $query_Recordset3) or die(mysqli_error($dbcon));
$resultarray = array();
while ($row = mysqli_fetch_assoc($Recordset4))// mysqli_fetch_assoc拿到的是数组里面的数组
{
    $resultarray[] = $row['coursename'];
}

?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生面板-选课系统</title>
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
					你好，<?php echo $_SESSION['MM_RealName']; ?>！<?php if($_SESSION['MM_stu_SelectAllow']==0)echo "<span class=\"badge badge-secondary\">受限中</span>"; ?>
				</h2>
                    <p>
                        请选择您要进行的操作
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="text-center">
							<?php if($_SESSION['MM_stu_SelectAllow']==0)echo "<font color=\"red\">你现在不被允许进行选课操作！</font>"; else echo"<font color=\"blue\">以下是你的可选课程</font>"; ?>
						</h3>
						<br>
						<input class="form-control" id="SearchInput" type="text" placeholder="搜索..">
						<br>
                        <table class="table table-striped table-hover table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>课程代码</th>
                                    <th>课程名称</th>
                                    <th>教师<!--编号--></th>
                                    <th>已选人数</th>
									<th>总人数</th>
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
                            <tbody id="CourseTable">
							<?php do { ?>
                                <tr>
                                    <td><?php echo $row_Recordset1['courseid']; ?></td>
                                    <td><?php echo $row_Recordset1['coursename']; ?></td>
                                    <td><?php echo $row_Recordset1['teaname']; ?> [<?php echo $row_Recordset1['teaid']; ?>]</td>
                                    <td><?php echo $row_Recordset1['selected']; ?></td>
									<td><?php echo $row_Recordset1['total']; ?></td>
									<td><?php echo $row_Recordset1['classtime']; ?></td>
									<td><?php echo $row_Recordset1['classroom']; ?></td>
									<td><?php echo $row_Recordset1['credit']; ?></td>
									<!--
									<td><?php echo $row_Recordset1['shangketime']; ?></td>
									<td><?php echo $row_Recordset1['shiyantime']; ?></td>
									-->
									<td><?php echo $row_Recordset1['prevcourse']; ?></td>
									<!-- 如果选满==不给选 || 已经选了==不给选 -->
									<td><a class="btn <?php if($_SESSION['MM_stu_SelectAllow']==0 || $row_Recordset1['selected']>=$row_Recordset1['total'] || in_array($row_Recordset1['coursename'],$resultarray)){echo "disabled btn-danger";}else{echo "active btn-success";} ?> btn-sm"  href="select-course.php?courseid=<?php echo $row_Recordset1['courseid']; ?>"><?php if($row_Recordset1['selected']>=$row_Recordset1['total'] || $_SESSION['MM_stu_SelectAllow']==0 || in_array($row_Recordset1['coursename'],$resultarray)){echo "无法报名";}else{echo "我要这个!";} ?></a></td>
                                </tr>
							<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
						<h4 class="text-center">
							你的进行中的课程<!--速查-->
						</h4>
						<!--<br>
						<input class="form-control" id="SearchInput2" type="text" placeholder="已选课程速查...">
						<br>-->
						<table class="table table-striped table-sm">
							<thead class="thead-dark">
                                <tr>
                                    <th>课程</th>
                                    <th>上课时间</th>
									<th>地点</th>
									<!--<th>状态</th>-->
                                </tr>
                            </thead>
                            <tbody>
							<?php do { ?>
								<tr>
                                    <td><?php echo $row_Recordset3['coursename']; ?></td>
                                    <td><?php echo $row_Recordset3['classtime']; ?></td>
									<td><?php echo $row_Recordset3['classroom']; ?></td>
									<!--<td><?php echo $row_Recordset3['fin']; ?></td>-->
                                </tr>
							<?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                            </tbody>
						</table>
						<br>
						<a id="modal-682641" class="btn active btn-block btn-md btn-primary" href="#modal-container-682641">个人详细信息</a>
						
						<a class="btn active btn-block btn-md btn-primary" href="view-selected.php">查询历史课程</a>
						
                        <a class="btn active btn-block btn-md btn-primary" href="changed-stu.php">修改密码</a>
						
						<a class="btn btn-danger btn-md btn-block" href="<?php echo $logoutAction ?>" onclick="return confirm('确定要注销吗？')" >退出系统</a>
						<br>
                    </div>
                </div>
				
				<!--Modal-->
				<div class="modal fade text-center" id="modal-container-682641" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document" style="display: inline-block; width: auto;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">
								个人详细信息
							</h5> 
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>项目</th>
                                    <th>信息</th>
                                </tr>
                            </thead>
                            <tbody>
								<tr>
                                    <td>学生ID</td>
                                    <td><?php echo $row_Recordset2['stuid']; ?></td>
                                </tr>
								<tr>
                                    <td>姓名</td>
                                    <td><?php echo $row_Recordset2['stuname']; ?></td>
                                </tr>
								<tr>
                                    <td>性别</td>
                                    <td><?php echo $row_Recordset2['sex']; ?></td>
                                </tr>
								<tr>
                                    <td>学院ID</td>
                                    <td><?php echo $row_Recordset2['collegeid']; ?></td>
                                </tr>
								<!--
								<tr>
                                    <td>专业</td>
                                    <td><?php echo $row_Recordset2['major']; ?></td>
                                </tr>
								-->
								<tr>
                                    <td>班级</td>
                                    <td><?php echo $row_Recordset2['class']; ?></td>
                                </tr>
								<tr>
                                    <td>密码</td>
                                    <td><?php echo $row_Recordset2['stupassword']; ?></td>
                                </tr>
                            </tbody>
							</table>
						</div>
						<div class="modal-footer">
							 
							<button type="button" class="btn btn-secondary" data-dismiss="modal">
								关闭
							</button>
						</div>
					</div>
				</div>
				</div>
				<!--Modal-->
				
            </div>
        </div>
    </div>
	<script>
	$(document).ready(function(){
		$("#SearchInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#CourseTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
	
	$(document).ready(function(){
		$("#modal-682641").click(function(){
			$("#modal-container-682641").modal();
		});
	});
	</script>
</body>

</html>
<?php mysqli_free_result($Recordset1); ?>
<?php mysqli_free_result($Recordset2); ?>
<?php mysqli_free_result($Recordset3); ?>
<?php mysqli_free_result($Recordset4); ?>