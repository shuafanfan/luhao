<?php 
include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';

if($_POST['submit']=='确认保存')
{
$task_args['备注']=$_POST['info'];
	$task_args['OA阶段代理费标准']=$_POST['fee'];	
	$dr1=$db->exec("insert t_luhao_case_offlist_setup (type,args) values ({:type},{:args})",
				array(
					'type'=>$_POST['type'],
					'args'=>json_encode($task_args,JSON_UNESCAPED_UNICODE)			 
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
	<title>增加页面</title>
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
				<td style="width:80px;" align="right">类型<span style="color:red;">*</span></td>
				<td><input type="text" name="type" value="" style="width:98%" required></td>
			</tr>				
			<tr align="center">
				<td style="width:80px;" align="right">备注<span style="color:red;">*</span></td>
				<td><textarea name="info" id="" style="width:98%"  required></textarea></td>
			</tr>


			</table>
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
 			<input style="margin-left:150px" id="submit" type="button" name="submit" value="确认保存" onclick="$confirm({'text':'确认完成？','btn1click':function(obj){document.getElementById('submit').type='submit';document.getElementById('submit').click();}})">
		</form>

</body>
</html>