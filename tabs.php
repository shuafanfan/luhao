<div class="tab">
<?php
init_db(1);
 if(acl('管理',false)||acl('客服',false))
	{	 
		$type = $db1->query("select * from t_luhao_case_offlist_setup group by id");
		$param=array();
		while($types=$type->fetch(PDO::FETCH_ASSOC))
		{
			$param[]=$types['id'];
		}
		//var_dump($param);	 
		if(acl('客服',false)){
			$enable="and enable=1";
		 }else{
		 	$enable="";
		 }
		$count=$db1->count("select count(*) from t_luhao_case_offlist where setup_id in ({:type}) {$enable}",array('type'=>$param));
		//var_dump($count);die;
	}

	if(!isset($_GET['show'])||empty($_GET['show']))
		$hover='button_hover';
	else
		$hover='';
?>
	<a class="button <?php echo $hover; ?>" href="index.php?mid=<?php echo $_GET['mid']; ?>">全部(<?php echo $count; ?>)</a>

<?php
	// 查询种类
	$type = $db1 -> query("select * from t_luhao_case_offlist_setup group by type order by id asc");
	while($types=$type->fetch(PDO::FETCH_ASSOC)){
	if(acl('管理',false)||acl('客服',false))
	{
		if(acl('客服',false)){
			$enable=" and enable=1";
		 }else{
		 	$enable="";
		 }
		$param['type']=array($types['id']);
		$count=$db1->count("select count(*) from t_luhao_case_offlist  where setup_id in ({:type}) {$enable}",$param);
	

	if(isset($_GET['show']) && $_GET['show']==$types['id'])
		$hover='button_hover';
	else
		$hover='';
?>
	<a class="button <?php echo $hover; ?>" href="index.php?mid=<?php echo $_GET['mid']; ?>&show=<?php echo $types['id']; ?>"><?php echo $types['type']; ?>(<?php echo $count; ?>)</a>

<?php 
	}
	}
 ?>

 
<?php
// 新加功能
if(acl('管理',false)||acl('客服',false))
	{	 

		$count=$db1->count("select count(*) from t_luhao_case_offlist_country where enable=1");
		//var_dump($count);die;
	}

	if(isset($_GET['show']) && $_GET['show']=='new')
		$hover='button_hover';
	else
		$hover='';
?>
	<a class="button <?php echo $hover; ?>" href="project.php?mid=<?php echo $_GET['mid']; ?>&show=new">新功能(<?php echo $count; ?>)</a>
</div>