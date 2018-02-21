<?php
include dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/inc.php';
if(isset($_REQUEST['action']))//这是为了只有提交表单的时候，才走这段代码
{
	var_dump($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<script type="text/javascript" src="../../inc.js"></script>
	<script type="text/javascript" src="../email.js"></script>
</head>
<body>
	<table class="toolbar">
		<tr>
			<td align="right"><a href="javascript:location.reload()"><img src="../../images/refresh.png">刷新</a></td>	
		</tr>
	</table> 
	<div>
<?php

	// 文件名数组
	$filename = array("t1.tif","t2.tif","t3.tif","t4.tif");
	// 文件类型 string
	$filetype = "tiff";
	// 生成文件名
	$pdfname = "done.pdf";

	print pdf($filename,$filetype,$pdfname);

	function pdf($filename,$filetype,$pdfname)
	{
		$p = PDF_new();
		PDF_open_file($p,"");
		foreach($filename as $f)
		{
			PDF_begin_page($p,595,842);
			$image = PDF_load_image($p,$filetype,$f,"");
			PDF_place_image($p,$image,0,0,.24);
			PDF_end_page_ext($p, "");
		}
		PDF_end_document($p, "");
		$buf = PDF_get_buffer($p);
		$len = strlen($buf);
		header("Content-type: application/pdf");
		// header("Content-Length: $len");
		header("Content-Disposition: inline; filename=".$pdfname);
		return $buf;
		PDF_delete($p);
	}

?> 
	</div>
	<a href="<?php echo file::dlnk(pdf($filename,$filetype,$pdfname));?>">ffffff</a>
</body>
</html>