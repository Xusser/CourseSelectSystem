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
    $stu1 = mysqli_real_escape_string($dbcon, $_POST['stu1']);
    $tea1 = mysqli_real_escape_string($dbcon, $_POST['tea1']);
	$tea2 = mysqli_real_escape_string($dbcon, $_POST['tea2']);
	$tea3 = mysqli_real_escape_string($dbcon, $_POST['tea3']);
	$tea4 = mysqli_real_escape_string($dbcon, $_POST['tea4']);
	$global1 = mysqli_real_escape_string($dbcon, $_POST['global1']);
	
	// 构造查询
	$UpdateQuery = "UPDATE `privilege` SET `stu_SelectAllow`=('$stu1'),`tea_ReleaseAllow`=('$tea1'),`tea_CourseModAllow`=('$tea2'),`tea_StuDelAllow`=('$tea3'),`tea_StuFinAllow`=('$tea4'),`global_ban`=('$global1')";
	$ResultRS = mysqli_query($dbcon, $UpdateQuery) or die(mysqli_error($dbcon));
	
	header("Location: welcome-admin.php?PrivModify=true&Success=true");
}
?>

<?php
// 转义
$adminid = mysqli_real_escape_string($dbcon, $_SESSION['MM_Username']);
// 构造查询
$query_Recordset1 = "SELECT * FROM `admin` WHERE `adminid` = ('$adminid')";
$Recordset1 = mysqli_query($dbcon, $query_Recordset1) or die(mysqli_error($dbcon));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

// 构造查询
$query_Recordset2 = "SELECT * FROM `privilege`";
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
    <title>管理员面板-选课系统</title>
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
					你好，管理员！
				</h2>
                    <p>
                        请选择您要进行的操作
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="jumbotron">
                                    <h2>
									管理教师信息
								</h2>
                                    <p>
                                        在这里你可以添加\删除\修改教师信息
                                    </p>
                                    <p>
                                        <a class="btn btn-primary btn-large" href="manage-tea.php">进入</a>
                                    </p>
                                </div>
								<div class="jumbotron">
                                    <h2>
									管理学院信息
								</h2>
                                    <p>
                                        在这里你可以添加\删除\修改学院信息
                                    </p>
                                    <p>
                                        <a class="btn btn-primary btn-large" href="manage-college.php">进入</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="jumbotron">
                                    <h2>
									管理学生信息
									</h2>
                                    <p>
                                        在这里你可以添加\删除\修改学生信息
                                    </p>
                                    <p>
                                        <a class="btn btn-primary btn-large" href="manage-stu.php">进入</a>
                                    </p>
                                </div>
								<div class="jumbotron">
									<h2>
									管理课程信息
									</h2>
                                    <p>
                                        在这里你可以添加\删除\修改课程信息
                                    </p>
                                    <p>
                                        <a class="btn btn-primary btn-large" href="manage-course.php">进入</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-dismissable alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4>注意!</h4>
							作为管理员，你<strong>应当</strong>知道你在做什么。
                        </div>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        管理员ID
                                    </th>
                                    <th>
                                        管理员密码
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $row_Recordset1['adminid']; ?></td>
                                    <td><!--<?php echo $row_Recordset1['adminpassword']; ?>-->******</td>
                                </tr>
                            </tbody>
                        </table>
						<hr class="my-4">
						<h5 class="text-center">权限控制</h5>
						<form role="form" id="form" name="form" method="POST" action="<?php echo $editFormAction; ?>">
						<table class="table table-striped table-hover table-sm table-borderless text-center">
                            <tbody>
                                <tr>
                                    <td>学生选择课程 状态:<?php if($row_Recordset2['stu_SelectAllow']=="1") echo "<font color=\"blue\">[允许]</font>"; else echo "<font color=\"red\">[禁止]</font>";?></td>
                                    <td>
										<div class="btn-group btn-group-toggle " data-toggle="buttons">
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['stu_SelectAllow']=="1") echo("active");?>">
												<input value="1" type="radio" name="stu1" id="stu1" autocomplete="off" <?php if($row_Recordset2['stu_SelectAllow']=="1") echo("checked");?> > 允许
											</label>
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['stu_SelectAllow']=="0") echo("active");?>">
												<input value="0" type="radio" name="stu1" id="stu1" autocomplete="off" <?php if($row_Recordset2['stu_SelectAllow']=="0") echo("checked");?> > 禁止
											</label>
										</div>
									</td>
                                </tr>
								<tr>
                                    <td>教师发布课程 状态:<?php if($row_Recordset2['tea_ReleaseAllow']=="1") echo "<font color=\"blue\">[允许]</font>"; else echo "<font color=\"red\">[禁止]</font>";?></td>
                                    <td>
										<div class="btn-group btn-group-toggle" data-toggle="buttons">
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_ReleaseAllow']=="1") echo("active");?>">
												<input value="1" type="radio" name="tea1" id="tea1" autocomplete="off" <?php if($row_Recordset2['tea_ReleaseAllow']=="1") echo("checked");?>> 允许
											</label>
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_ReleaseAllow']=="0") echo("active");?>">
												<input value="0" type="radio" name="tea1" id="tea1" autocomplete="off" <?php if($row_Recordset2['tea_ReleaseAllow']=="0") echo("checked");?>> 禁止
											</label>
										</div>
									</td>
                                </tr>
								<tr>
                                    <td>教师修改课程 状态:<?php if($row_Recordset2['tea_CourseModAllow']=="1") echo "<font color=\"blue\">[允许]</font>"; else echo "<font color=\"red\">[禁止]</font>";?></td>
                                    <td>
										<div class="btn-group btn-group-toggle" data-toggle="buttons">
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_CourseModAllow']=="1") echo("active");?>">
												<input value="1" type="radio" name="tea2" id="tea2" autocomplete="off" <?php if($row_Recordset2['tea_CourseModAllow']=="1") echo("checked");?>> 允许
											</label>
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_CourseModAllow']=="0") echo("active");?>">
												<input value="0" type="radio" name="tea2" id="tea2" autocomplete="off" <?php if($row_Recordset2['tea_CourseModAllow']=="0") echo("checked");?>> 禁止
											</label>
										</div>
									</td>
                                </tr>
								<tr>
                                    <td>教师剔除学生 状态:<?php if($row_Recordset2['tea_StuDelAllow']=="1") echo "<font color=\"blue\">[允许]</font>"; else echo "<font color=\"red\">[禁止]</font>";?></td>
                                    <td>
										<div class="btn-group btn-group-toggle" data-toggle="buttons">
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_StuDelAllow']=="1") echo("active");?>">
												<input value="1" type="radio" name="tea3" id="tea3" autocomplete="off" <?php if($row_Recordset2['tea_StuDelAllow']=="1") echo("checked");?>> 允许
											</label>
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_StuDelAllow']=="0") echo("active");?>">
												<input value="0" type="radio" name="tea3" id="tea3" autocomplete="off" <?php if($row_Recordset2['tea_StuDelAllow']=="0") echo("checked");?>> 禁止
											</label>
										</div>
									</td>
                                </tr>
								<tr>
                                    <td>教师通过课程 状态:<?php if($row_Recordset2['tea_StuFinAllow']=="1") echo "<font color=\"blue\">[允许]</font>"; else echo "<font color=\"red\">[禁止]</font>";?></td>
                                    <td>
										<div class="btn-group btn-group-toggle" data-toggle="buttons">
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_StuFinAllow']=="1") echo("active");?>">
												<input value="1" type="radio" name="tea4" id="tea4" autocomplete="off" <?php if($row_Recordset2['tea_StuFinAllow']=="1") echo("checked");?>> 允许
											</label>
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['tea_StuFinAllow']=="0") echo("active");?>">
												<input value="0" type="radio" name="tea4" id="tea4" autocomplete="off" <?php if($row_Recordset2['tea_StuFinAllow']=="0") echo("checked");?>> 禁止
											</label>
										</div>
									</td>
                                </tr>
								<tr>
                                    <td>提供服务 状态:<?php if($row_Recordset2['global_ban']=="0") echo "<font color=\"blue\">[启用]</font>"; else echo "<font color=\"red\">[禁用]</font>";?></td>
                                    <td>
										<div class="btn-group btn-group-toggle" data-toggle="buttons">
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['global_ban']=="0") echo("active");?>">
												<input value="0" type="radio" name="global1" id="global1" autocomplete="off" <?php if($row_Recordset2['global_ban']=="0") echo("checked");?>> 启用
											</label>
											<label class="btn btn-sm btn-info <?php if($row_Recordset2['global_ban']=="1") echo("active");?>">
												<input value="1" type="radio" name="global1" id="global1" autocomplete="off" <?php if($row_Recordset2['global_ban']=="1") echo("checked");?>> 禁用
											</label>
										</div>
									</td>
                                </tr>
                            </tbody>
                        </table>
						<button type="submit" class="btn btn-info btn-block active">更新权限设定</button>
						<input type="hidden" name="MM_update" value="form" />
						</form>
						<hr class="my-4">
						<a class="btn active btn-block btn-md btn-primary" href="changed-admin.php">修改密码</a>
						<!--<a class="btn active btn-block btn-md btn-primary" href="manage-permission.php">权限管理</a>-->
						<a class="btn btn-danger btn-md btn-block" href="<?php echo $logoutAction ?>" onclick="return confirm('确定要注销吗？')" >退出系统</a>
						
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_free_result($Recordset1); ?>
<?php mysqli_free_result($Recordset2); ?>