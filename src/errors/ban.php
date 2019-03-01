<?php
// 初始化会话
	session_start();

// 登出
// 洗掉所有SESSION变量
    session_start();
	session_unset();
	session_destroy();
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>You've been BANNED</title>
      <meta name="HandheldFriendly" content="true">
      <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
      <meta name="theme-color" content="#333344">
      <link rel="stylesheet" href="../style/bootstrap.min.css">
      <script src="../style/jquery-3.3.1.slim.min.js"></script>
      <script src="../style/popper.min.js"></script>
      <script src="../style/bootstrap.min.js"></script>
	  <script language="javascript">
		var num = 2; //倒计时的秒数
		var URL = "../index.html";
		var id = window.setInterval('doUpdate()', 1000);
		function doUpdate() {
			document.getElementById('page_div').innerHTML = num+'秒后将自动返回主页' ;
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
					你已被禁止
				</h2>
				<p id="page_div">3秒后将自动返回主页</p>
				<p>
					<a class="btn btn-primary btn-large" href="../index.html">返回主页</a>
				</p>
			</div>
		</div>
	</div>
   </div>
   </body>
</html>