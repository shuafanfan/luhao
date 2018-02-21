<?php
// 每个文件都要引的文件
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';
// 更改用户状态
$dr = $db -> exec('update ap.t_luhao_test set state = {:state} where id = {:id}',array('state'=>'1','id'=>$_GET['id']));
header('location:../xyl/index.php');
?>