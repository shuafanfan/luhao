<script>
// 取得name的值
var name = document.getElementsById('name');alert();
// 验证
name.onblur = function()
{
	var span = document.getElementsById('span');
				span.innerHTML('重名');
	$.ajax({
		url:'ajax.php?name='+name,
		success:function(data){
			alert(data);
			if(data == '1')
			{
				span.innerHTML('重名');
			}
			else
			{
				span.innerHTML('OK');
			}
		},
		error:function()
		{
			span.innerHTML('服务器连接失败');
		}
	});
}
</script>
<?php
// 接收name
$name = $_REQUEST['name'];
$dr = $db -> query('select * from ap.t_luhao_test where name={:name}',array('name'=>$name));
print_r($dr);
?>