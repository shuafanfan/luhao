<?php

include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';
 




// 删除项目
if($_GET['a']=="delete")
{
	$dr2=$db->exec("delete from t_luhao_case_offlist_country where id={:id}",array('id'=>$_GET['id']));
	echo"<script>window.location.href='{$_SERVER['HTTP_REFERER']}'</script>";
	die;
}

if(isset($_REQUEST['action']))
	switch($_REQUEST['action'])
	{
		case 'up':
		case 'down':
			dbtools::updown($_REQUEST['action'],'t_luhao_case_offlist_country',$_REQUEST['id']);
			break;
	}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>国家设置</title>
<script src="../inc.js"></script>
</head>
<body >
<table class="toolbar">
	<tr>
		<td><?php echo role::name($_GET['mid']); ?></td>
		<td align="right">
			<a href="#" onclick="$window({'modal':true,'title':'新增国家','center':'true','width':'900','height':'400','url':'projectnew.php','onclose':function(){location.reload()}})" ><img src="../images/add.png" >增加国家</a>	
			<a href="countrysetup.php"><img src="../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<br>
<form action="" method="get">
<table class="table" style="text-align:center">
	<tr>
		<th>国家名称</th>
		<th>是否启用</th>
		<th style="width:200px">操作</th>
	</tr>
<?php 
	// 查询国家
	$dr = $db -> query("select * from t_luhao_case_offlist_country  order by oc");
	while($setup=$dr->fetch(PDO::FETCH_ASSOC))
	{
		
 ?>
	<tr>
		<td><?php echo $setup['country'] ?></td>
		<td><?php echo $setup['enable']==1?"启用":"禁用" ?></td>
		<td ><a href="#" onclick="$window({'modal':true,'title':'修改','center':'true','width':'900','height':'400','url':'projectnewedit.php?id=<?php echo $setup['id'] ?>','onclose':function(){location.reload()}})" >编辑</a> 

		<a href="#" onclick=" $confirm({'text':'确认删除吗?','btn1click':function(obj){location.href='countrysetup.php?a=delete&id=<?php echo $setup['id']?>';},'btn2click':function(){location.reload(true)}})" >删除</a>

			<a href="countrysetup.php?mid=<?php echo $_GET['mid']; ?>&id=<?php echo $setup['id']; ?>&action=up">上移</a>
			<a href="countrysetup.php?mid=<?php echo $_GET['mid']; ?>&id=<?php echo $setup['id']; ?>&action=down">下移</a>
		</td>
	</tr>
<?php 
	}
 ?>
 </table>
 </form>

</body>
</html>