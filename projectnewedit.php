<?php
include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';

// 查询所有项目名称
$project = $db->query("select * from t_luhao_case_offlist_project order by oc");

// 查询国家及费用
$country = $db->row("select * from t_luhao_case_offlist_country where id={:id}",array('id'=>$_GET['id']));	
$money=json_decode($country['args'], true);
// echo"<pre>";
// var_dump($money);
// echo"</pre>";

// 保存信息
if($_POST['submit2']=="保存")
{	
	// echo "<pre>";
	// var_dump($_POST['project']);
	// var_dump($_POST['官费']);
  	
	foreach ($_POST['project'] as $key => $value) 
	{		 
			 $args[$value]['官费']=$_POST['官费'][$key];
			 $args[$value]['官费货币']=$_POST['官费货币'][$key];
			 $args[$value]['代理费']=$_POST['代理费'][$key];
			 $args[$value]['代理费货币']=$_POST['代理费货币'][$key];
			 $args[$value]['counts']=$_POST['counts'][$key];
			 $args[$value]['小时次数']=$_POST['小时次数'][$key];	
	}
	 //var_dump($_POST['id']);
	// echo "</pre>";
	// $args=$_POST;

	$dr2=$db->exec("update t_luhao_case_offlist_country set country={:country},args={:args},enable={:enable} where id={:id}",
			array(
				'country'=>$_POST['country'],
				'args'=>json_encode($args,JSON_UNESCAPED_UNICODE),
				'id'=>$_POST['id'],
				'enable'=>$_POST['enable']
			)
		);
	echo "<script type='text/javascript'>window.frameElement.close();</script>";
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>报价管理</title>
<script src="../inc.js"></script>
<script src="../luhao_crm/crm.js"></script>
<script src="../luhao_case_trademark_applyfile/pi.js"></script>
</head>
<body >
<table class="toolbar">
	<tr>
		<td><?php echo role::name($_GET['mid']); ?></td> 
		<td align="right">	
			<a href="javascript:location.href='projectnewedit.php?id=<?php echo $_GET['id'] ?>'"><img src="../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<form action="projectnewedit.php" method="post" id="form1">
<table class="table" >
	<tr>
		<td style="width:120px" align="right">项目名称</td>
		<td style="width:120px" align="right" >国家地区</td>
		<td ><input type="text" value="<?php echo $country['country'] ?>" name="country"></td>
		<td colspan="2"></td>
	</tr>
<?php 
	while($projects=$project->fetch(PDO::FETCH_ASSOC))
	{
?>
	<input type="hidden" name="project[]" value="<?php echo $projects['id'] ?>">
	<tr >
		<td style="width:120px" align="right" ><?php echo $projects['name'] ?></td>
		<td align="right">官费</td>
		<td id="">
			<input name="官费[]" type="text" value="<?php echo $money[$projects['id']]['官费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="官费货币[]" id="" class="select">
				<option value="元"  <?php echo $money[$projects['id']]['官费货币']=='元'?"selected":"" ?>>元</option>
				<option value="美元"  <?php echo $money[$projects['id']]['官费货币']=='美元'?"selected":"" ?>>美元</option>
				<option value="日元"  <?php echo $money[$projects['id']]['官费货币']=='日元'?"selected":"" ?>>日元</option>
				<option value="韩元" <?php echo $money[$projects['id']]['官费货币']=='韩元'?"selected":"" ?>>韩元</option>
				<option value="欧元" <?php echo $money[$projects['id']]['官费货币']=='欧元'?"selected":"" ?>>欧元</option>
				<option value="加元" <?php echo $money[$projects['id']]['官费货币']=='加元'?"selected":"" ?>>加元</option>
				<option value="英镑" <?php echo $money[$projects['id']]['官费货币']=='英镑'?"selected":"" ?>>英镑</option>
				<option value="瑞典克朗"<?php echo $money[$projects['id']]['官费货币']=='瑞典克朗'?"selected":"" ?> >瑞典克朗</option>
				<option value="瑞法" <?php echo $money[$projects['id']]['官费货币']=='瑞法'?"selected":"" ?>>瑞法</option>
				<option value="泰铢" <?php echo $money[$projects['id']]['官费货币']=='泰铢'?"selected":"" ?>>泰铢</option>
				<option value="新元" <?php echo $money[$projects['id']]['官费货币']=='新元'?"selected":"" ?>>新元</option>
				<option value="台币" <?php echo $money[$projects['id']]['官费货币']=='台币'?"selected":"" ?>>台币</option>
			</select>
		</td>

		<td align="right">代理费</td>
		<td  id="">
			<input  name="代理费[]" id="OA阶段代理费" type="text" value="<?php echo $money[$projects['id']]['代理费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}" >
			<select name="代理费货币[]" id="OA阶段代理费货币" class="select">
				<option value="元"  <?php echo $money[$projects['id']]['代理费货币']=='元'?"selected":"" ?>>元</option>
				<option value="美元"  <?php echo $money[$projects['id']]['代理费货币']=='美元'?"selected":"" ?>>美元</option>
				<option value="日元"  <?php echo $money[$projects['id']]['代理费货币']=='日元'?"selected":"" ?>>日元</option>
				<option value="韩元" <?php echo $money[$projects['id']]['代理费货币']=='韩元'?"selected":"" ?>>韩元</option>
				<option value="欧元" <?php echo $money[$projects['id']]['代理费货币']=='欧元'?"selected":"" ?>>欧元</option>
				<option value="加元" <?php echo $money[$projects['id']]['代理费货币']=='加元'?"selected":"" ?>>加元</option>
				<option value="英镑" <?php echo $money[$projects['id']]['代理费货币']=='英镑'?"selected":"" ?>>英镑</option>
				<option value="瑞典克朗"<?php echo $money[$projects['id']]['代理费货币']=='瑞典克朗'?"selected":"" ?> >瑞典克朗</option>
				<option value="瑞法" <?php echo $money[$projects['id']]['代理费货币']=='瑞法'?"selected":"" ?>>瑞法</option>
				<option value="泰铢" <?php echo $money[$projects['id']]['代理费货币']=='泰铢'?"selected":"" ?>>泰铢</option>
				<option value="新元" <?php echo $money[$projects['id']]['代理费货币']=='新元'?"selected":"" ?>>新元</option>
				<option value="台币" <?php echo $money[$projects['id']]['代理费货币']=='台币'?"selected":"" ?>>台币</option>
			</select>
			<select name="counts[]" id="counts" onchange="co()">
				<option value="小时" <?php echo $money[$projects['id']]['counts']=='小时'?"selected":"" ?>>/小时</option>
				<option value="次" <?php echo $money[$projects['id']]['counts']=='次'?"selected":"" ?>>/次</option>
			</select>
			<span id="two">*<input style="width: 30px;" type="text" id="小时次数" name="小时次数[]" value="<?php echo $money[$projects['id']]['小时次数']?$money[$projects['id']]['小时次数']:1 ?>">
			</span>	
		</td>
	</tr>
<?php 
	}
?>
	<tr>
		<td style="width:120px" align="right">是否启用</td>
		<td colspan="4">
			<input type="radio" name="enable" value="1" required <?php echo $country['enable']==1?"checked":"" ?>>启用
			<input type="radio" name="enable" value="2" required <?php echo $country['enable']==2?"checked":"" ?>>禁止
		</td>
	</tr>

	<tr>
		<td colspan="5" align="center">
		<input type="hidden" id="id" value="<?php echo $_GET['id'] ?>" name="id">
		<input id="submit2" type="button" name="submit2" value="保存" onclick="$confirm({'text':'确认完成？','btn1click':function(obj){document.getElementById('submit2').type='submit';document.getElementById('submit2').click();}})">
		</td>
	</tr>
 </table>
 </form>
<script>
	

</script>
</body>
</html>