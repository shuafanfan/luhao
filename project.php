<?php

include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';

// 权限判断
acl('管理',false) or acl('客服',false) or die;


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>新功能</title>
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
		<th rowspan="2">选择</th>
		<th rowspan="2">项目</th>

<?php 
	// 查询国家及费用
	$countrys = $db->rows("select * from t_luhao_case_offlist_country where enable=1 order by oc ");
	foreach($countrys as $country)
	{
		$n++;
?>	
		<th colspan="2" class="country" data="<?php echo $country['id']; ?>"><?php echo $country['country'] ?> </th>
<?php 
	}
 ?>
	</tr>
	<tr>
<?php 
	for ($i=1; $i <=$n ; $i++) { 
 ?>
		<th>官费</th> 
		<th>代理费</th> 
<?php 
	}
 ?>
	</tr>

<?php 
	// 查询所有项目名称
	$project = $db->query("select * from t_luhao_case_offlist_project order by oc");
	while($projects=$project->fetch(PDO::FETCH_ASSOC))
	{
?>
	<tr>
		<td align="center"><input type="checkbox" name="choice" class="choice" data="<?php echo $projects['id'] ?>"></td>
		<td align="center" ><?php echo $projects['name'] ?></td>
<?php
	 // $dr = $db->query("select id,country,args from t_luhao_case_offlist_country ");
	 // while($country = $dr ->fetch(PDO::FETCH_ASSOC))
	foreach($countrys as $country)
	 {
	 	$args = json_decode($country['args'],true);
	 	// echo"<pre>";
	 	// var_dump($args);
	 	// echo"</pre>";
	 	$guan=$args[$projects['id']]['官费'];
	 	$guanb=$args[$projects['id']]['官费货币'];
	 	$dai=$args[$projects['id']]['代理费'];
	 	$daib=$args[$projects['id']]['代理费货币'];
	 	$daic=$args[$projects['id']]['counts'];
	 	$dait=$args[$projects['id']]['小时次数'];
	 	
?>
		<td align="right" class="country_g_<?php echo $country['id'] ?>" m="<?php echo $guan; ?>" b="<?php if($guan>0) { echo $guanb;} ?>" style="white-space:nowrap"><?php if($guan>0) { echo $guan.$guanb;} ?></td>
		<td align="right" class="country_s_<?php echo $country['id'] ?>"  m="<?php echo $dai; ?>" b="<?php echo $daib; ?>" t="<?php echo $dait; ?>" style="white-space:nowrap"><?php if($dai>0) { echo $dai.$daib.'/'.$daic;} ?>
<?php 
if($dait>0&&$dai>0)
{ 
	?>
		*<input style="width:20px" class="country_sn_<?php echo $country['id'] ?>" type="text" value="<?php echo $dait ?>" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}else js();">
<?php 
}else{
?>
		<input style="width:20px" class="country_sn_<?php echo $country['id'] ?>" type="hidden" value="<?php echo $dait ?>" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}else js();">
<?php	
} 
?>
		</td>
<?php
	 }	 
 ?>
		
	</tr>
<?php 
	}
 ?>
 	<tr align="right">
 		<td></td>
		<td align="center">外币合计</td>
<?php 
	foreach($countrys as $country)
	{ 
 ?>
		<td id="sum_g_<?php echo $country['id']; ?>" class="sum1"></td>
		<td id="sum_s_<?php echo $country['id']; ?>" class="sum2"></td>
		
<?php 
 }
?>
	</tr>
	 <tr align="right">
 		<td></td>
		<td align="center" style="white-space:nowrap">折合人民币合计</td>
<?php 
	foreach($countrys as $country)
	{ 
 ?>
		<td id="sum_g_g<?php echo $country['id']; ?>" class="sum1" style="white-space:nowrap"></td>
		<td id="sum_s_s<?php echo $country['id']; ?>" class="sum2" style="white-space:nowrap"></td>
		
<?php 
 }
?>
	</tr>
<?php 
	if(acl('管理',false))
	{
?>	
 	<tr align="right">
		<td colspan=" <?php echo 2*$i+2; ?> ">
			<button onclick="$window({'modal':true,'title':'项目设置','center':'true','width':'600','height':'400','url':'projectsetup.php','onclose':function(){location.reload()}})">项目设置</button>
			<button onclick="$window({'modal':true,'title':'国家设置','center':'true','width':'950','height':'600','url':'countrysetup.php','onclose':function(){location.reload()}})">国家设置</button>
			<!-- <button onclick="$window({'modal':true,'title':'新增国家','center':'true','width':'900','height':'600','url':'projectnew.php','onclose':function(){location.reload()}})">新增国家</button> -->
		</td>
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
	//console.log(exchange);
 	var guanfei = 0;
 	var sum_yuan = 0;


 	function js()
 	{
 		//读取所有国家
 			var countrys=document.getElementsByClassName('country');
 			for(var c=0;c<countrys.length;c++)
 			{
 				//计算官费
 				var sum=[];
 				var gg=document.getElementsByClassName('country_g_'+countrys[c].getAttribute("data"));
 				for(var g=0;g<gg.length;g++)
 					if(choice[g].checked)
 						if(gg[g].getAttribute('b'))
	 					{
	 						if(sum[gg[g].getAttribute('b')])
		 						sum[gg[g].getAttribute('b')]+=pFloat(gg[g].getAttribute('m'));
		 					else
		 						sum[gg[g].getAttribute('b')]=pFloat(gg[g].getAttribute('m'));
	 					}

	 			var show="";
				var rmb=0;
				for(var k in sum)
					if(sum[k]!=0)
					{
						show+='+'+sum[k]+k;
						rmb+=exchange[k]*sum[k];
					}
				show=show.substring(1);
				document.getElementById('sum_g_'+countrys[c].getAttribute('data')).innerHTML=show;
				document.getElementById('sum_g_g'+countrys[c].getAttribute('data')).innerHTML=(rmb/100).toFixed(2)+'元';;

 				//计算代理费
 				var sum=[];
 				var gg=document.getElementsByClassName('country_s_'+countrys[c].getAttribute("data"));
 				var sn=document.getElementsByClassName('country_sn_'+countrys[c].getAttribute("data"));
 				for(var g=0;g<gg.length;g++)
 					if(choice[g].checked)
 						if(gg[g].getAttribute('b'))
	 					{
	 						if(sum[gg[g].getAttribute('b')])
		 						sum[gg[g].getAttribute('b')]+=pFloat(gg[g].getAttribute('m'))*pFloat(sn[g].value);
		 					else
		 						sum[gg[g].getAttribute('b')]=pFloat(gg[g].getAttribute('m'))*pFloat(sn[g].value);
	 					}

	 			var show="";
				var rmb=0;
				for(var k in sum)
					if(sum[k]!=0)
					{
						show+='+'+sum[k]+k;
						rmb+=exchange[k]*sum[k];
					}

				show=show.substring(1);
				document.getElementById('sum_s_'+countrys[c].getAttribute('data')).innerHTML=show;
				document.getElementById('sum_s_s'+countrys[c].getAttribute('data')).innerHTML=(rmb/100).toFixed(2)+'元';;
 			}
 	}

 	var choice=document.getElementsByClassName('choice');
 	for (var i = 0; i < choice.length; i++) 
 		choice[i].onchange=js;


 	// for (var i = 0; i < choice.length; i++) 
 	// {
 	// 	choice[i].onchange=function()
 	// 	{
 			
 	// 		if(this.checked==true)
 	// 		{	
 	// 			var choi=this;
 	// 			var data=this.getAttribute('data');
 	// 			var country = this.parentNode.parentNode.getElementsByClassName('guan0'+data);	
 	// 			var country2 = this.parentNode.parentNode.getElementsByClassName('guan1'+data);	
 	// 			var guant = this.parentNode.parentNode.getElementsByClassName('guant2'+data);	
 	// 			var list_sum = document.getElementsByClassName('sum_money');
 	// 			var list_sum2 = document.getElementsByClassName('sum_money2');
 	// 			var sumrmb = document.getElementsByClassName('sumrmb');
 	// 			var sumrmb2 = document.getElementsByClassName('sumrmb2');

 	// 			// 官费计算
 	// 			for (var j = 0; j < country.length; j++) 
 	// 			{	  					 
 	// 				var guanm=country[j].getAttribute('m');
 	// 				 var guanb=country[j].getAttribute('b');
 	// 				  if(!isNaN(parseFloat(guanm)))
 	// 				  {
 	// 				  	//总金额
 	// 				  	var sum= parseFloat(list_sum[j].getElementsByClassName(guanb)[0].getElementsByClassName('money')[0].innerHTML) + parseFloat(guanm);
 					  
 					  	
 	// 				  	// 人民币
 	// 				  	if(guanb!='元')
 	// 				  	{
 	// 				  		rmb=(guanm*exchange[guanb]/100).toFixed(2);
 	// 				  	}
 	// 				  	else
 	// 				  	{
 	// 				  		rmb=guanm;
 	// 				  	}
 					  	 
 	// 				  	 rmb=parseFloat(rmb)+pFloat(sumrmb[j].innerHTML);
 	// 					 rmb=rmb.toFixed(2);
 	// 				  	 sumrmb[j].innerHTML=rmb+'元';
 					  	 
 	// 				  	  //人民币
 	// 				  	list_sum[j].getElementsByClassName(guanb)[0].getElementsByClassName('money')[0].innerHTML =sum;
 	// 				  	list_sum[j].getElementsByClassName(guanb)[0].style.display = '';
 	// 				  	sumrmb[j].style.display = '';
 	// 				  }	
 					  			 
 	// 			}
 	// 			// 代理费计算
 	// 			for (var k = 0; k < country2.length; k++) 
 	// 			{	  					 
 	// 				var guanm2=country2[k].getAttribute('m');
 	// 				var guanb2=country2[k].getAttribute('b');

 	// 				var guant2=guant[k].value;
 	// 				guant[k].oninput=function()
 	// 				{
 	// 					choi.click();
 						 

 	// 				}
 	// 				if (guant2 == '')
 	// 				{ 
		// 				guant2 ='1'; 
		// 			}else
		// 			{
		// 				guant2=guant2;
		// 			} 
 					 
 	// 				  if(!isNaN(parseFloat(guanm2)))
 	// 				  {
 	// 				  	//总金额
 	// 				  	var sum2= parseFloat(list_sum2[k].getElementsByClassName(guanb2)[0].getElementsByClassName('money')[0].innerHTML) + parseFloat(guanm2*guant2);
 					  
 					  	
 	// 				  	// 人民币
 	// 				  	if(guanb2!='元')
 	// 				  	{
 	// 				  		rmb2=(guanm2*guant2*exchange[guanb2]/100).toFixed(2);
 	// 				  	}
 	// 				  	else
 	// 				  	{
 	// 				  		rmb2=(guanm2*guant2).toFixed(2);
 	// 				  	}
 					  	 
 	// 				  	 rmb2=parseFloat(rmb2)+pFloat(sumrmb2[k].innerHTML);
 	// 				  	  rmb2=rmb2.toFixed(2);
 	// 				  	 sumrmb2[k].innerHTML=rmb2+'元';
 					  	  
 	// 				  	  //人民币
 	// 				  	list_sum2[k].getElementsByClassName(guanb2)[0].getElementsByClassName('money')[0].innerHTML =sum2;
 	// 				  	list_sum2[k].getElementsByClassName(guanb2)[0].style.display = '';
 	// 				  	sumrmb2[k].style.display = '';
 	// 				  }	
 					  			 
 	// 			}
 				 
 			  
 	// 		}
	 // 			else
	 // 			{
	 // 				var data=this.getAttribute('data');
	 // 				var country = this.parentNode.parentNode.getElementsByClassName('guan0'+data);	
	 // 				var country2 = this.parentNode.parentNode.getElementsByClassName('guan1'+data);	
	 // 				var list_sum = document.getElementsByClassName('sum_money');
	 // 				var list_sum2 = document.getElementsByClassName('sum_money2');
	 // 				var guant = this.parentNode.parentNode.getElementsByClassName('guant2'+data);	
	 // 				var sumrmb = document.getElementsByClassName('sumrmb');
	 // 				var sumrmb2 = document.getElementsByClassName('sumrmb2');

	 // 				for (var j = 0; j < country.length; j++) 
	 // 				{
	 // 					 var guanm=country[j].getAttribute('m');
	 // 					 var guanb=country[j].getAttribute('b');
	 // 					  if(!isNaN(parseFloat(guanm)))
	 // 					  {
	 // 					  	//总金额
	 // 					  	var sum= parseFloat(list_sum[j].getElementsByClassName(guanb)[0].getElementsByClassName('money')[0].innerHTML) - parseFloat(guanm);
	 					  
	 					  	
	 // 					  	// 人民币
	 // 					  	if(guanb!='元')
	 // 					  	{
	 // 					  		rmb=(guanm*exchange[guanb]/100).toFixed(2);
	 // 					  	}
	 // 					  	else
	 // 					  	{
	 // 					  		rmb=guanm;
	 // 					  	}
	 					  	 
	 // 					  	 rmb=pFloat(sumrmb[j].innerHTML)-parseFloat(rmb);
	 // 					  	 rmb=rmb.toFixed(2);
	 // 					  	 sumrmb[j].innerHTML=rmb+'元';
	 					  	 
	 // 					  	  //人民币
	 // 					  	list_sum[j].getElementsByClassName(guanb)[0].getElementsByClassName('money')[0].innerHTML =sum;
	 // 					  	list_sum[j].getElementsByClassName(guanb)[0].style.display = '';
	 // 					  	if(sum ==0)
	 // 					  	{

	 // 					  		list_sum[j].getElementsByClassName(guanb)[0].style.display = 'none';
	 // 					  	}	
	 // 					  }	
 					  					 
 	// 				}
	 // 				// 代理费计算
	 // 				for (var k = 0; k < country2.length; k++) 
	 // 				{	  					 
	 // 					var guanm2=country2[k].getAttribute('m');
	 // 					var guanb2=country2[k].getAttribute('b');
	 // 					var guant2=guant[k].value;
	 // 					if (guant2 == '')
	 // 					{ 
		// 					guant2 ='1'; 
		// 				}else
		// 				{
		// 					guant2=guant2;
		// 				} 
	 // 					  if(!isNaN(parseFloat(guanm2)))
	 // 					  {
	 // 					  	//总金额
	 // 					  	var sum2= parseFloat(list_sum2[k].getElementsByClassName(guanb2)[0].getElementsByClassName('money')[0].innerHTML) - parseFloat(guanm2*guant2);
	 					  
	 					  	
	 // 					  	// 人民币
	 // 					  	if(guanb2!='元')
	 // 					  	{
	 // 					  		rmb2=(guanm2*guant2*exchange[guanb2]/100).toFixed(2);
	 // 					  	}
	 // 					  	else
	 // 					  	{
	 // 					  		rmb2=(guanm2*guant2).toFixed(2);
	 // 					  	}
	 					  	 
	 // 					  	 rmb2=pFloat(sumrmb2[k].innerHTML)-parseFloat(rmb2);
	 // 					  	 rmb2=rmb2.toFixed(2);
	 // 					  	 sumrmb2[k].innerHTML=rmb2+'元';
	 					  	  
	 // 					  	  //人民币
	 // 					  	list_sum2[k].getElementsByClassName(guanb2)[0].getElementsByClassName('money')[0].innerHTML =sum2;
	 // 					  	list_sum2[k].getElementsByClassName(guanb2)[0].style.display = '';
	 // 					  	if(sum2 ==0)
	 // 					  	{
	 // 					  		list_sum2[k].getElementsByClassName(guanb2)[0].style.display = 'none';	 					  		
	 // 					  	}

	 // 					  }	
	 					  			 
	 // 				}

 	// 		}
 	// 		// var data=this.getAttribute('data');
 	// 		// var country = this.parentNode.parentNode.getElementsByClassName('guan0'+data);
 	// 		// console.log(country);
 	// 	}
 	// };

	// var exchange=<?php echo json_encode($exchange,JSON_UNESCAPED_UNICODE); ?>;
	// console.log(exchange);
	// function fun1(obj)
	// {
	// 	// alert(1);
	// 	//alert(obj.getAttribute('data'));
	// 	var data=eval("("+obj.getAttribute('data')+")");
	// 	// alert(data);

	// 	var sum=[];
	// 	if(data['申请阶段官费货币'])sum[data['申请阶段官费货币']]=0;
	// 	if(data['申请阶段代理费货币']) sum[data['申请阶段代理费货币']]=0;
	// 	if(data['OA阶段官费货币']) sum[data['OA阶段官费货币']]=0;
	// 	if(data['OA阶段代理费货币']) sum[data['OA阶段代理费货币']]=0;
	// 	if(data['授权阶段官费货币']) sum[data['授权阶段官费货币']]=0;
	// 	if(data['授权阶段代理费货币']) sum[data['授权阶段代理费货币']]=0;
	// 	if(data['路浩代理费货币']) sum[data['路浩代理费货币']]=0;
	// 	if(data['其他费用货币']) sum[data['其他费用货币']]=0;

	// 	if(data['申请阶段官费货币']) sum[data['申请阶段官费货币']]+=pFloat(data['申请阶段官费']);
	// 	if(data['申请阶段代理费货币']) sum[data['申请阶段代理费货币']]+=pFloat(data['申请阶段代理费']);
	// 	if(data['OA阶段官费货币']) sum[data['OA阶段官费货币']]+=pFloat(data['OA阶段官费']);
	// 	if(data['OA阶段代理费货币']) sum[data['OA阶段代理费货币']]+=pFloat(data['OA阶段代理费'])*pFloat(obj.value);
	// 	if(data['授权阶段官费货币']) sum[data['授权阶段官费货币']]+=pFloat(data['授权阶段官费']);
	// 	if(data['授权阶段代理费货币']) sum[data['授权阶段代理费货币']]+=pFloat(data['授权阶段代理费']);
	// 	if(data['路浩代理费货币']) sum[data['路浩代理费货币']]+=pFloat(data['路浩代理费']);
	// 	if(data['其他费用货币']) sum[data['其他费用货币']]+=pFloat(data['其他费用']);

	// 	// alert(sum)
	// 	var show="";
	// 	var rmb=0;
	// 	for(var k in sum)
	// 	{
	// 		show+='+'+sum[k]+k;
	// 		rmb+=exchange[k]*sum[k];
	// 	}
	// 	show=show.substring(1);

	// 	// alert(show);
	// 	obj.parentElement.parentElement.getElementsByClassName('sum')[0].innerHTML=show;
	// 	obj.parentElement.parentElement.getElementsByClassName('rmb')[0].innerHTML=(rmb/100).toFixed(2)+'元';
	// }
	function pFloat(o)
	{
		o=parseFloat(o);
		return isNaN(o)?0:o;
	}

	// //重新计算
	// var oi_list=<?php echo json_encode($oi,JSON_UNESCAPED_UNICODE); ?>;
	// for(var i=0;i<oi_list.length;i++)
	// {
	// 	var o=document.getElementById('oi_'+oi_list[i]);
	// 	o.oninput(o);
	// }



</script>


</body>
</html>