<?php
// 每个文件都要引的文件
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';
// 权限acl2
// $tmids=role::get_all_mid('/luhao_case_assignment_agent/');
// var_dump($tmids);echo '<br/>';
// foreach ($tmids as $tmid) {
// 	var_dump(acl2($tmid,'处理人分案'));
// }
// die;

// 查数据库
// $dr=$db->query("select * from t_test where id in ({:id})",array('id'=>array('1','2')));
// var_dump($dr->fetch());
// $row=$dr->fetch(PDO::FETCH_ASSOC);
// die;
// var_dump('asd');
// $patent=$db->row("select patent_number from t_luhao_case where id={:case_id}",array('case_id'=>'2469'));
// var_dump($patent);
// 	die;
// echo ini::get(0,'luhao_case_task_cpc_200602.body');die;
$sql='select * from ap.t_luhao_test';
$dr = $db -> query("select * from ap.t_luhao_test",5);

// 如果接收到sousou，则代表本页要执行查询
if($_REQUEST['sousou'])
{
	// 查询
	if($_REQUEST['sousou'] == '查询'){
		// 接收传来的值进行查询
		$sql_where=array();
		$sql_param=array();
		// 接收其他值
		$name = '';
		if(!empty($_GET['name']))
		{
			$name = $_GET['name'];
			$sql_where[]="name like {:name}";
			$sql_param['name']="%{$_GET['name']}%";
		}
		$sex = '';
		if($_GET['sex'] == '0' || !empty($_GET['sex']))
		{
			$sex = $_GET['sex'];
			$sql_where[]="sex={:sex}";
			$sql_param['sex']=$_GET['sex'];
		}
		$address = '';
		if(!empty($_GET['address']))
		{
			$address = $_GET['address'];
			$sql_where[]="address like {:address}";
			$sql_param['address']="%{$_GET['address']}%";
		}
		$age = '';
		if($_GET['age'] == '0' || !empty($_GET['age']))
		{
			$age = $_GET['age'];
			$sql_where[]="age={:age}";
			$sql_param['age']=$_GET['age'];
		}
		$dept = '';
		if(!empty($_GET['dept']))
		{
			$dept = $_GET['dept'];
			$sql_where[]="dept like {:dept}";
			$sql_param['dept']="%{$_GET['dept']}%";
		}
		$state = '';
		if($_GET['state'] == '0' || !empty($_GET['state']))
		{
			$state = $_GET['state'];
			$sql_where[]="state={:state}";
			$sql_param['state']=$_GET['state'];
		}
		// and连接
		$sql_where = implode(' and ', $sql_where);
		// where连接
		if(!empty($sql_where))
		{
			$sql_where = " where {$sql_where}";
		}
		$dr = $db -> query("select * from ap.t_luhao_test {$sql_where}",$sql_param,5);

	}
	// 取消查询
	else
	{
		header('location:index.php');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>xyl测试页</title>
	<!-- 每个文件都要引的文件 -->
	<script type="text/javascript" src="../../inc.js"></script>
</head>
<body>
<table class="toolbar">
	<tr>
		<td>
			xyl测试
		</td>
		<td align="right">
			<a href="javascript:location.reload()"><img src="../../images/refresh.png">刷新</a>
			<a href="javascript:void(0)" onclick="$window({'modal':true,'url':'add.php','title':'添加','width':'800','height':'800'})"><img src="../../images/add.png">添加</a>
		</td>
	</tr>
</table>
<table class="table">
	<form action="">
		<tr align="center">
			<td></td>
			<td><input type="text" name="name" placeholder="姓名关键字查询" value="<?php echo $name ?>"></td>
			<td>
				<select name="sex" id="">
					<option value="">-- 请选择性别 --</option>
					<option value="0" <?php echo $sex=='0' ? 'selected':'' ?> >男</option>
					<option value="1" <?php echo $sex=='1' ? 'selected':'' ?> >女</option>
				</select>
			</td>
			<td><input type="text" name="address" value="<?php echo $address ?>" placeholder="地址关键字查询"></td>
			<td><input type="text" name="age" value="<?php echo $age ?>" placeholder="年龄关键字查询"></td>
			<td><input type="text" name="dept" value="<?php echo $dept ?>" placeholder="部门关键字查询"></td>
			<td>
				<select name="state" id="">
					<option value="">-- 请选择状态 --</option>
					<option value="0" <?php echo $state=='0' ? 'selected':'' ?> >正常</option>
					<option value="1" <?php echo $state=='1' ? 'selected':'' ?> >已删除</option>
				</select>
			</td>
			<td><input type="submit" name="sousou" value="查询"><input type="submit" name="sousou" value="取消查询"></td>
		</tr>
	</form>
	<tr>
		<th>ID</th>
		<th>姓名</th>
		<th>性别</th>
		<th>地址</th>
		<th>年龄</th>
		<th>部门</th>
		<th>状态</th>
		<th>操作</th>
	</tr>
<?php
	while( $res=$dr -> fetch())
	{
?>
	<tr <?php echo $res['state'] ? 'style="color:grey;"':'' ?> align="center">
		<td><?php echo $res['id'] ?></td>
		<td><?php echo $res['name'] ?></td>
		<td><?php echo $res['sex'] ? '女' : '男' ?></td>
		<td><?php echo $res['address'] ?></td>
		<td><?php echo $res['age'] ?></td>
		<td><?php echo $res['dept'] ?></td>
		<td><?php echo $res['state'] ? '已删除' : '正常' ?></td>
		<td>
			<a href="javascript:void(0)" onclick="$window({
				'btn_refresh':true,
				'center':true,
				'url':'add.php?id=<?php echo $res['id'] ?>',
				'title':'修改'
				})">修改</a>
			<a href="javascript:void(0)" onclick="$confirm({
				'text':'确认撤销',
				'btn1click':function(obj){location.href='delete.php?id=<?php echo $res['id'] ?>'}
				})">删除</a>
		</td>
	</tr>
<?php
	}
?>
</table>
<div align="right"><?php echo $db -> navigation(1); ?></div>
<!-- <div><?php // echo $db -> navigation(2); ?></div> -->
<!-- info提示 -->
<div class="info">
	<table>
		<tr>
			<td>bb</td><td>bbb</td>
		</tr>
		<tr>
			<td colspan="2">aaaaa</td>
		</tr>
	</table>
</div>
<div>
<?php
function xl($key)
{
	$xl = array(
		"10"=>"研究生教育",
		"11"=>"博士研究生毕业",
		"12"=>"博士研究生结业",
		"13"=>"不是研究生肄业",
		"14"=>"硕士研究生毕业",
		"15"=>"硕士研究生结业",
		"16"=>"硕士研究生肄业",
		"17"=>"研究生班毕业",
		"18"=>"研究生班结业",
		"19"=>"研究生班肄业",
		"20"=>"大学本科/专科教育",
		"21"=>"大学本科毕业",
		"22"=>"大学本科结业",
		"23"=>"大学本科肄业",
		"28"=>"大学普通班毕业",
		"31"=>"大学专科毕业",
		"32"=>"大学专科结业",
		"33"=>"大学专科肄业",
		"40"=>"中等职业教育",
		"41"=>"中等专科毕业",
		"42"=>"中等专科结业",
		"43"=>"中等专科肄业",
		"44"=>"职业高中毕业",
		"45"=>"职业高中结业",
		"46"=>"职业高中肄业",
		"47"=>"技工学校毕业",
		"48"=>"技工学校结业",
		"49"=>"技工学校肄业",
		"60"=>"普通高级中学教育",
		"61"=>"普通高中毕业",
		"62"=>"普通高中结业",
		"63"=>"普通高中肄业",
		"70"=>"初级中学教育",
		"71"=>"初中毕业",
		"73"=>"初中肄业",
		"80"=>"小学教育",
		"81"=>"小学毕业",
		"83"=>"小学肄业",
		"90"=>"其他"
	);
	//return $xl[$key];
}
var_dump(xl(90));
?>

</div>
<div><a href="./plb_window.php">登记表</a></div>
<div><a href="./doadd.php">其他练习</a></div>
<div><a href="./lalala.php">拖拽上传</a></div>
<div><a href="./test.php">TEST</a></div>
<div><a href="./rowsql.php">rowsql</a></div>
<div><a href="javascript:void(0)" onclick="$window({'modal':true,'title':'SUPREME','width':'750','height':'550',
		onclose:function(){location.reload();},'url':'./supreme_window.php?mid=<?php echo $_GET['mid']; ?>'});">SUPREME</a></div>
<a href="rowsql.php" class="button">点我点我</a>
<div>
<?php
$json="[
	{'id':1,'code':'CN','name':'中国'},
	{'id':2,'code':'TW','name':'台湾地区'},
	{'id':3,'code':'HK','name':'中国香港'},
	{'id':4,'code':'MO','name':'澳门'},
	{'id':5,'code':'JP','name':'日本'},
	{'id':6,'code':'US','name':'美国'},
	{'id':7,'code':'KR','name':'韩国'},
	{'id':8,'code':'DE','name':'德国'},
	{'id':9,'code':'GB','name':'英国'},
	{'id':10,'code':'FR','name':'法国'},
	{'id':11,'code':'ARIPO','name':'AP非洲工业产权组织'},
	{'id':12,'code':'AL','name':'阿尔巴尼亚'},
	{'id':13,'code':'DZ','name':'阿尔及利亚'},
	{'id':14,'code':'AF','name':'阿富汗'},
	{'id':15,'code':'AR','name':'阿根廷'},
	{'id':16,'code':'AE','name':'阿联酋'},
	{'id':17,'code':'AW','name':'阿鲁巴岛'},
	{'id':18,'code':'OM','name':'阿曼'},
	{'id':19,'code':'AZ','name':'阿塞拜疆'},
	{'id':20,'code':'EG','name':'埃及'},
	{'id':21,'code':'ET','name':'埃塞俄比亚'},
	{'id':22,'code':'IE','name':'爱尔兰'},
	{'id':23,'code':'EE','name':'爱沙尼亚'},
	{'id':24,'code':'AD','name':'安道尔'},
	{'id':25,'code':'AO','name':'安哥拉'},
	{'id':26,'code':'AI','name':'安圭拉岛'},
	{'id':27,'code':'AG','name':'安提瓜和巴布达'},
	{'id':28,'code':'AT','name':'奥地利'},
	{'id':29,'code':'AU','name':'澳大利亚'},
	{'id':30,'code':'BB','name':'巴巴多斯'},
	{'id':31,'code':'PG','name':'巴布亚新几内亚'},
	{'id':32,'code':'BS','name':'巴哈马群岛'},
	{'id':33,'code':'PK','name':'巴基斯坦'},
	{'id':34,'code':'PY','name':'巴拉圭'},
	{'id':35,'code':'BH','name':'巴林岛'},
	{'id':36,'code':'PA','name':'巴拿马'},
	{'id':37,'code':'BR','name':'巴西'},
	{'id':38,'code':'BY','name':'白俄罗斯'},
	{'id':39,'code':'BM','name':'百慕大群岛'},
	{'id':40,'code':'BG','name':'保加利亚'},
	{'id':41,'code':'MP','name':'北马里亚纳'},
	{'id':42,'code':'BJ','name':'贝宁'},
	{'id':43,'code':'BE','name':'比利时'},
	{'id':44,'code':'IS','name':'冰岛'},
	{'id':45,'code':'PL','name':'波兰'},
	{'id':46,'code':'BA','name':'波斯尼亚和黑塞哥维那'},
	{'id':47,'code':'BO','name':'玻利维亚'},
	{'id':48,'code':'BZ','name':'伯利兹'},
	{'id':49,'code':'BW','name':'博茨瓦纳'},
	{'id':50,'code':'BT','name':'不丹'},
	{'id':51,'code':'BF','name':'布基纳法索'},
	{'id':52,'code':'BI','name':'布隆迪'},
	{'id':53,'code':'BV','name':'布维岛'},
	{'id':54,'code':'SO','name':'索马里'},
	{'id':55,'code':'SY','name':'叙利亚'},
	{'id':56,'code':'TT','name':'特立尼达和多巴哥'},
	{'id':57,'code':'AM','name':'亚美尼亚'},
	{'id':58,'code':'AN','name':'荷属安的列斯'},
	{'id':59,'code':'AS','name':'美属萨摩亚'},
	{'id':60,'code':'BD','name':'孟加拉国'},
	{'id':61,'code':'BN','name':'文莱达鲁萨兰国'},
	{'id':62,'code':'BU','name':'缅甸'},
	{'id':63,'code':'BX','name':'比荷卢'},
	{'id':64,'code':'CA','name':'加拿大'},
	{'id':65,'code':'CD','name':'刚果民主共和国'},
	{'id':66,'code':'CF','name':'中非'},
	{'id':67,'code':'CG','name':'刚果共和国'},
	{'id':68,'code':'CH','name':'瑞士'},
	{'id':69,'code':'CI','name':'科特迪瓦'},
	{'id':70,'code':'CK','name':'库克群岛'},
	{'id':71,'code':'CL','name':'智利'},
	{'id':72,'code':'CM','name':'咯麦隆'},
	{'id':73,'code':'CO','name':'哥伦比亚'},
	{'id':74,'code':'CR','name':'哥斯达黎加'},
	{'id':75,'code':'CS','name':'捷克斯洛戈克'},
	{'id':76,'code':'CU','name':'古巴'},
	{'id':77,'code':'CV','name':'佛得角'},
	{'id':78,'code':'CY','name':'塞浦路斯'},
	{'id':79,'code':'CZ','name':'捷克'},
	{'id':80,'code':'DJ','name':'吉布提'},
	{'id':81,'code':'DK','name':'丹麦'},
	{'id':82,'code':'DM','name':'多米尼克'},
	{'id':83,'code':'DO','name':'多米尼加共和国'},
	{'id':84,'code':'EC','name':'厄瓜多尔'},
	{'id':85,'code':'EH','name':'西撒哈拉'},
	{'id':86,'code':'EM','name':'欧洲内部市场协调局'},
	{'id':87,'code':'EP','name':'欧洲专利局'},
	{'id':88,'code':'ER','name':'厄立特里亚'},
	{'id':89,'code':'ES','name':'西班牙'},
	{'id':90,'code':'FI','name':'芬兰'},
	{'id':91,'code':'FJ','name':'斐济'},
	{'id':92,'code':'FK','name':'福克兰群岛(马尔维纳斯群岛)'},
	{'id':93,'code':'FO','name':'法罗群岛'},
	{'id':94,'code':'GA','name':'加蓬'},
	{'id':95,'code':'GD','name':'格林纳达'},
	{'id':96,'code':'GE','name':'格鲁吉亚'},
	{'id':97,'code':'GH','name':'加纳'},
	{'id':98,'code':'GI','name':'直布罗陀'},
	{'id':99,'code':'GL','name':'格陵兰'},
	{'id':100,'code':'GM','name':'冈比亚'},
	{'id':101,'code':'GN','name':'几内亚'},
	{'id':102,'code':'GQ','name':'赤道几内亚'},
	{'id':103,'code':'GR','name':'希腊'},
	{'id':104,'code':'GS','name':'南乔治亚岛和南桑德韦奇岛'},
	{'id':105,'code':'GT','name':'危地马拉'},
	{'id':106,'code':'GW','name':'几内亚比绍'},
	{'id':107,'code':'GY','name':'圭亚那'},
	{'id':108,'code':'HN','name':'洪都拉斯'},
	{'id':109,'code':'HR','name':'克罗地亚'},
	{'id':110,'code':'HT','name':'海地'},
	{'id':111,'code':'HU','name':'匈牙利'},
	{'id':112,'code':'HV','name':'上沃尔特'},
	{'id':113,'code':'ID','name':'印度尼西亚'},
	{'id':114,'code':'IL','name':'以色列'},
	{'id':115,'code':'IN','name':'印度'},
	{'id':116,'code':'IQ','name':'伊拉克'},
	{'id':117,'code':'IR','name':'伊朗'},
	{'id':118,'code':'IT','name':'意大利'},
	{'id':119,'code':'JE','name':'泽西岛'},
	{'id':120,'code':'JM','name':'牙买加'},
	{'id':121,'code':'JO','name':'约旦'},
	{'id':122,'code':'KE','name':'肯尼亚'},
	{'id':123,'code':'KG','name':'吉尔吉斯斯坦'},
	{'id':124,'code':'KH','name':'柬埔寨'},
	{'id':125,'code':'KI','name':'基里巴斯'},
	{'id':126,'code':'KM','name':'科摩罗'},
	{'id':127,'code':'KN','name':'圣基茨和尼维斯'},
	{'id':128,'code':'KP','name':'朝鲜'},
	{'id':129,'code':'KW','name':'科威特'},
	{'id':130,'code':'KY','name':'开曼群岛'},
	{'id':131,'code':'KZ','name':'哈萨克斯坦'},
	{'id':132,'code':'LA','name':'老挝'},
	{'id':133,'code':'LB','name':'黎巴嫩'},
	{'id':134,'code':'LC','name':'圣卢西亚'},
	{'id':135,'code':'LI','name':'列支敦士登'},
	{'id':136,'code':'LK','name':'斯里兰卡'},
	{'id':137,'code':'LR','name':'利比里亚'},
	{'id':138,'code':'LS','name':'莱索托'},
	{'id':139,'code':'LT','name':'立陶宛'},
	{'id':140,'code':'LU','name':'卢森堡'},
	{'id':141,'code':'LV','name':'拉托维亚'},
	{'id':142,'code':'LY','name':'利比亚'},
	{'id':143,'code':'MA','name':'摩洛哥'},
	{'id':144,'code':'MC','name':'摩纳哥'},
	{'id':145,'code':'MD','name':'摩尔多瓦'},
	{'id':146,'code':'MG','name':'马达加斯加'},
	{'id':147,'code':'MK','name':'马其顿'},
	{'id':148,'code':'ML','name':'马里'},
	{'id':149,'code':'MM','name':'缅甸'},
	{'id':150,'code':'MN','name':'蒙古'},
	{'id':151,'code':'MR','name':'毛里塔尼亚'},
	{'id':152,'code':'MS','name':'蒙特塞拉特'},
	{'id':153,'code':'MT','name':'马耳他'},
	{'id':154,'code':'MU','name':'毛里求斯'},
	{'id':155,'code':'MV','name':'马尔代夫'},
	{'id':156,'code':'MW','name':'马拉维'},
	{'id':157,'code':'MX','name':'墨西哥'},
	{'id':158,'code':'MY','name':'马来西亚'},
	{'id':159,'code':'MZ','name':'莫桑比克'},
	{'id':160,'code':'NA','name':'纳米比亚'},
	{'id':161,'code':'NE','name':'尼日尔'},
	{'id':162,'code':'NG','name':'尼日利亚'},
	{'id':163,'code':'NH','name':'新赫布里底'},
	{'id':164,'code':'NI','name':'尼加拉瓜'},
	{'id':165,'code':'NL','name':'荷兰'},
	{'id':166,'code':'NO','name':'挪威'},
	{'id':167,'code':'NP','name':'尼泊尔'},
	{'id':168,'code':'NR','name':'瑙鲁'},
	{'id':169,'code':'NZ','name':'新西兰'},
	{'id':170,'code':'OA','name':'非洲知识产权组织(OAPI)'},
	{'id':171,'code':'AP','name':'非洲工业产权组织'},
	{'id':172,'code':'PE','name':'秘鲁'},
	{'id':173,'code':'PH','name':'菲律宾'},
	{'id':174,'code':'PT','name':'葡萄牙'},
	{'id':175,'code':'PW','name':'帕劳'},
	{'id':176,'code':'QA','name':'卡塔尔'},
	{'id':177,'code':'RO','name':'罗马尼亚'},
	{'id':178,'code':'RU','name':'俄罗斯联邦'},
	{'id':179,'code':'RW','name':'卢旺达'},
	{'id':180,'code':'SA','name':'沙特阿拉伯'},
	{'id':181,'code':'SB','name':'所罗门群岛'},
	{'id':182,'code':'SC','name':'塞舌尔'},
	{'id':183,'code':'SD','name':'苏丹'},
	{'id':184,'code':'SE','name':'瑞典'},
	{'id':185,'code':'SG','name':'新加坡'},
	{'id':186,'code':'SH','name':'圣赫勒拿'},
	{'id':187,'code':'SI','name':'斯洛文尼亚'},
	{'id':188,'code':'SK','name':'斯洛伐克'},
	{'id':189,'code':'SL','name':'塞拉利昂'},
	{'id':190,'code':'SM','name':'圣马力诺'},
	{'id':191,'code':'SN','name':'塞内加尔'},
	{'id':192,'code':'SR','name':'苏里南'},
	{'id':193,'code':'ST','name':'圣多美和普林西比'},
	{'id':194,'code':'SU','name':'苏联'},
	{'id':195,'code':'SV','name':'萨尔瓦多'},
	{'id':196,'code':'SZ','name':'斯威士兰'},
	{'id':197,'code':'TC','name':'特克斯和凯科斯群岛'},
	{'id':198,'code':'TD','name':'乍特'},
	{'id':199,'code':'TG','name':'多哥'},
	{'id':200,'code':'TH','name':'泰国'},
	{'id':201,'code':'TJ','name':'塔吉克斯坦'},
	{'id':202,'code':'TM','name':'土库曼斯坦'},
	{'id':203,'code':'TN','name':'突尼斯'},
	{'id':204,'code':'TO','name':'汤加'},
	{'id':205,'code':'TP','name':'东帝汶'},
	{'id':206,'code':'TR','name':'土耳其'},
	{'id':207,'code':'TV','name':'图瓦卢'},
	{'id':208,'code':'TZ','name':'坦桑尼亚'},
	{'id':209,'code':'UA','name':'乌克兰'},
	{'id':210,'code':'UG','name':'乌干达'},
	{'id':211,'code':'UY','name':'乌拉圭'},
	{'id':212,'code':'UZ','name':'乌斯别克斯坦'},
	{'id':213,'code':'VA','name':'梵蒂冈'},
	{'id':214,'code':'VC','name':'圣文森特和格林纳丁斯'},
	{'id':215,'code':'VE','name':'委内瑞拉'},
	{'id':216,'code':'VG','name':'维尔京群岛'},
	{'id':217,'code':'VN','name':'越南'},
	{'id':218,'code':'VU','name':'瓦努阿图'},
	{'id':219,'code':'WO','name':'世界知识产权组织国际局'},
	{'id':220,'code':'WS','name':'萨摩亚'},
	{'id':221,'code':'YD','name':'民主也门'},
	{'id':222,'code':'YE','name':'也门'},
	{'id':223,'code':'YU','name':'南斯拉夫'},
	{'id':224,'code':'ZA','name':'南非'},
	{'id':225,'code':'ZM','name':'赞比亚'},
	{'id':226,'code':'ZR','name':'扎伊尔'},
	{'id':227,'code':'ZW','name':'津巴布韦'},
	{'id':228,'code':'IB','name':'国际局'},
	{'id':229,'code':'RS','name':'塞尔维亚'},
	{'id':230,'code':'ME','name':'黑山'},
	{'id':231,'code':'OAPI','name':'非洲知识产权组织'},
	{'id':232,'code':'PR','name':'波多黎各自由联邦'},
	{'id':233,'code':'MH','name':'马绍尔群岛共和国'},
	{'id':234,'code':'EU','name':'欧盟'},
	{'id':235,'code':'EA','name':'欧亚专利组织'},
	{'id':236,'code':'GC','name':'海湾阿拉伯国家合作委员会'},
	{'id':237,'code':'BQ','name':'博纳尔，圣饿斯塔修斯'},
	{'id':238,'code':'CW','name':'库拉索'},
	{'id':239,'code':'IM','name':'马恩岛'},
	{'id':240,'code':'SS','name':'南苏丹'},
	{'id':241,'code':'TL','name':'东帝汶'},
	{'id':242,'code':'QZ','name':'欧盟植物品种办公室'},
	{'id':243,'code':'XN','name':'北欧专利研究所(NPI)'},
	{'id':244,'code':'GC','name':'根西岛'},
	{'id':245,'code':'IB，WO','name':'国际局(WIPO))'},
]";
// var_dump($json);
?>

</div>
<div align="center"><span style="border:1px solid blue;padding: 3px 15px 3px 15px;">aaa</span><br/><br/>

<?php

aaa('20161101','20170210');
function aaa($start_dt,$finish_dt)
{
	// 分别取得,起始日期的星期，起始日到终止日的天数
	
	$str_start_dt = strtotime($start_dt);
	$str_finish_dt = strtotime($finish_dt);
	// var_dump($start_dt);echo '<br/>';
	// var_dump($finish_dt);echo '<br/>';
	
	// 起始日期的星期
	$start_wee = date('w',$str_start_dt);
	$finish_wee = date('w',$str_finish_dt);
	// var_dump($start_wee);echo '<br/>';
	// var_dump($finish_wee);echo '<br/>';
	
	// 起始日期到终止日期的天数
	$s_f_num = ( $str_finish_dt - $str_start_dt ) / (60 * 60 * 24);
	// var_dump($s_f_num);echo '<br/>';
	
	// 重新定义起始和终止日期
	$str_start_dt -= (60*60*24*($start_wee-1));
	$str_finish_dt -= (60*60*24*($finish_wee-1));
	// 得到两个周一
	$start_wee = date('w',$str_start_dt);
	$finish_wee = date('w',$str_finish_dt);
	// var_dump($start_wee);echo '<br/>';
	// var_dump($finish_wee);echo '<br/>';
	
	// 获得新的起始日期到终止日期天数
	$s_f_num = ( $str_finish_dt - $str_start_dt ) / (60 * 60 * 24);
	// 获得星期数，起始到终止有多少个星期
	$wee_num = $s_f_num/7;
	// var_dump($wee_num);
	
	// 定义一个数组，将年份做key将时间做value
	$abc = array();
	// 循环生成年月日
	for($i=0,$y=0,$s=$str_start_dt;$i<$wee_num;$i++)
	{
		// 记录年份
		$y2 = date('Y',$s);
		if($y != $y2)
		{
			// echo "<b>".$y2."</b><br/>";
			$y=$y2;
		}
		// echo date("m月d日",$s)."-";
		$echo = date("m月d日",$s)."-";
		$s+=(60 * 60 * 24 * 6);
		// echo date("m月d日",$s)."<br/>";
		$echo .= date("m月d日",$s)."<br/>";
		$s+=(60 * 60 * 24);
		$abc[$y][] = $echo;
	}
	// print_r($abc);
	foreach ($abc as $key => $value)
	{
		echo "<b>".$key."年</b><br/>";
		for($j=count($value)-1;$j>0;$j--)
		{
			echo $value[$j];
		}
	}
}


die;
// 今年年份
$year = date('y');
// 今月月份
$month = date('n');
// 今日日期
$day = date('j');
// 今日星期几,0周日
$wee = date('w');
// 这一个月的天数
$mdays = date('t');
// var_dump($year);
// var_dump($month);
// var_dump($day);
// var_dump($wee);
// 下周一
$xzy = $day+(7-$wee+1);
if($xzy>$mdays)
{
	// 如果下周一日期大于当月天数
	$xzy = $xzy - $mdays;
	$month = $month + 1;
}
// var_dump($xzy);

// 用下周一加6天就是下周日
$xzr = $xzy + 6;
// var_dump($xzr);
// 周一到周日就出来了，加上月份
var_dump($month."月".$xzy."日-".$month."月".$xzr."日");

// 给一个截止时期，比如：20161231
// 判断给定时间到今天的天数
$str_today = strtotime(date('Ymd'));
$str_thisday = strtotime('20161231');
$thisday_num = ( $str_thisday - $str_today ) / (60 * 60 * 24);
var_dump($thisday_num);
// 这个天数减去到下周一的天数,即计算要显示多少天的日期
$d = $thisday_num - (7-$wee+1);
// var_dump($d); 
// 取整，有几周，余数为最后一周前几天
$wee_x_num = intval($d / 7);
$wee_y_num = intval($d % 7);
// var_dump($wee_x_num);
// var_dump($wee_y_num);

$amdays = date('t', strtotime("2016-12")); //返回指定月份的天数
// var_dump($amdays);die;

// 循环周数，输出
for($i;$i<$wee_x_num;$i++)
{
	$xzy = $xzy+(7);
	if($xzy>$amdays)
	{
		// 如果下周一日期大于当月天数
		$xzy = $xzy - $amdays;
		$month = $month + 1;
		if($month == 13)
		{
			$month = 1;
			$year = $year + 1;
		}
	}
	echo "<br/>20".$year."年".$month."月".$xzy."日-";
	$xzr = $xzy + 6;
	if($xzr>$amdays)
	{
		// 如果下周一日期大于当月天数
		$xzr = $xzr - $amdays;
		$month = $month + 1;
		if($month == 13)
		{
			$month = 1;
			$year = $year + 1;
		}
	}
	echo "20".$year."年".$month."月".$xzr."日";
}
?>
</div>
</body>
</html>
<?php
/*
**
*函数
*sin(弧度数)
*deg2rad(角度数)角度转化弧度
*
* 角度 $a
* x $x
* y $y
 */
$x=cos(deg2rad($a));
$y=sin(deg2rad($a));

?>