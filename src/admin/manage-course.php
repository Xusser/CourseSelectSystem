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
// 所有课程 先显示没结束的
$query_Recordset1 = "SELECT course.*,teacher.teaid,teacher.teaname FROM course,teacher WHERE teacher.teaid=course.teaid ORDER BY `course`.`cou_disable` ASC,`course`.`courseid` ASC";
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
    <title>课程管理-管理员面板-选课系统</title>
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
					课程管理
				</h2>
				<p>
					在这里你可以添加\删除\修改课程信息
				</p>
			</div>
			<div class="row">
				<div class="col-md-8">
					<div class="alert alert-dismissable alert-success" id="SuccessInfo">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<div id="SuccessText"></div>
                    </div>
					<!--startprint-->
				    <table class="table table-striped table-hover table-sm" id="myTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>课程代码</th>
                                    <th>课程名称</th>
                                    <th>教师</th>
									<th>人数</th>
									<th>状态</th>
									<th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="CourseTable">
							<?php do { ?>
                                <tr>
									<td><?php echo $row_Recordset1['courseid']; ?></td>
                                    <td><?php echo $row_Recordset1['coursename']; ?></td>
                                    <td><?php echo $row_Recordset1['teaname']; ?>[<?php echo $row_Recordset1['teaid']; ?>]</td>
                                    <td><?php echo $row_Recordset1['selected']; ?>/<?php echo $row_Recordset1['total']; ?></td>
									<td>
										<?php
											if($row_Recordset1['cou_disable']==0){
												echo "<font color=\"blue\">进行中</font>";
												if($row_Recordset1['pick_allowed']==1)
													echo ",<font color=\"green\">可选课</font>";
												else
													echo ",<font color=\"red\">不可选课</font>";
											}
											else{
												echo "<font color=\"red\">课程结束</font>";
											}
										?>
									</td>
									<td>
										<a class="btn active btn-primary btn-sm"  href="details-course.php?courseid=<?php echo $row_Recordset1['courseid']; ?>">详情</a> 
										<a <?php if($row_Recordset1['pick_allowed']==1){echo "hidden";}?> class="btn active btn-success btn-sm <?php if($row_Recordset1['cou_disable']==1){echo "disabled";}?>" href="enpick-cou.php?courseid=<?php echo $row_Recordset1['courseid']; ?>">允许选课</a>
										<a <?php if($row_Recordset1['pick_allowed']==0){echo "hidden";}?> class="btn active btn-secondary btn-sm <?php if($row_Recordset1['cou_disable']==1){echo "disabled";}?>" href="dipick-cou.php?courseid=<?php echo $row_Recordset1['courseid']; ?>">禁止选课</a>
										 | <a class="btn active btn-danger btn-sm <?php if($row_Recordset1['cou_disable']==1){echo "disabled";}?>" onclick="return confirm('确定要结束该课程？')" href="delete-cou.php?courseid=<?php echo $row_Recordset1['courseid']; ?>">结束课程</a>
									</td>
                                </tr>
							<?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
                            </tbody>
                        </table>
						<!--endprint-->
				</div>
				<div class="col-md-4">
					<a class="btn active btn-block btn-md btn-info" href="javascript:doPrint();">打印</a>
					
					<a class="btn active btn-block btn-md btn-primary" href="welcome-admin.php">返回管理员面板</a>
						
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
	
	function GetQueryString(name)
	{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  decodeURI(r[2]); return null;    //decodeURI()是将encodeURI转换的字符转换回来
	}
	
	function doPrint() {  
        bdhtml=window.document.body.innerHTML;      
        sprnstr="<!--startprint-->";    
        eprnstr="<!--endprint-->";
        prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);      
        prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));      
        window.document.body.innerHTML=prnhtml;   
        window.print();      
	}
	
	$(document).ready(function(){
		var url_courseid = GetQueryString("courseid");
		var url_disable = GetQueryString("disable");
		var url_delete = GetQueryString("delete");
		console.log("courseid="+url_courseid);
		console.log("disable="+url_disable);
		console.log("delete="+url_delete);
		if(url_courseid !=null)
		{
			$("#SuccessInfo").show();
			
			if(url_disable == "true")
			{
				$("#SuccessText").text('[课程ID:'+url_courseid+'] 【禁止选课】: 操作成功');
			}else if(url_disable == "false")
			{
				$("#SuccessText").text('[课程ID:'+url_courseid+'] 【允许选课】: 操作成功');
			}else if(url_delete == "true")
			{
				$("#SuccessText").text('[课程ID:'+url_courseid+'] 【结束课程】: 操作成功');
			}
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