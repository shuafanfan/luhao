<?php 
include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';

if($_POST['submit']=='确认保存')
{

	$dr1=$db->exec("insert t_luhao_case_offlist_project (name,args,oc) values ({:type},{:args},{:oc})",
				array(
					'type'=>$_POST['type'],
					'args'=>json_encode($task_args,JSON_UNESCAPED_UNICODE),
					'oc'=>time()			 
				)
			);
	echo "<script type='text/javascript'>window.frameElement.close();</script>";
	echo "<script>window.location.href=location<script>";
}
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>增加项目</title>
	<script type="text/javascript" src="../inc.js"></script>
	<style>
		.*{margin:0px auto}
	</style>
</head>
<body>
	<table class="toolbar">
		<tr>
			<td align="right">
				<a href="javascript:location.reload()"><img src="../images/refresh.png">刷新</a>
			</td>
		</tr>
	</table>
		<form action="" method="post">
			<table class="table" >
				  <input type="hidden" name="id" value="">
			<tr align="center">
				<td style="width:80px;" align="right">项目<span style="color:red;">*</span></td>
				<td><input type="text" name="type" value="" style="width:98%" required></td>
			</tr>				

			</table>
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
 			<input style="margin-left:150px" id="submit" type="button" name="submit" value="确认保存" onclick="$confirm({'text':'确认完成？','btn1click':function(obj){document.getElementById('submit').type='submit';document.getElementById('submit').click();}})">
		</form>

</body>
</html>