<?php
// pdf

$pdf=new PDFLib();

$pdf->begin_document('','');

$pdf->begin_page_ext(595,842,'');

$pd=$pdf->open_pdi_document('1.pdf','','');
while($pg=$pdf->open_pdi_page($pd,1))
	$pdf->fit_pdi_page($pg);

// $tiff=$pdf->load_image('auto','000001.tif','');
// $pdf->fit_image($tiff,0,0,'');
$pdf->end_page_ext('');

$pdf->end_document('');

// $buf=$pdf->get_buffer();
// echo $buf;
// die;

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=hello.pdf");

echo $pdf->get_buffer();

die;

// tif

$pdf=new PDFLib();

$pdf->begin_document('','');

$pdf->begin_page_ext(595,842,'');
$tiff=$pdf->load_image('auto','t1.tif','');
$pdf->fit_image($tiff,0,0,'');
$pdf->end_page_ext('');

$pdf->end_document('');

// $buf=$pdf->get_buffer();
// echo $buf;
// die;

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=hello.pdf");

echo $pdf->get_buffer();


?>