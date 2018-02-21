<?php

include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';

// 权限判断
acl('管理',false) or acl('客服',false) or die;
if(!isset($_GET['show'])||(isset($_GET['show'])&&$_GET['show']==''))
{
		$type = $db -> query("select * from t_luhao_case_offlist_setup group by id");
		while($types=$type->fetch(PDO::FETCH_ASSOC))
		{
		$param[]=$types['id'];
		}	 
		if(acl('客服',false)){
			$enable="and enable=1";
		 }else{
		 	$enable="";
		 }
		$dr = $db -> query("select c1.*,c2.type from t_luhao_case_offlist c1 join t_luhao_case_offlist_setup c2 on c1.setup_id=c2.id where c1.setup_id in ({:type}) {$enable}  order by c1.enable asc",
				array(
					'type'=>$param
				)
			);
		
}


// 查询种类
	$type = $db->query("select * from t_luhao_case_offlist_setup group by type");
	while($types=$type->fetch(PDO::FETCH_ASSOC))
	{
		if(isset($_GET['show']) && $_GET['show']==$types['id'])
		{
				if(acl('客服',false)){
						$enable="and enable='1'";
					 }else{
					 	$enable="";
					 }
				$param['type']=array($types['id']);
				$dr = $db -> query("select c1.*,c2.type from t_luhao_case_offlist c1 join t_luhao_case_offlist_setup c2 on c1.setup_id=c2.id where setup_id in ({:type}) {$enable} order by c1.enable asc",$param);
				$typeinfo=$db->row("select * from t_luhao_case_offlist_setup where id in ({:type})",$param);
		}
	}


// 删除类型
if($_GET['a']=="delete")
{
	$dr2=$db->exec("delete from t_luhao_case_offlist where id={:id}",array('id'=>$_GET['id']));
	$_GET['a']="";
	echo"<script>window.location.href='{$_SERVER['HTTP_REFERER']}'</script>";

}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>报价管理</title>
<script src="../inc.js"></script>
</head>
<body>
<table class="toolbar">
	<tr>
		<td><?php echo role::name($_GET['mid']); ?></td>
		<td align="right">
		<?php 
			 if(acl('管理',false)){
		 ?>	
			<a href="#" onclick="$window({'modal':true,'title':'新建','center':'true','width':'800','height':'600','url':'new.php','onclose':function(){location.reload()}})" ><img src="../images/add.png" >新建</a>
			<a href="#" onclick="$window({'modal':true,'title':'类型','center':'true','width':'800','height':'600','url':'setup.php','onclose':function(){location.reload()}})" ><img src="../images/add.png" >设置</a>	
		<?php 
			}
		 ?>		
			<a href="javascript:location.reload()"><img src="../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<?php
include "tabs.php";//引入筛选按钮
?>
<table class="table">
	<tr>
		<th rowspan="2">类型</th>
		<th rowspan="2">国家地区</th>
		<th rowspan="2">语言</th>
		<th colspan="2">申请阶段(含提实审)</th>
		<th colspan="2">OA阶段(以实际发生为准)</th>
		<th colspan="2">授权阶段(不含年费)</th>
		<th rowspan="2">路浩代理</th>
		<th rowspan="2">其他(生效官费<br>+代理费)</th>
		<th rowspan="2">总价</th>
		<th rowspan="2">折合<br>人民币</th>
		<th rowspan="2">翻译费</th>
		<th rowspan="2">备注</th>
		<?php 
			 if(acl('管理',false)){
		 ?>	
		<th rowspan="2">状态</th>		
		<th rowspan="2">操作</th>
		<?php 
			 }
		 ?>		
	</tr>
	<tr>
		<th>官费</th>
		<th>代理费</th>
		<th>官费</th>
		<th>代理费</th>
		<th>官费</th>
		<th>代理费</th>
	</tr>
<?php 
	$oi=array();
	while($offlist=$dr->fetch(PDO::FETCH_ASSOC))
	{
		$oi[]=$offlist['id'];
		$offlist_args=json_decode($offlist['args'],true);
 ?>
	<tr <?php if($offlist['enable']==2) echo 'style="background:#E6E6E6"'?>>
		<td align="center"><?php echo $offlist['type']; ?></td>
		<td align="center"><?php echo $offlist['country']; ?></td>
		<td align="center"><?php echo $offlist['language']; ?></td>
		<td align="right" style="white-space:nowrap">
			<span class="moneys_type">
				<span class="money_num"><?php echo $offlist_args['申请阶段官费']; ?></span><span class="money_type"><?php echo $offlist_args['申请阶段官费货币']; ?></span>
			</span>
		</td>
		<td align="right" style="white-space:nowrap">
			<span class="moneys_type">
				<span class="money_num"><?php echo $offlist_args['申请阶段代理费']; ?></span><span class="money_type"><?php echo $offlist_args['申请阶段代理费货币']; ?></span>
			</span>
		</td>
		<td align="right" style="white-space:nowrap">
			<span class="moneys_type">
				<span class="money_num"><?php echo $offlist_args['OA阶段官费']; ?></span><span class="money_type"><?php echo $offlist_args['OA阶段官费货币']; ?></span>
			</span>
		</td>
		<td align="right" style="white-space:nowrap">
<?php 
	if($offlist_args['OA阶段代理费']==0||$offlist_args['OA标准2']==0){
?>	
		<span class="input_money" style="display:none"><?php echo $offlist_args['OA阶段代理费'];?></span><span class="input_type	"></span><input style="width:20px" type="hidden" value="<?php echo $offlist_args['OA标准2'] ?>" data='<?php echo $offlist['args']; ?>' id="oi_<?php echo $offlist['id']; ?>" oninput="if(/[^\d.]/g.test(this.value)){this.value='';};fun1(this)" >	
<?php 
	}else{	
 ?>
 		<span class="input_money"><?php echo $offlist_args['OA阶段代理费'];?></span><span class="input_type"><?php echo $offlist_args['OA阶段代理费货币'];?>/<?php echo $offlist_args['OA阶段代理费方式'] ?>*</span><input style="width:20px" type="text" value="<?php echo $offlist_args['OA标准2'] ?>" data='<?php echo $offlist['args']; ?>' id="oi_<?php echo $offlist['id']; ?>" oninput="if(/[^\d.]/g.test(this.value)){this.value='';};fun1(this)" >	
 <?php 
 	}
  ?>
		</td>
		<td align="right" style="white-space:nowrap">
			<span class="moneys_type">
			<span class="money_num"><?php echo $offlist_args['授权阶段官费']; ?></span><span class="money_type"><?php echo $offlist_args['授权阶段官费货币']; ?></span>
			</span>
		</td>
		<td align="right" style="white-space:nowrap">
			<span class="moneys_type">
			<span class="money_num"><?php echo $offlist_args['授权阶段代理费']; ?></span><span class="money_type"><?php echo $offlist_args['授权阶段代理费货币']; ?></span></span>
		</td>
		<td align="right" style="white-space:nowrap">
			<span class="moneys_type">
			<span class="money_num"><?php echo $offlist_args['路浩代理费']; ?></span><span class="money_type"><?php echo $offlist_args['路浩代理费货币']; ?></span>
			</span>
		</td>
		<td align="right" style="white-space:nowrap">
			<span class="moneys_type">
			<span class="money_num"><?php echo $offlist_args['其他费用']; ?></span><span class="money_type"><?php echo $offlist_args['其他费用货币']; ?></span>
			</span>
		</td>
		<td class="sum" align="right" ></td>
		<td class="rmb" align="right" style="white-space:nowrap"></td>
		<td align="center"><?php echo $offlist_args['翻译费']; ?></td>
		<td align="left"><?php echo $offlist_args['备注']; ?></td>
		<?php 
			 if(acl('管理',false)){
		 ?>	
		<td align="center"><?php echo $offlist['enable']==1?"启用":"禁用"; ?></td>
		<td align="center">
		<a href="#" onclick="$window({'modal':true,'title':'修改','center':'true','width':'800','height':'600','url':'newedit.php?id=<?php echo $offlist['id']?>','onclose':function(){location.reload()}})" >编辑</a>
		<a href="#" onclick="$confirm({'text':'确认删除吗?','btn1click':function(obj){location.href='index.php?mid=<?php echo $_GET['mid']; ?>&a=delete&id=<?php echo $offlist['id']?>';},'btn2click':function(){location.reload(true)}})" >删除</a>
		</td>
		<?php 
			}
		 ?>
	</tr>
<?php 
}
 ?>

</table>

<br><br>
<?php 
	if(!empty($typeinfo['args']))
	{			
 ?>
 <div class="info"><?php echo json_decode($typeinfo['args'], true)['备注'] ?></div>
<?php 
	}else{
 ?>
 <div class="info">外币折算，按照从中国银行官网网站读取的中行折算价计算。</div>
 <?php 
 	}
  ?>
<br>
<?php
	$exchange_rate=$db->row('select * from t_luhao_exchange_rate order by id desc limit 1');
	$exchange=array();
	$exchange['元']=100;
	$exchange['美元']=$exchange_rate['USconvert'];
	$exchange['日元']=$exchange_rate['JPconvert'];
	$exchange['韩元']=$exchange_rate['KORconvert'];
	$exchange['欧元']=$exchange_rate['Eurconvert'];
	$exchange['加元']=$exchange_rate['CADconvert'];
	$exchange['英镑']=$exchange_rate['GBPconvert'];
	$exchange['瑞典克朗']=$exchange_rate['SEKconvert'];
	$exchange['瑞法']=$exchange_rate['CHFconvert'];
	$exchange['泰铢']=$exchange_rate['THBconvert'];
	$exchange['新元']=$exchange_rate['SGDconvert'];
	$exchange['台币']=$exchange_rate['TWDconvert'];
?>
<script type="text/javascript">
	var exchange=<?php echo json_encode($exchange,JSON_UNESCAPED_UNICODE); ?>;
	console.log(exchange);
	function fun1(obj)
	{
		// alert(1);
		//alert(obj.getAttribute('data'));
		var data=eval("("+obj.getAttribute('data')+")");
		// alert(data);

		var sum=[];
		if(data['申请阶段官费货币'])sum[data['申请阶段官费货币']]=0;
		if(data['申请阶段代理费货币']) sum[data['申请阶段代理费货币']]=0;
		if(data['OA阶段官费货币']) sum[data['OA阶段官费货币']]=0;
		if(data['OA阶段代理费货币']) sum[data['OA阶段代理费货币']]=0;
		if(data['授权阶段官费货币']) sum[data['授权阶段官费货币']]=0;
		if(data['授权阶段代理费货币']) sum[data['授权阶段代理费货币']]=0;
		if(data['路浩代理费货币']) sum[data['路浩代理费货币']]=0;
		if(data['其他费用货币']) sum[data['其他费用货币']]=0;

		if(data['申请阶段官费货币']) sum[data['申请阶段官费货币']]+=pFloat(data['申请阶段官费']);
		if(data['申请阶段代理费货币']) sum[data['申请阶段代理费货币']]+=pFloat(data['申请阶段代理费']);
		if(data['OA阶段官费货币']) sum[data['OA阶段官费货币']]+=pFloat(data['OA阶段官费']);
		if(data['OA阶段代理费货币']) sum[data['OA阶段代理费货币']]+=pFloat(data['OA阶段代理费'])*pFloat(obj.value);
		if(data['授权阶段官费货币']) sum[data['授权阶段官费货币']]+=pFloat(data['授权阶段官费']);
		if(data['授权阶段代理费货币']) sum[data['授权阶段代理费货币']]+=pFloat(data['授权阶段代理费']);
		if(data['路浩代理费货币']) sum[data['路浩代理费货币']]+=pFloat(data['路浩代理费']);
		if(data['其他费用货币']) sum[data['其他费用货币']]+=pFloat(data['其他费用']);

		 console.log(sum);
		var show="";
		var rmb=0;
		for(var k in sum)
		{
			show+='+'+sum[k]+k;
			rmb+=exchange[k]*sum[k];
		}
		show=show.substring(1);

		// alert(show);
		obj.parentElement.parentElement.getElementsByClassName('sum')[0].innerHTML=show;
		obj.parentElement.parentElement.getElementsByClassName('rmb')[0].innerHTML=(rmb/100).toFixed(2)+'元';
	}
	function pFloat(o)
	{
		o=parseFloat(o);
		return isNaN(o)?0:o;
	}

	//重新计算
	var oi_list=<?php echo json_encode($oi,JSON_UNESCAPED_UNICODE); ?>;
	for(var i=0;i<oi_list.length;i++)
	{
		var o=document.getElementById('oi_'+oi_list[i]);
		o.oninput(o);
	}


	// function fun(obj)
	// {
	// 	var va = obj.value;
	// 	var input_type =obj.parentNode.getElementsByClassName('input_type')[0].innerHTML;//当前input框
	// 	var input_money =obj.parentNode.getElementsByClassName('input_money')[0].innerHTML;//当前input框
	// 	var par = obj.parentNode.parentNode;
	// 	var list = par.getElementsByClassName('moneys_type');
	// 	sum = parseInt(va*input_money);
	// 	same='';
	// 	for(var i=0;i<list.length;i++)
	// 	{
	// 		var money = list[i].getElementsByClassName('money_num')[0].innerHTML;
	// 		var type = list[i].getElementsByClassName('money_type')[0].innerHTML;
	// 		console.log(money);
	// 		console.log(type);
	// 		//var money = parseInt(va*input_money);
	// 		if(type==input_type)
	// 		{
	// 			sum = sum + parseInt(money);//计算总值 
	// 			sum1 = sum + input_type;//放入带单位的拼接后的值
	// 		}
	// 		else
	// 		{
	// 			for (var j = 0; j< list.length; j++) 
	// 			{
					
	// 				//if(obj.)
	// 				{

	// 				}
	// 				if(list[j].getElementsByClassName('money_type')[0].innerHTML == type)//如果与当前遍历的类型相同
	// 				{
	// 					money = parseInt(money) + parseInt(list[j].getElementsByClassName('money_num')[0].innerHTML);
	// 					money1 = money + type;
	// 				}
					
	// 			}
	// 		}
			
	// 		// if(sum)
	// 		// {
	// 		// 	sum = sum+"+"+money+type;
	// 		// }
	// 		// else
	// 		// {
	// 		// 	sum = money+type;
	// 		// }
			
	// 	}
	// 	// console.log(sum);
	// 	// console.log(sum1);
	// 	console.log(money1);


		

	// }
</script>


</body>
</html>