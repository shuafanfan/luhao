<?php
// 每个文件都要引的文件
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';
if( $_REQUEST['action'] )
{
	// 如果接收到action就是本页提交表单，添加或者修改，保存
	if( $_GET['id'] )
	{
		// echo '执行修改操作';
		$dr = $db -> exec("update t_luhao_test set name={:name},sex={:sex},age={:age},dept={:dept},state={:state} where id = {:id}",
			array('name'=>$_POST['name'],'sex'=>$_POST['sex'],'age'=>$_POST['age'],'dept'=>$_POST['dept'],'state'=>$_POST['state'],'id'=>$_GET['id']));
	}
	else
	{
		// echo '执行添加操作';
		$dr = $db -> exec('insert into t_luhao_test(name,sex,age,dept,state) values({:name},{:sex},{:age},{:dept},{:state})',
			array('name'=>$_POST['name'],'sex'=>$_POST['sex'],'age'=>$_POST['age'],'dept'=>$_POST['dept'],'state'=>$_POST['state']));
	}
		echo "<script type='text/javascript'>window.frameElement.close();</script>";
}
// 接收id显示到表单中
if( $_GET['id'] )
{
	// 查该id下的信息，显示到表单中
	$dr = $db -> query('select * from ap.t_luhao_test where id = {:id}', array('id' => $_GET['id']));
	$res = $dr -> fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加或修改表单</title>
	<!-- 每个文件都要引的文件 -->
	<script type="text/javascript" src="../../inc.js"></script>
</head>
<body>
<table class="toolbar">
	<tr>
		<td align="right">
			<a href="javascript:location.reload()"><img src="../../images/refresh.png">刷新</a>
		</td>
	</tr>
</table>
<form method="post">
	<table class="table">
		<tr>
			<td align="right">
				用户名：
			</td>
			<td colspan="2">
				<input type="text" name="name" value="<?php echo !empty($_GET['id']) ? $res['name'] : '' ?>" id="name" ><span id="span"></span>
			</td>
		</tr>
		<tr>
			<td align="right">
				性别：
			</td>
			<td colspan="2">
				<input type="radio" name="sex" value="0" <?php echo !empty($_GET['id'])&&$res['sex']=='0' ? 'checked':'' ?> >男
				<input type="radio" name="sex" value="1" <?php echo !empty($_GET['id'])&&$res['sex']=='1' ? 'checked':'' ?> >女
			</td>
		</tr>
		<tr>
			<td align="right">
				年龄：
			</td>
			<td colspan="2">
				<input type="text" name="age" value="<?php echo !empty($_GET['id']) ? $res['age'] : '' ?>" >
			</td>
		</tr>
		<tr>
			<td align="right">
				部门：
			</td>
			<td colspan="2">
				<input type="text" name="dept" value="<?php echo !empty($_GET['id']) ? $res['dept'] : '' ?>" >
			</td>
		</tr>
		<tr>
			<td align="right">
				状态：
			</td>
			<td colspan="2">
				<input type="radio" name="state" value="0" <?php echo !empty($_GET['id'])&&$res['state']=='0' ? 'checked':'' ?> >正常
				<input type="radio" name="state" value="1" <?php echo !empty($_GET['id'])&&$res['state']=='1' ? 'checked':'' ?> >已删除
			</td>
		</tr>
		<tr>
			<td id="args"><div id="file"></div>上传文件</td>
			<td>显示文件 <canvas id="showpic" style="float:left;margin:55px;"></canvas></td>
		</tr>
		<tr>
			<td colspan="3"  align="center">
				<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" >
				<input type="hidden" name="action" value="<?php echo $_GET['id'] ?>" >
				<input type="submit" value="提交保存">
			</td>
		</tr>
	</table>
</form>
<script>
	$ddupfile2(
    {
      'el':document.getElementById("file"),
      'url':'../../upfile.php',
      'text':'拖拽上传委托书',
      'input':true,
      'name':'pic'
    })
    var files = document.getElementsByName("pic");
    files.onchange = function(){

      // 以下是显示在另一侧
      var lin=document.getElementById("pic");
      //创建一个文件对象
      var reader=new FileReader();
      //读取在文件框里选中的文件
      reader.readAsDataURL(lin.files[0]);
      //读取文件结束
      reader.onload=function(){
      //调用画布程序，把图画到画布上
        showpic(reader.result);
      }
      
    }
	// 文件上传改变后显示函数所调用的函数，用来显示图片
	function showpic(str){
		var can=document.getElementById("showpic");
		var width=320;
		var height=270;
		can.setAttribute("width",width);
		can.setAttribute("height",height);
		//得到画图对象
		context=can.getContext('2d');

		var a=document.createElement("img");
		a.setAttribute("src",str);
		a.onload=function(){
		  context.drawImage(a,0,0,width,height);
		}
	}
</script>
</body>
</html>