<?php
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';
if(isset($_POST['sup'])&&!empty($_POST['sup']))
	$sup = $_POST['sup'][array_rand($_POST['sup'])];
else
	$sup = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SUPREME_WINDOW</title>
	<script type="text/javascript" src="../../inc.js"></script>
</head>
<body>
<table class="toolbar">
	<tr>
		<td><?php echo $sup; ?></td>
		<td align="right">
			<a href="javascript:location.reload()"><img src="../../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<form action="" method="post" id="sup__form">
	Option 1<input type="text" name="sup[]"><br/>
	Option 2<input type="text" name="sup[]"><br/>
	Option 3<input type="text" name="sup[]"><br/>
	Option 4<input type="text" name="sup[]"><br/>
	Option 5<input type="text" name="sup[]"><br/>
	Option 6<input type="text" name="sup[]"><br/>
	<input type="button" value="Add" onclick="add(this)">
	<input type="submit" value="GO">
</form>
<script>
	function add($t)
	{
		form=document.getElementById('sup__form');
		sup_num=document.getElementsByName('sup[]').length;
		input=document.createElement('input');
		input.setAttribute('type','text');
		input.setAttribute('name','sup[]');
		// form.insetBefore($t,input);
		// console.log(sup_num);
	}
</script>
	
</body>
</html>