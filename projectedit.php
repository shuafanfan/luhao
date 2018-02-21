<?php 
include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';
$row=$db->row("select * from t_luhao_case_offlist_project where id={:id}",array('id'=>$_GET['id']));

if($_POST['submit']=='确认修改')
{

$dr=$db->exec("update t_luhao_case_offlist_project set name={:type},args={:args} where id={:id}",
			array(
				'type'=>$_POST['type'],
				'args'=>json_encode($args,JSON_UNESCAPED_UNICODE),
				'id'=>$_POST['id']		 
			)
		);
echo "<script type='text/javascript'>window.frameElement.close();</script>";
die('<script>location.href=location</script>');
}
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>修改页面</title>
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
				<td style="width:60px;" align="right">类型<span style="color:red;">*</span></td>
				<td><input type="text" name="type" value="<?php echo $row['name'] ?>" style="width:98%" required></td>
			</tr>			


			</table>
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
 			<input style="margin-left:150px" id="submit" type="button" name="submit" value="确认修改" onclick="$confirm({'text':'确认完成？','btn1click':function(obj){document.getElementById('submit').type='submit';document.getElementById('submit').click();}})">
		</form>

</body>
</html>