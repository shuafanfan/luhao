<?php

include dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/inc.php';

// 查询所有项目名称
	$project = $db->query("select * from t_luhao_case_offlist_project");

	

// 查询汇率
$rate = $db->row("select * from t_luhao_exchange_rate order by id desc ");

// 保存信息
if($_POST['submit1']=="确认")
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
	var_dump($args);
	echo "</pre>";
	//$args=$_POST;

	
	
	
	
	$dr2=$db->exec("insert t_luhao_case_offlist_country (country,args,enable,oc)
									values ({:country},{:args},{:enable},{:oc})",
			array(
				'country'=>$_POST['country'],
				'args'=>json_encode($args,JSON_UNESCAPED_UNICODE),		
				'enable'=>$_POST['enable'],
				'oc'=>time()
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
			<a href="javascript:location.href='projectnew.php'"><img src="../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<form action="projectnew.php" method="post" id="form1">
<table class="table" >
	<tr>
		<td style="width:120px" align="right">项目名称</td>
		<td style="width:120px" align="right" >国家地区</td>
		<td ><input type="text" value="" name="country"></td>
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
			<input name="官费[]" type="text" value="" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}">
			<select name="官费货币[]" id="" class="select">
				<option value="元" class="rmb">元</option>
				<option value="美元" class="us">美元</option>
				<option value="日元" class="jp">日元</option>
				<option value="韩元" class="ko">韩元</option>
				<option value="欧元" class="eu">欧元</option>
				<option value="加元" class="ca">加元</option>
				<option value="英镑" class="gb">英镑</option>
				<option value="瑞典克朗" class="ko">瑞典克朗</option>
				<option value="瑞法" class="eu">瑞法</option>
				<option value="泰铢" class="ca">泰铢</option>
				<option value="新元" class="ca">新元</option>
				<option value="台币" class="gb">台币</option>
			</select>
		</td>

		<td align="right">代理费</td>
		<td  id="">
			<input  name="代理费[]" id="OA阶段代理费" type="text" value="" class="num" oninput="if(/[^\d.]/g.test(this.value)){this.value='';}" >
			<select name="代理费货币[]" id="OA阶段代理费货币" class="select">
				<option value="元" class="rmb">元</option>
				<option value="美元" class="us">美元</option>
				<option value="日元" class="jp">日元</option>
				<option value="韩元" class="ko">韩元</option>
				<option value="欧元" class="eu">欧元</option>
				<option value="加元" class="ca">加元</option>
				<option value="英镑" class="gb">英镑</option>
				<option value="瑞典克朗" class="ko">瑞典克朗</option>
				<option value="瑞法" class="eu">瑞法</option>
				<option value="泰铢" class="ca">泰铢</option>
				<option value="新元" class="ca">新元</option>
				<option value="台币" class="gb">台币</option>
			</select>
			<select name="counts[]" id="counts" onchange="co()">
				<option value="小时">/小时</option>
				<option value="次">/次</option>
			</select>
			<span id="two">*<input style="width: 30px;" type="text" id="小时次数" name="小时次数[]" value="1">
			</span>	
		</td>
	</tr>
<?php 
	}
?>
	<tr>
		<td style="width:120px" align="right">是否启用</td>
		<td colspan="4">
			<input type="radio" name="enable" value="1" checked="true" required>启用
			<input type="radio" name="enable" value="2" required>禁止
		</td>
	</tr>

	<tr>
		<td colspan="5" align="center">
		<input type="hidden" id="total" value="" name="total[]">
		<input id="submit1" type="button" name="submit1" value="确认" onclick="$confirm({'text':'确认完成？','btn1click':function(obj){document.getElementById('submit1').type='submit';document.getElementById('submit1').click();}})">
		</td>
	</tr>
 </table>
 </form>
<script>

	// 改变类型
	function select_type()
	{	
		var form1=document.getElementById('form1');
		form1.submit();
	}
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