<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
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

				</ul>

				<select name="operation">
					<option value="lessonTable">查询课表</option>
					<option value="checkTest">查询考试信息</option>
					<option value="checkScore">查询考试成绩</option>
					<option value="personalMsg">查看个人信息</option>
				</select>
				<br>
			    <input type="submit" name="button" id="button" value="登录" />
		    </form>
    	</div>
    </body>
</html>