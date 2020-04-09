<?php require_once "vendor/autoload.php";

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$html2pdf = new Html2Pdf('P','A4','es',true,'UTF-8');

$html2pdf->writeHTML($html);
if ( isset($_GET['mode']) ) {
	
	if ( $_GET['mode'] == "saveas" ) {
		$html2pdf->output("$filename.pdf", 'D');
	} else if ( $_GET['mode'] == "storein" ) {
		$html2pdf->output("$basepath/html2pdf/$filename"."_$id.pdf", 'F');
	} else $html2pdf->output("$filename.pdf");
	
}
else $html2pdf->output("$filename.pdf");