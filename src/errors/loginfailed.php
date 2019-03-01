<!DOCTYPE html>
<html lang="zh-cmn-Hans">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>首页-选课系统</title>
      <meta name="HandheldFriendly" content="true">
      <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
      <meta name="theme-color" content="#333344">
      <link rel="stylesheet" href="../style/bootstrap.min.css">
      <script src="../style/jquery-3.3.1.slim.min.js"></script>
      <script src="../style/popper.min.js"></script>
      <script src="../style/bootstrap.min.js"></script>
	  <script language="javascript">
		var num = 4; //倒计时的秒数
		var URL = "../index.html";
		var id = window.setInterval('doUpdate()', 1000);
		function doUpdate() {
			document.getElementById('page_div').innerHTML = '将在请检查用户ID/密码是否正确,'+num+'秒后将自动返回主页' ;
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
					登录失败!
				</h2>
				<p id="page_div">将在请检查用户ID/密码是否正确,5秒后将自动返回主页</p>
				<p>
					<a class="btn btn-primary btn-large" href="../index.html">返回主页</a>
				</p>
			</div>
		</div>
	</div>
   </div>
   </body>
</html>