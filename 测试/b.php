<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="../../Scripts/tselect.css">
	<script src="../../Scripts/tselect.js"></script>
</head>
<body>
	<div id="a">
	<input type="text" id="a1" name="aa">
	<input type="text" id="b1" name="bb">
	</div>
	<script>
		$tselect(
	{//国家选择
		"el_select":a1,
		'el_value':b1,
		"url":"a.php?mid="+$get('mid'),
		'selected':true,
		"onload":function(){},
		"onselected":function()//选择后设置隐藏域的值
		{
			var cn = document.getElementById(input1Id).value;
			$ajax({'dataType':'json','url':'../gb_2659/gb2659_data2.php?cn='+cn+'&mid'+$get('mid'),
			'success':function(data)
				{
					var val = '';
					if(data)
					{
						if(args.type == 'c3')
							val = data.c3;
						else if(args.type == 'n3')
							val = data.n3;
						else
							val = data.c2;
					}
					document.getElementById(input2Id).value = val;
					if(args.func)
						args.func(val);//执行回调函数
				}
			})
		}
	})
	</script>
</body>
</html>

<!-- 另外的一个，这个好用 -->
<tr>
    <td width="120" align="right" nowrap>联系人类型</td>
    <td colspan="3">
      <input type="text" name="type" id="type" style="width:82%;" value="<?php echo $row['type']?$row['type']:''?>">
    </td>
  </tr>
  <script>
    $tselect(
    {
      "el_select":'type',
      "url":"../luhao_crm/contact_type.php?mid="+$get('mid'),
      'selected':false,
      "onload":function(){},
      "onselected":function(){}
    })
  </script>