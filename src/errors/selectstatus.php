<?php
if($_GET['SubmitSuccess']=='true'){
	$f1=1;
}
elseif($_GET['SubmitSuccess']=='false'){
	$f1=0;
	if($_GET['SubmitExist']=='true')
		$f2=1;
	elseif($_GET['SubmitFull']=='true')
		$f2=2;
	elseif($_GET['isPrevDone']=='false')
		$f2=3;
	else
		$f2=4;
}
else
	$f1=0;
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>状态信息</title>
      <meta name="HandheldFriendly" content="true">
      <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
      <meta name="theme-color" content="#333344">
      <link rel="stylesheet" href="../style/bootstrap.min.css">
      <script src="../style/jquery-3.3.1.slim.min.js"></script>
      <script src="../style/popper.min.js"></script>
      <script src="../style/bootstrap.min.js"></script>
	  <script language="javascript">
		var num = 2; //倒计时的秒数
		var URL = "../stu/welcome-stu.php";
		var id = window.setInterval('doUpdate()', 1000);
		function doUpdate() {
			document.getElementById('page_div').innerHTML = num+'秒后将自动返回学生主页' ;
			if(num == 0) {
				window.clearInterval(id);
				window.location = URL;
			}
			num --;
		}
	  </script>
   </head>
   <body>
   <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron">
				<h2>
					<?php
						if($f1==1)
							echo "选课成功！";
						elseif($f2==1)
							echo "课程已经选取！";
						elseif($f2==2)
							echo "人数已满！";
						elseif($f2==3)
							echo "未学前置课程！";
						elseif($f2==4)
							echo "因未知原因失败，请联系管理员！";
					?>
				</h2>
				<p id="page_div">3秒后将自动返回学生主页</p>
				<p>
					<a class="btn btn-primary btn-large" href="../stu/welcome-stu.php">返回主页</a>
				</p>
			</div>
		</div>
	</div>
   </div>
   </body>
</html>