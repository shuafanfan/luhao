<?php

include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';
 

// 新增类型
if($_POST['submit']=='新增类型')
{
	$task_args['备注']=$_POST['info'];
	$task_args['OA阶段代理费标准']=$_POST['fee'];	
	$dr1=$db->exec("insert t_luhao_case_offlist_setup (type,args) values ({:type},{:args})",
				array(
					'type'=>$_POST['type'],
					'args'=>json_encode($task_args,JSON_UNESCAPED_UNICODE)			 
				)
			);
	echo "<script>window.location.href=location<script>";

}
// 删除类型

if($_GET['a']=="delete")
{
$dr2=$db->exec("delete from t_luhao_case_offlist_setup where id={:id}",array('id'=>$_GET['id']));
	echo"<script>window.location.href='{$_SERVER['HTTP_REFERER']}'</script>";

}
$dr = $db -> query("select * from t_luhao_case_offlist_setup  order by id asc");


//$dr = $db -> query("select * from t_luhao_case_offlist_setup order by id desc");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>报价管理</title>
<script src="../inc.js"></script>
</head>
<body >
<table class="toolbar">
	<tr>
		<td><?php echo role::name($_GET['mid']); ?></td>
		<td align="right">
			<a href="#" onclick="$window({'modal':true,'title':'增加','center':'true','width':'400','height':'400','url':'add.php','onclose':function(){location.reload()}})" ><img src="../images/add.png" >增加</a>	
			<a href="setup.php"><img src="../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<br>
<form action="" method="get">
<table class="table" style="text-align:center">
	<tr>
		<th>类型名称</th>
		<th >类型备注</th>
		<th style="width:200px">操作</th>
	</tr>
<?php 
	while($setup=$dr->fetch(PDO::FETCH_ASSOC))
	{
		
 ?>
	<tr>
		<td><?php echo $setup['type'] ?></td>
		<td><?php echo json_decode($setup['args'], true)['备注'] ?></td>
		<td align="left"><a style="margin-left:80px" href="#" onclick="$window({'modal':true,'title':'类型','center':'true','width':'400','height':'400','url':'edit.php?id=<?php echo $setup['id']?>','onclose':function(){location.reload(true)}})" >编辑</a>
<?php 
	$delete=$db->count("select count(*) from t_luhao_case_offlist where setup_id={:setup_id}",array('setup_id'=>$setup['id']));
	if($delete==0)
	{	
 ?>	
		<a href="#" onclick=" $confirm({'text':'确认删除吗?','btn1click':function(obj){location.href='setup.php?a=delete&id=<?php echo $setup['id']?>';},'btn2click':function(){location.reload(true)}})" >删除</a>
<?php 
	}
 ?>
		</td>
	</tr>
<?php 
	}
 ?>
 </table>
 </form>

</body>
</html>