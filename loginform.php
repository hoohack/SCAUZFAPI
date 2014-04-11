<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="stylesheets/index.css" />
		
	</head>
	<body>
		<h1>获取正方信息</h1>
	   	<div class="liginForm">
		    <form id="form1" name="form1" method="post" action="testAPI.php">
			    <label name="usernamelabel" for="username">学 号 </label>
			    <input type="text" name="username"  /><br>
			    <label name="passwordlabel" for="password">密 码 </label>
				<input type="password" name="password" /><br>

				<ul id="navigation">
					<li onmouseover="displaySubMenu(this)" onmouseout="hideSubMenu(this)">
						<a href="#">菜单</a>
						<ul>
							<li>
								<a href="#">查询课表</a>
							</li>
							<li>
								<a href="#">查询考试信息</a>
							</li>
							<li onmouseover="displaySubMenu(this)" onmouseout="hideSubMenu(this)">
								<a href="#">查询成绩</a>
								<ul>
									<li>
										<a href="#">查询历年成绩</a>
									</li>
									<li>
										<a href="#">查询学年成绩</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
				<br>
				<br><br>
				<br>
			    <input type="submit" name="button" id="button" value="登录" />
		    </form>
    	</div>
    	<script type="text/javascript" src="javascripts/index.js"></script>
    </body>
</html>