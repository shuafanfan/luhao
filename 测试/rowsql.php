<?php
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';
$url="http://192.168.6.254/ap/upload/201701/23154758/%E5%95%86%E6%A0%87%E5%A4%96%E5%86%85%E8%B4%A6%E5%8D%95170123.pdf";
echo file::download2($url);
die;
?>
<a href="javascript:location.reload()"><img src="../../images/refresh.png">刷新</a><br/>
<?php
	$dr=$db->query("select args from t_luhao_case where no is null and id = {:id}",array('id'=>'1664'));
	$args = $dr->fetch();
	// var_dump($args);

	// echo '<hr/>';
	foreach ($args as $key => $value) {
		var_dump($key.'===>'.$value);echo "<br/>===<br/>";
	}

echo '============================================================================';
echo '============================================================================';
echo '============================================================================';

	$dr=$db->row("select args from t_luhao_case where no is null and id = {:id}",array('id'=>'1664'));
	$args2 = $dr;
	var_dump($args2);

	// $args = json_decode($args,true);
	// $args['错误'] = array('标记人'=>$_SESSION['ap_uid']);
	// $args = json_encode($args,true);
	
	
echo '<br/><hr/><hr/><hr/>下面是其他练习<hr/><hr/><hr/>';	
?>
<?php
// 每个文件都要引的文件
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/luhao_crm/class.php';
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/luhao_case/class.php';
// 输出session，存有ap_uid和ap_uname
print_r($_SESSION);

// 查看权限
if(acl('管理',false))
{
	$priv = 'admin';
}
else if(acl('查看',false))
{
	$priv = 'see';
}
if($priv == 'admin' || $priv == 'see')
{
	echo '查看';
}
else
{
	echo '您啥都不能干呀~巴扎黑！';
}

// 读取关注，返回ture，false，这里返回NULL
// fav::get($_SESSION['ap_uid']);
$favget = fav::get($_SESSION['ap_uid']);
var_dump($favget);

// 获取指定条件的用户列表
var_dump( fav::get_list_uid() );

// 上传文件
if(!empty($_FILES)){
	// 执行上传
	echo '<br/>';var_dump($_FILES);
	$file = file::upfile('file');
	echo "文件路径：{$file}"; // 201609/21150036/ceshi_test.txt
	// $delfile = file::delete($file);
}

// 创建目录
// var_dump( file::upload_mkdir() ); //  "/var/www/html/ap/upload/201609/21151126/"
// 临时目录
// echo file::tempdir();//  /var/www/html/ap/download/t1474442373/
// file::rmdir('/var/www/html/ap/download/t1474442373/');
// 读取INI值
var_dump(ini::get($_SESSION['ap_uid'],'bg'));
echo '<hr/>';
// 发送内部消息
// msg::send(9,'yigexiaoxi','xiaoxixixi');
// msg::sys(9,'绝命毒shi','老白',);
echo "<select>";
options::dd();
echo "</select>";
echo "<br/>";
options::topdept(123);echo '<hr/>';
echo role::check_parent_dept_enabled(123);echo "<br/>";
// 返回角色
var_dump(role::id('#行政单位','d'));echo "<br/>";
var_dump(role::id2('测试模块'));echo "<br/>";
// 判定角色是否在指定父层下面
if(role::is_child(123,45,'dd'))
{
	echo '123部门是45的下属部门';
}
else
{
	echo '123部门不是45的下属部门';
}
echo "<br/>";
var_dump(role::name(8)); // 获取角色的名称
var_dump(role::get_info(8)); // 获取角色的信息
var_dump(role::parent_deptname(123)); // 获取指定角色上层部门
var_dump(role::get_parent_id(234,'mm')); // 查找234模块的上层模块
// 用户登录
// $res = role::sign2('user','123123');
// if($res['success'])
// {
// 	echo "登陆成功，用户编号：{$res['ap_uid']}";
// }
// else
// {
// 	echo "登陆失败，失败原因{$res['info']}";
// }
echo "<hr/>";
// 获取发给该电话的最后一条短信
$ret = sms::get_last('13386842561');
print_r( $ret );
echo '<hr/>';
// 给电话发信息
// $ret = sms::send('13386842561','你好！这是测试短信~');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>XYL又测试啦！</title>
	<!-- 每个文件都要引的文件 -->
	<script type="text/javascript" src="../../inc.js"></script>
</head>
<body>
<table class="toolbar">
	<tr>
		<td><a href="javascript:location.reload()"><img src="../../images/refresh.png" alt="">刷新</a></td>
	</tr>
</table>
<hr/>
上传文件
<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="file"><input type="submit">
</form>
<div id="box"></div>
<script type="text/javascript">
	
$contextmenu({
	id='box'，
	'submenu':
	[
		{
			'id':'desktop_add',
			'html':'增加至~~'，
		}，
		{
			'id':'toolbar_add',
			'html':'增加至~~'
		}
	]
});
</script>

<hr/>
<span>$jctree树形选择框</span><br/>
<div id="dept_id_el">
	<script>
		$jctree({
			"el":"dept_id_el", // 插入的节点名称，不设置返回jctree对象
			"el_id":"dept_id", // 当前节点id
			"el_insert_before":"true", // 插到节点内最前
			"name":"dept_id", // 生成input名称
			"mode":"1", // 单选
			"selectleaf":"true", // 只选叶子 
			"popup_width":"160px", // 弹出区宽度 
			"dataurl":"../../role.php?type=dd&enabled=1", // 数据读取地址，异步
		});
	</script>
</div>
<div id="created_uid_el">
	<script>
		$jctree(
		{
			"el":"created_uid_el",
			"el_id":"created_uid_el",
			"el_insert_before":true,
			"name":"created_uid",
			"mode":1,
			"selectleaf":true,
			"popup_width":'160px',
			"selected":[{'i':'9','t':"9"}], // 默认选项
			"dataurl":"..\/role.php?type=du&id="
		});
	</script>
</div>
<table class="table">
	<tr id="table2datarow">
		<td>
<?php
			$t = '1474595006';
			$t2 = time() - $t;
			var_dump( $t2>3600*24 );
?>
		</td>
		<td>aaaaa</td>
		<td>aaaaa</td>
		<td>aaaaa</td>
	</tr>
</table>
<div>
	
<?php
	$wheres='';
	$codes=array('p1/email_x12','z1/write_01','p1oa/oa','p1reexam/reexam','p5/email_x12','3p1/email_x12','3p1oa/oa','9p1/email_x12','9p1retrieval/retrieval','p1/email_x12','z1/write_01','p1oa/oa','p1reexam/reexam','p5/email_x12','9p1/email_x12','9p1retrieval/retrieval','p1/write','3p1/email_x12','3p1oa/oa');
	$param['code']=$codes;
	$get_task=$db->query("
		SELECT case1.no,case1.type,case1.groups,case1.title,case1.file_dt,case1.args,case1.file_uid,task.args,task.dept_id,task.state,task.case_id,task.id,task.agent_id
		FROM ap.t_luhao_case_task task
		join ap.t_luhao_case case1 on case1.id=task.case_id where task.code in ({:code}) {$wheres}
		order by task.id desc",$param,20);
	while($info=$get_task->fetch())
	{
		// caseinfo::showtype($info); // 输出所属部门
		// var_dump($info);
		// var_dump($info[5]); // 字符串
		$case_args = json_decode($info[5],true); // 变成数组
		// var_dump($case_args);
		crm::showname($case_args['客户']);
	}
?>
</div>
<div>
	<?php
		// 使用路径读取模块编号
		$priv='流程';
		echo role::mid('/luhao_case/',$priv);
		echo '<br/>';
		echo role::mid('/luhao_case/');
		echo '<br/>';
		$priv='客服';
		echo role::mid('/luhao_case/',$priv);
	?>
</div>
</body>
</html>