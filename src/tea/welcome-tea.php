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
	$colname_Recordset1 = "-1";
	if (isset($_SESSION['MM_Username'])) {
		$colname_Recordset1 = $_SESSION['MM_Username'];
	}
	
	// 转义
	$loginUsername = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
    // 构造查询
	
	$query_Recordset1 = "SELECT * FROM course WHERE teaid = ('$loginUsername') AND `cou_disable` = 0 ORDER BY cou_disable ASC, courseid ASC";
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
    <title>教师面板-选课系统</title>
    <meta name="HandheldFriendly" content="true">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="theme-color" content="#333344">
    <link rel="stylesheet" href="../style/bootstrap.min.css">
      <script src="../style/jquery.min.js"></script>
      <script src="../style/popper.min.js"></script>
      <script src="../style/bootstrap.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron">
                <h2>
					你好，<?php echo $_SESSION['MM_RealName']; ?>！
					<?php if($_SESSION['MM_tea_ReleaseAllow']==0 || $_SESSION['MM_tea_CourseModAllow']==0 || $_SESSION['MM_tea_StuDelAllow']==0 || $_SESSION['MM_tea_StuFinAllow']==0)echo "<span class=\"badge badge-secondary\">受限中</span>"; ?> 
					<?php if($_SESSION['MM_tea_ReleaseAllow']==0)echo "<span class=\"badge badge-secondary\">禁止发布课程</span>"; ?>
					<?php if($_SESSION['MM_tea_CourseModAllow']==0)echo "<span class=\"badge badge-secondary\">禁止修改课程</span>"; ?>
					<?php if($_SESSION['MM_tea_StuDelAllow']==0)echo "<span class=\"badge badge-secondary\">禁止剔除学生</span>"; ?>
					<?php if($_SESSION['MM_tea_StuFinAllow']==0)echo "<span class=\"badge badge-secondary\">禁止通过学生</span>"; ?>
				</h2>
                    <p>
                        请选择您要进行的操作
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="text-center">
							以下是您发布的课程及情况
						</h3>
						<br>
						<input class="form-control" id="SearchInput" type="text" placeholder="搜索..">
						<br>
                        <table class="table table-striped table-hover table-sm" id="CourseInfo">
                            <thead class="thead-dark">
                                <tr>
                                    <th>课程代码</th>
                                    <th>课程名称</th>
                                    <th>教师编号</th>
                                    <th><!--已选-->人数</th>
									<!--<th>总人数</th>-->
									<th>上课时间</th>
									<th>地点</th>
									<!--
									<th>学分</th>
									<th>讲授学时</th>
									<th>实验学时</th>
									-->
									<th>先行课程</th>
									<th colspan="2">操作</th>
                                </tr>
                            </thead>
                            <tbody id="CourseTable">
							<?php do { ?>
                                <tr>
                                    <td><?php echo $row_Recordset1['courseid']; ?></td>
                                    <td><?php echo $row_Recordset1['coursename']; ?></td>
                                    <td><?php echo $row_Recordset1['teaid']; ?></td>
									<td><?php echo $row_Recordset1['selected']; ?>/<?php echo $row_Recordset1['total']; ?></td>
                                    <!--
									<td><?php echo $row_Recordset1['selected']; ?></td>
									<td><?php echo $row_Recordset1['total']; ?></td>
									-->
									<td><?php echo $row_Recordset1['classtime']; ?></td>
									<td><?php echo $row_Recordset1['classroom']; ?></td>
									<!--
									<td><?php echo $row_Recordset1['credit']; ?></td>
									<td><?php echo $row_Recordset1['shangketime']; ?></td>
									<td><?php echo $row_Recordset1['shiyantime']; ?></td>
									-->
									<td><?php echo $row_Recordset1['prevcourse']; ?></td>
									<td><a class="btn active btn-primary btn-sm" href="details-course.php?courseid=<?php echo $row_Recordset1['courseid']; ?>">详情</a></td>
									<!--<td><a href="#">备用</a></td>-->
                                </tr>
							<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
						<a class="btn active btn-block btn-md btn-success <?php if($_SESSION['MM_tea_ReleaseAllow']==0)echo "disabled"; ?>" href="add-course.php">发布课程</a>
                        <a class="btn active btn-block btn-md btn-primary" href="changed-tea.php">修改密码</a>
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
			$("#CourseTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
	</script>
</body>

</html>
<?php mysqli_free_result($Recordset1); ?>