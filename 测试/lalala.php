<?php
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';

// 这个是点击按钮上传的
if($_FILES['wts']['tmp_name'])
{
	$files=file::upfiles('wts'); // 直接使用封装方法上传
	foreach ($files as  $value) 
	{
		
		$files[]=array('上传人'=>$_SESSION['ap_uid'],'上传时间'=>time(),'文件路径'=>urldecode($value));
	}
	
}

// 这个是拖拽上传的
if($_POST['wts'])
{
	foreach ($_POST['wts'] as  $value) 
	{
		
		$files[]=array('上传人'=>$_SESSION['ap_uid'],'上传时间'=>time(),'文件路径'=>urldecode($value));
	}
}

var_dump($files);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>拖拽上传</title>
	<script src="../../inc.js"></script>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
<span>拖拽上传</span>
<div id="test1" style="position:absolute;left:'10px';top:'380px';width:'200px';height:'200px';background-color:'#ccccff';"></div>
<div id="test2" style="position:absolute;left:'220px';top:'380px';width:'200px';height:'200px';background-color:'#ccffcc';"></div>
<div id="test3" style="position:absolute;left:'430px';top:'380px';width:'200px';height:'200px';background-color:'#ffcccc';"></div>
<div id="test4" style="position:absolute;left:'640px';top:'380px';width:'200px';height:'200px';background-color:'#ffccff';"></div>
<table width="600" border="1" class="table">
	<tr>
		<td height="100">单元格11</td>
		<td height="100">单元格12</td>
		<td height="100">单元格13</td>
	</tr>
	<tr>
		<td height="100">单元格21</td>
		<td height="100"><div style="position: 'relative';width: '100%';height: '100%';" id="test5">单元格22</div></td>
		<td height="100">单元格23</td>
	</tr>
	<tr>
		<td height="100">单元格31</td>
		<td height="100">单元格32</td>
		<td height="100">单元格33</td>
	</tr>

</table>
	<div><input type="submit" value="保存"></div>
</form>	
<script>
	$ddupfile2({'el':document.getElementById('test1'),'success':function(data){alert(data)},'text':'提示文本1'});
	$ddupfile2({'el':document.getElementById('test2'),'success':function(data){alert(data)},'text':'提示文本2'});
	$ddupfile2({'el':document.getElementById('test3'),'success':function(data){alert(data)},'text':'提示文本3'});
	$ddupfile2({'el':document.getElementById('test4'),'success':function(data){alert(data)},'text':'提示文本4s'});
	$ddupfile2(
    {
      'el':document.getElementById("test5"),
      'url':'../../upfile.php',
      'text':'拖拽上传委托书',
      'input':true,
      'name':'wts'
    })
</script>

<table class="toolbar">
	<tr>
		<td><a href="javascript:location.reload()"><img src="../../images/refresh.png" alt="">刷新</a></td>
	</tr>
</table>
</body>
</html>