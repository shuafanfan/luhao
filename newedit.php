<?php

include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';

$dr = $db -> query("select * from t_luhao_case_offlist_setup order by id desc");
$offlist=$db->row("select * from t_luhao_case_offlist where id={:id}",array('id'=>$_GET['id']));
$offlist_args=json_decode($offlist['args'],true);

// 各种类型对应的oa标准
// if(!empty($_POST['type']))
// {
	$row= $db -> row("select * from t_luhao_case_offlist_setup where type={:type} order by id desc",array("type"=>$_POST['type']));
	$oa=json_decode($row['args'], true)['OA阶段代理费标准'];

// }else
// {
// 	$oa="";
// }

// 查询汇率
$rate = $db->row("select * from t_luhao_exchange_rate order by id desc ");

// 保存信息
if($_POST['submit2;']=="确认修改")
{
 
	$args['申请阶段官费']=$_POST['申请阶段官费'];
	if(!empty($args['申请阶段官费']))
	{
		$args['申请阶段官费货币']=$_POST['申请阶段官费货币'];
	}

	$args['申请阶段代理费']=$_POST['申请阶段代理费'];
	if(!empty($args['申请阶段代理费']))
	{
		$args['申请阶段代理费货币']=$_POST['申请阶段代理费货币'];
	}	
	
	$args['OA阶段官费']=$_POST['OA阶段官费'];
	if(!empty($args['OA阶段官费']))
	{
		$args['OA阶段官费货币']=$_POST['OA阶段官费货币'];
		$args['OA阶段官费方式']=$_POST['counts'];
		$args['OA标准1']=$_POST['OA标准1'];
	}
			
	$args['OA阶段代理费']=$_POST['OA阶段代理费'];
	if(!empty($args['OA阶段代理费']))
	{
		$args['OA阶段代理费货币']=$_POST['OA阶段代理费货币'];
		$args['OA阶段代理费方式']=$_POST['counts2'];
		$args['OA标准2']=$_POST['OA标准2'];
	}

	$args['授权阶段官费']=$_POST['授权阶段官费'];
	if(!empty($args['授权阶段官费']))
	{
		$args['授权阶段官费货币']=$_POST['授权阶段官费货币'];
	}
	
	$args['授权阶段代理费']=$_POST['授权阶段代理费'];
	if(!empty($args['授权阶段代理费']))
	{
		$args['授权阶段代理费货币']=$_POST['授权阶段代理费货币'];
	}
	
	$args['路浩代理费']=$_POST['路浩代理费'];
	if(!empty($args['路浩代理费']))
	{
		$args['路浩代理费货币']=$_POST['路浩代理费货币'];
	}
	
	$args['其他费用']=$_POST['其他费用'];
	if(!empty($args['其他费用']))
	{
		$args['其他费用货币']=$_POST['其他费用货币'];
	}
	
	$args['总价']=$_POST['总价'];
	$args['人民币']=$_POST['人民币'];
	$args['翻译费']=$_POST['翻译费'];
	$args['备注']=$_POST['备注'];
	$args['除了oa']=$_POST['total'];
 	
 	$oldinfo=$db->row("select * from t_luhao_case_offlist where id={:id}",array(
				'id'=>$_POST['id']));
	$dr2=$db->exec("update t_luhao_case_offlist set setup_id={:setup_id},country={:country},language={:language},args={:args},enable={:enable} where id={:id} ",
			 	array(
				'id'=>$_POST['id'],
				'setup_id'=>$_POST['type'],
				'country'=>$_POST['country'],
				'language'=>$_POST['language'],
				'args'=>json_encode($args,JSON_UNESCAPED_UNICODE),		
				'enable'=>$_POST['enable']			
			)
		);
	$newinfo=$db->row("select * from t_luhao_case_offlist where id={:id}",array(
				'id'=>$_POST['id']));
	 wlog('luhao_case_offlist',array('操作'=>'修改报价单','修改前信息'=>json_encode($oldinfo,JSON_UNESCAPED_UNICODE),'修改后信息'=>json_encode($newinfo,JSON_UNESCAPED_UNICODE)));

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
			<a href="javascript:location.href='newedit.php?id=<?php echo $_GET['id']; ?>'"><img src="../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<form action="newedit.php" method="post" id="form1">
<table class="table" >
	<tr>
		<td style="width:120px" align="right">类型<span style="color:red;">*</span></td>
		<td >
			<input type="hidden" name="setup_id" value="<?php echo $row['id'] ?>">
			<select name="type" o nchange="select_type()" required value="<?php echo $offlist['type'] ?>">
			<option value="">请选择</option>
<?php 
	while($setup=$dr->fetch(PDO::FETCH_ASSOC))
	{
?>
		<option class='type' value='<?php echo $setup['id'] ?>' <?php echo isset($offlist['setup_id']) && $offlist['setup_id'] == $setup['id'] ? "selected" : ''?> data='<?php echo json_decode($setup['args'], true)['OA阶段代理费标准'] ?>' ><?php echo $setup['type'] ?></option>;
<?php
	}		
 ?>				
			</select>
		</td>
		<td style="width:135px" align="right" >国家地区<span style="color:red;">*</span></td>
		<td ><input type="text" value="<?php echo $offlist['country'] ?>" name="country" required></td>
		<td style="width:120px" align="right" >语言</td>
		<td ><input type="text" value="<?php echo $offlist['language'] ?>" name="language" ></td>
	</tr>
	<tr >
		<td style="width:120px" align="right" rowspan="2">申请阶段<br>(含提实审)</td>
		<td align="right">官费</td>
		<td colspan="4">
			<input name="申请阶段官费" type="text" value="<?php echo $offlist_args['申请阶段官费'] ?>" class="num"  oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="申请阶段官费货币" id="" class="select">
				<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
				<option value="美元" class="us" <?php echo $offlist_args['申请阶段官费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['申请阶段官费货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['申请阶段官费货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['申请阶段官费货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['申请阶段官费货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['申请阶段官费货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['申请阶段官费货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['申请阶段官费货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['申请阶段官费货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['申请阶段官费货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['申请阶段官费货币']=='台币'?'selected':'' ?>>台币</option>

			</select>
		</td>
	</tr>
	<tr>
				<td align="right">代理费</td>
		<td colspan="4">
			<input name="申请阶段代理费" type="text" value="<?php echo $offlist_args['申请阶段代理费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="申请阶段代理费货币" id="" class="select">
				<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
				<option value="美元" class="us" <?php echo $offlist_args['申请阶段代理费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['申请阶段代理费货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['申请阶段代理费货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['申请阶段代理费货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['申请阶段代理费货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['申请阶段代理费货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['申请阶段代理费货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['申请阶段代理费货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['申请阶段代理费货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['申请阶段代理费货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['申请阶段代理费货币']=='台币'?'selected':'' ?>>台币</option>
			</select>
		</td>
	</tr>
		<tr >
		<td style="width:120px" align="right" rowspan="2">OA阶段<br>(以实际发生为准)</td>
		<td align="right">官费</td>
		<td colspan="4" id="">
			<input name="OA阶段官费" type="text" value="<?php echo $offlist_args['OA阶段官费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="OA阶段官费货币" id="" class="select">
				<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
				<option value="美元" class="us" <?php echo $offlist_args['OA阶段官费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['OA阶段官费货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['OA阶段官费货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['OA阶段官费货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['OA阶段官费货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['OA阶段官费货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['OA阶段官费货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['OA阶段官费货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['OA阶段官费货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['OA阶段官费货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['OA阶段官费货币']=='台币'?'selected':'' ?>>台币</option>
			</select>
			<!-- <select name="counts" id="counts" onchange="co()">
				<option value="/小时">/小时</option>
				<option value="/次">/次</option>
			</select>
			<span id="one"> -->
<?php 
	// if(!empty($_POST['type']))
	// {
	// 		echo "*  ".$oa ;
	// }
?>
			<!-- </span>	
			<input type="hidden" name="OA标准1" value="<?php echo '*'.$oa ?>">	 -->	
		</td>
	</tr>
	<tr>
		<td align="right">代理费</td>
		<td colspan="4" id="">
			<input  name="OA阶段代理费" id="OA阶段代理费" type="text" value="<?php echo $offlist_args['OA阶段代理费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}" >
			<select name="OA阶段代理费货币" id="OA阶段代理费货币" class="select">
				<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
				<option value="美元" class="us" <?php echo $offlist_args['OA阶段代理费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['OA阶段代理费货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['OA阶段代理费货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['OA阶段代理费货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['OA阶段代理费货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['OA阶段代理费货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['OA阶段代理费货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['OA阶段代理费货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['OA阶段代理费货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['OA阶段代理费货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['OA阶段代理费货币']=='台币'?'selected':'' ?>>台币</option>
			</select>
			<select name="counts2" id="counts2" onchange="co()">
				<option value="小时" <?php echo $offlist_args['OA阶段代理费方式']=='小时'?'selected':'' ?>>/小时</option>
				<option value="次"  <?php echo $offlist_args['OA阶段代理费方式']=='次'?'selected':'' ?>>/次</option>
			</select>
			<span id="two">
<?php 
	// if(!empty($_POST['type']))
	// {
	// 		echo "*  ".$oa ;
	// }
?>			
			
			*<input style="width: 30px;" type="text" id="OA标准2" name="OA标准2" value="<?php echo $offlist_args['OA标准2'] ?>">
			</span>	
		</td>
	</tr>
		<tr >
		<td style="width:120px" align="right" rowspan="2">授权阶段<br>(不含年费)</td>
		<td align="right">官费</td>
		<td colspan="4">
			<input name="授权阶段官费" id="授权阶段官费" type="text" value="<?php echo $offlist_args['授权阶段官费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="授权阶段官费货币" id="授权阶段官费货币" class="select">
				<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
				<option value="美元" class="us" <?php echo $offlist_args['授权阶段官费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['授权阶段官费货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['授权阶段官费货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['授权阶段官费货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['授权阶段官费货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['授权阶段官费货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['授权阶段官费货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['授权阶段官费货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['授权阶段官费货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['授权阶段官费货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['授权阶段官费货币']=='台币'?'selected':'' ?>>台币</option>
			</select>
		</td>
	</tr>
	<tr>
				<td align="right">代理费</td>
		<td colspan="4">
			<input name="授权阶段代理费" type="text" value="<?php echo $offlist_args['授权阶段代理费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="授权阶段代理费货币" id="" class="select">
				<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
				<option value="美元" class="us" <?php echo $offlist_args['授权阶段代理费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['授权阶段代理费货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['授权阶段代理费货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['授权阶段代理费货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['授权阶段代理费货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['授权阶段代理费货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['授权阶段代理费货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['授权阶段代理费货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['授权阶段代理费货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['授权阶段代理费货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['授权阶段代理费货币']=='台币'?'selected':'' ?>>台币</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="width:120px" align="right">路浩代理费</td>
		<td colspan="5">
			<input name="路浩代理费" type="text" value="<?php echo $offlist_args['路浩代理费'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="路浩代理费货币" id="" class="select">
				<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
				<option value="美元" class="us" <?php echo $offlist_args['路浩代理费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['路浩代理费货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['路浩代理费货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['路浩代理费货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['路浩代理费货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['路浩代理费货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['路浩代理费货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['路浩代理费货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['路浩代理费货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['路浩代理费货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['路浩代理费货币']=='台币'?'selected':'' ?>>台币</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="width:120px" align="right">其他<br>(生效官费+代理费)</td>
		<td colspan="5">
			<input name="其他费用" type="text" value="<?php echo $offlist_args['其他费用'] ?>" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="其他费用货币" id="" class="select">
			<option value="元" class="rmb" <?php echo $offlist_args['申请阶段官费货币']=='元'?'selected':'' ?>>元</option>
			<option value="美元" class="us" <?php echo $offlist_args['授权阶段代理费货币']=='美元'?'selected':'' ?>>美元</option>
				<option value="日元" class="jp" <?php echo $offlist_args['其他费用货币']=='日元'?'selected':'' ?>>日元</option>
				<option value="韩元" class="ko" <?php echo $offlist_args['其他费用货币']=='韩元'?'selected':'' ?>>韩元</option>
				<option value="欧元" class="eu" <?php echo $offlist_args['其他费用货币']=='欧元'?'selected':'' ?>>欧元</option>
				<option value="加元" class="ca" <?php echo $offlist_args['其他费用货币']=='加元'?'selected':'' ?>>加元</option>
				<option value="英镑" class="gb" <?php echo $offlist_args['其他费用货币']=='英镑'?'selected':'' ?>>英镑</option>
				<option value="瑞典克朗" class="gb" <?php echo $offlist_args['其他费用货币']=='瑞典克朗'?'selected':'' ?>>瑞典克朗</option>
				<option value="瑞法" class="gb" <?php echo $offlist_args['其他费用货币']=='瑞法'?'selected':'' ?>>瑞法</option>
				<option value="泰铢" class="gb" <?php echo $offlist_args['其他费用货币']=='泰铢'?'selected':'' ?>>泰铢</option>
				<option value="新元" class="gb" <?php echo $offlist_args['其他费用货币']=='新元'?'selected':'' ?>>新元</option>
				<option value="台币" class="gb" <?php echo $offlist_args['其他费用货币']=='台币'?'selected':'' ?>>台币</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="width:120px" align="right">翻译费</td>
		<td colspan="5"><input name="翻译费" type="text" value="<?php echo $offlist_args['翻译费'] ?>" class="num"></td>
	</tr>
	<tr>
		<td style="width:120px" align="right">备注</td>
		<td colspan="5"><textarea name="备注" id="" style="width:90%" rows="3"><?php echo $offlist_args['备注'] ?></textarea></td>
	</tr>
	<tr>
		<td style="width:120px" align="right">是否启用</td>
		<td colspan="5">
			<label><input type="radio" name="enable" value="1"   <?php echo $offlist['enable']=='1'?'checked':'' ?> required>启用</label>
			<label><input type="radio" name="enable" value="2" required <?php echo $offlist['enable']=='2'?'checked':'' ?>>禁止</label>
		</td>
	</tr>

	<tr>
		<td colspan="6" align="center">
		<input type="hidden" id="total" value="" name="total[]">
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
		<input id="submit2;" type="button" name="submit2;" value="确认修改" onclick="$confirm({'text':'确认完成？','btn1click':function(obj){document.getElementById('submit2;').type='submit';document.getElementById('submit2;').click();}})">
		</td>
	</tr>
 </table>
 </form>
<script>
	// 改变小时次数
	function co()
	{

		var counts2=document.getElementById('counts2');

		if(counts2.value=="/次")
		{
			document.getElementById('two').style.display='none';
			document.getElementById('OA标准2').value=1;
		}else{
			document.getElementById('two').style.display='inline';
		}
	}

	// 改变类型
	// function select_type()
	// {	
	// 	var form1=document.getElementById('form1');
	// 	form1.submit();
	// }
	// 处理多种货币相加
	var compute=document.getElementById('compute');
	var select=document.getElementsByClassName('select');

	compute.onclick=function()
	{
		var oavalue=document.getElementById('OA标准2').value; 
		var sumus=0;
		var sumjp=0;
		var sumko=0;
		var sumeu=0;
		var sumca=0;
		var sumgb=0;
			for (var i = 0; i <select.length; i++) {
				if(select[i].value=="美元")
				{
					var num=0;			
					num = select[i].parentNode.getElementsByClassName('num')[0].value;
					if(i==2||i==3)
					{
						num=num*oavalue;
					}	
					if(num!=0)
					{
					sumus=sumus+parseFloat(num);
					}
				}
				if(select[i].value=="日元")
				{
					var num=0;	
				    num = select[i].parentNode.getElementsByClassName('num')[0].value;
				    if(i==2||i==3)
					{
						num=num*oavalue;
					}	
				    if(num!=0)
				    {
					sumjp=sumjp+parseFloat(num);
					console.log(sumjp);	
					}
				}
				if(select[i].value=="韩元")
				{
					var num = select[i].parentNode.getElementsByClassName('num')[0].value;
					if(i==2||i==3)
					{
						num=num*oavalue;
					}	
					if(num!=0)
					{
					sumko=sumko+parseFloat(num);
					console.log(sumko);	
					}
				}
				if(select[i].value=="欧元")
				{
					var num = select[i].parentNode.getElementsByClassName('num')[0].value;
					if(i==2||i==3)
					{
						num=num*oavalue;
					}	
					if(num!=0)
					{
					sumeu=sumeu+parseFloat(num);
					console.log(sumeu);	
					}
				}
				if(select[i].value=="加元")
				{
					var num = select[i].parentNode.getElementsByClassName('num')[0].value;
					if(i==2||i==3)
					{
						num=num*oavalue;
					}	
					if(num!=0)
					{
					sumca=sumca+parseFloat(num);
					console.log(sumca);	
					}
				}
				if(select[i].value=="英镑")
				{
					var num = select[i].parentNode.getElementsByClassName('num')[0].value;
					if(i==2||i==3)
					{
						num=num*oavalue;
					}	
					if(num!=0)
					{
					sumgb=sumgb+parseFloat(num);
					console.log(sumgb);	
					}
				}
			};
			var maxsum="";
			var maxsum2=0;
			var total=Array();
			maxsum2=sumus*<?php echo $rate['USconvert']/100 ?>
					+sumjp*<?php echo $rate['JPconvert']/100 ?>
					+sumko*<?php echo $rate['KORconvert']/100 ?>
					+sumeu*<?php echo $rate['Eurconvert']/100 ?>
					+sumca*<?php echo $rate['CADconvert']/100 ?>
					+sumgb*<?php echo $rate['GBPconvert']/100 ?>;
			if(sumus!=0)
			{
			total['美元']=sumus;
			sumus=sumus.toString()+'美元';			
			maxsum=maxsum+sumus+'+';
			}
			if(sumjp!=0)
			{
			total['日元']=sumjp;
			sumjp=sumjp.toString()+'日元';
			maxsum=maxsum+sumjp+'+';
			}
			if(sumko!=0)
			{
			total['韩元']=sumko;
			sumko=sumko.toString()+'韩元';
			maxsum=maxsum+sumko+'+';
			}
			if(sumeu!=0)
			{
				total['欧元']=sumeu;
			sumeu=sumeu.toString()+'欧元';
			maxsum=maxsum+sumeu+'+';
			}
			if(sumca!=0)
			{
				total['加元']=sumca;
			sumca=sumca.toString()+'加元';
			maxsum=maxsum+sumca+'+';
			}
			if(sumgb!=0)
			{
				total['英镑']=sumgb;
			sumgb=sumgb.toString()+'英镑';
			maxsum=maxsum+sumgb+'+';
			}


		// 除了oa代理费不存储
		// var k=document.getElementById('OA阶段代理费货币').value;
		// var v=document.getElementById('OA阶段代理费').value;		
		// var b=document.getElementById('OA标准2').value;	
		// console.log(b);	
		// total[k]=total[k]-v*b;
		// console.log(total);
		// total=total.join(',');
		// console.log(total);
	 //    document.getElementById('total').value=total;

	 //    console.log(document.getElementById('total').value);
		// 除了oa代理费不存储
		

		 maxsum = maxsum.substring(0, maxsum.length-1);  
		 document.getElementById('maxsum').value=maxsum;
		 document.getElementById('maxsum2').value=maxsum2.toFixed(2)+'元';
	}
</script>
</body>
</html>