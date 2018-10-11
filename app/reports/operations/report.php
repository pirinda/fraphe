<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";
//------------------------------------------------------------------------------

use Fraphe\App\FAppConsts;
use Fraphe\App\FGuiUtils;
use Fraphe\Lib\FFiles;
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\catalogs\ModContact;
use app\models\catalogs\ModEntity;
use app\models\catalogs\ModUser;
use app\models\operations\ModReport;
use app\models\operations\ModSample;
use app\models\operations\ModTest;

require_once $_SESSION[FAppConsts::ROOT_DIR] . "app/reports/app_tcpdf_include.php";

//------------------------------------------------------------------------------
// extend class TCPDF
//------------------------------------------------------------------------------
// Letter page format maximum width: 8.5 in * 254 mm = 195.9 mm >>> 196 mm
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) // uses MultiCell()
*/

class AppPDF extends TCPDF {

    private $issueDate;

    function setIssueDate(int $issueDate) {
        $this->issueDate = $issueDate;
    }

	//Page header
	public function Header() {
		$image_file = K_PATH_IMAGES . 'report_header.jpg';
		$this->Image($image_file, 10, 10, 196, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);
	}

	// Page footer
	public function Footer() {
		$this->SetY(-25); // position at 25 mm from bottom

        $image_file = K_PATH_IMAGES . 'report_footer.jpg';
        $this->Image($image_file, 10, '', 196, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);

        $this->SetY(-30); // position at 30 mm from bottom

        $this->SetFont('helvetica', '', 7);

        // control number of document
        $txt = "F1-P1IR R0";
        $this->MultiCell(42.95, 0, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'B', false);
        /*
        $txt = "FECHA DE EMISIÓN: " . FLibUtils::formatLocDate($this->issueDate);
        $this->MultiCell(50, 0, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'B', false);
        */

        $txt = "CENTRO DE DIAGNÓSTICO MICROBIOLÓGICO SA DE CV";
        $this->MultiCell(100, 0, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'B', false);

		// page number
        $txt = 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages();
        $this->MultiCell(42.95, 0, $txt, 0, 'R', false, 0, '', '', true, 0, false, true, 0, 'B', false);
	}
}

//------------------------------------------------------------------------------
// read report
//------------------------------------------------------------------------------
$userSession = FGuiUtils::createUserSession();
$report = new ModReport();
$report->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);


//------------------------------------------------------------------------------
// create new PDF document
//------------------------------------------------------------------------------
$pdf = new AppPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setIssueDate($report->getDatum("ts_user_valid"));

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle(PDF_HEADER_TITLE);
$pdf->SetSubject("División Alimentos y Agua");
$pdf->SetKeywords("CEDIMI, informe, resultados");

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//------------------------------------------------------------------------------


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
// report
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
/*
Title: helvetica, 'B', 12
Subtitle: helvetica, 'B', 9
Text: helvetica, '', 8
*/

$hNl = 2; // new line
$hTxt09 = 3; // height for text of font of size 9
$hTit09 = 4; // height for title of font of size 9
$hTit12 = 5; // height for title of font of size 12

$sample = new ModSample();
$sample->read($userSession, $report->getDatum("fk_sample"), FRegistry::MODE_READ);

////////////////////////////////////////////////////////////////////////////////
// Sampling Report, page 1
////////////////////////////////////////////////////////////////////////////////
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) // uses MultiCell()
*/

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 12);

$txt = "INFORME DE RESULTADOS";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

$pdf->Ln($hTit12);

//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', 'B', 9);

$txt = "<b>Folio interno: </b>" . $sample->getDatum("sample_num");
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>Fecha emisión: </b>" . FLibUtils::formatLocDate($report->getDatum("ts_user_valid"));
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 1, false, 0, 'R', true);

//------------------------------------------------------------------------------

$pdf->Ln($hNl);

//------------------------------------------------------------------------------
// customer
//------------------------------------------------------------------------------
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) // uses MultiCell()
*/

//$pdf->SetFillColor(180, 180, 180);
$pdf->SetFillColor(127, 255, 212); // aquamarine

$pdf->SetFont('helvetica', 'B', 9);

$txt = "DATOS DEL CLIENTE";
$pdf->MultiCell(0, $hTit09, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'C', false);

//------------------------------------------------------------------------------

$customer = new ModEntity();
$customer->read($userSession, $report->getDatum("fk_customer"), FRegistry::MODE_READ);

$contact = new ModContact();
$contact->read($userSession, $sample->getDatum("fk_report_contact"), FRegistry::MODE_READ);

$txtCustomer;
$txtAddress;
$txtContact;

if ($sample->getDatum("is_customer_custom")) {
    if (!empty($sample->getDatum("customer_name"))) {
        $txtCustomer = $sample->getDatum("customer_name");
    }
    else {
        $txtCustomer = $customer->getDatum("name");
    }

    $txtAddress = $sample->composeCustomerAddress();

    if (!empty($sample->getDatum("customer_contact"))) {
        $txtContact = $sample->getDatum("customer_contact");
    }
    else {
        $txtContact = $contact->composeContact();
    }
}
else {
    $txtCustomer = $customer->getDatum("name");
    $txtAddress = $customer->getChildEntityAddresses()[0]->composeAddress();
    $txtContact = $contact->composeContact();
}

$pdf->SetFont('helvetica', '', 8);

$txt = "<b>Nombre: </b>" . $txtCustomer;
$pdf->writeHTMLCell(0, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>Dirección: </b>" . $txtAddress;
$pdf->writeHTMLCell(0, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>Contacto: </b>" . $txtContact;
$pdf->writeHTMLCell(0, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

$pdf->Ln($hNl);

//------------------------------------------------------------------------------
// sample
//------------------------------------------------------------------------------
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)
*/

$pdf->SetFont('helvetica', 'B', 9);

$txt = "INFORMACIÓN DE LA MUESTRA";
$pdf->MultiCell(0, $hTit09, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 8);

$txt = "<b>Identificación: </b>" . $sample->getDatum("sample_name");
$pdf->writeHTMLCell(0, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

$txt = "<b>Fecha producción: </b>" . (empty($sample->getDatum("sample_date_mfg_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("sample_date_mfg_n")));
$pdf->writeHTMLCell(70, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>Fecha caducidad: </b>" . (empty($sample->getDatum("sample_date_sell_by_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("sample_date_sell_by_n")));
$pdf->writeHTMLCell(70, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>Lote: </b>" . (empty($sample->getDatum("sample_lot")) ? "NE" : $sample->getDatum("sample_lot"));
$pdf->writeHTMLCell(45.9, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

$pdf->Ln($hNl);

//------------------------------------------------------------------------------
// sampling
//------------------------------------------------------------------------------
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)
*/

$pdf->SetFont('helvetica', 'B', 9);

$txt = "INFORMACIÓN DEL MUESTREO";
$pdf->MultiCell(0, $hTit09, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 8);

$txt = "<b>Fecha muestreo: </b>" . (empty($sample->getDatum("sampling_datetime_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("sampling_datetime_n")));
$pdf->writeHTMLCell(60, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>T° muestreo: </b>" . ($sample->getDatum("sampling_temperat_n") == null ? "NE" : $sample->getDatum("sampling_temperat_n") . " °C");
$pdf->writeHTMLCell(40, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

if ($sample->getDatum("is_sampling_company")) {
    $txt = "<b>Guía muestreo: </b>" . (empty($sample->getDatum("sampling_guide")) ? "NE" : $sample->getDatum("sampling_guide"));
}
else {
    $txt = "<b>Guía muestreo: </b>NA";
}
$pdf->writeHTMLCell(40, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>Folio cadena: </b>" . (empty($sample->getDatum("ref_chain_custody")) ? "NE" : $sample->getDatum("ref_chain_custody"));
$pdf->writeHTMLCell(45.9, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

$name = AppUtils::readField($userSession, "name", AppConsts::OC_SAMPLING_METHOD, $sample->getDatum("fk_sampling_method"));
$txt = "<b>Método muestreo: </b>" . (empty($name) ? "NE" : $name);
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

if ($sample->getDatum("is_sampling_company")) {
    $name = AppUtils::readField($userSession, "name", AppConsts::CC_USER, $sample->getDatum("fk_user_sampler"));
    $txt = "<b>Muestra tomada por: </b>" . (empty($name) ? "NE" : $name);
}
else {
    $txt = "<b>Muestra tomada por: </b>El cliente";
}
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

if ($sample->getDatum("is_sampling_company")) {
    $name = "";
    for ($i = 1; $i <= 3; $i++) {
        if (!empty($sample->getDatum("nk_sampling_equipt_$i"))) {
            $name .= (empty($name) ? "" : ", ") . AppUtils::readField($userSession, "name", AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt_$i"));
        }
    }

    $txt = "<b>Equipos utilizados: </b>" . (empty($name) ? "NE" : $name);
}
else {
    $txt = "<b>Equipos utilizados: </b>NA";
}
$pdf->writeHTMLCell(0, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

$txt = "<b>Condiciones de toma: </b>" . (empty($sample->getDatum("sampling_conditions")) ? "NE" : $sample->getDatum("sampling_conditions"));
$pdf->writeHTMLCell(0, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>Observaciones: </b>" . (empty($report->getDatum("process_notes")) ? "NA" : $report->getDatum("process_notes"));
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>Desviaciones: </b>" . (empty($report->getDatum("process_deviations")) ? "NA" : $report->getDatum("process_deviations"));
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>Lugar de toma: </b>" . (empty($sample->getDatum("sampling_area")) ? "NE" : $sample->getDatum("sampling_area"));
$pdf->writeHTMLCell(0, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

$pdf->Ln($hNl);

$images = count($sample->getChildSamplingImages());
if ($images > 0) {
    foreach ($sample->getChildSamplingImages() as $samplingImage) {
        $image = '../../images/sample/' . $samplingImage->getDatum("sampling_img");
        $pdf->Image($image, 50, '', 90, 60, 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);
        if (--$images > 0) {
            $pdf->Ln(65);
        }
    }
}
else if ($sample->getDatum("is_def_sampling_img")) {
    $image = '../../images/entity/' . FFiles::createFileNameForId(ModEntity::PREFIX, 6, $customer->getId(), 1, "jpg");
    $pdf->Image($image, 50, '', 100, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);
}

////////////////////////////////////////////////////////////////////////////////
// Results Report, page 2, ...
////////////////////////////////////////////////////////////////////////////////
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) // uses MultiCell()
*/

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 9);

$txt = "<b>Folio interno: </b>" . $sample->getDatum("sample_num");
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>Fecha emisión: </b>" . FLibUtils::formatLocDate($report->getDatum("ts_user_valid"));
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 1, false, 0, 'R', true);

//------------------------------------------------------------------------------

$pdf->Ln($hNl);

//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', 'B', 9);

$txt = "INFORMACIÓN DE RECEPCIÓN DE LA MUESTRA";
$pdf->MultiCell(0, $hTit09, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 8);

$txt = "<b>Fecha recepción: </b>" . (empty($sample->getDatum("recept_datetime_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("recept_datetime_n")));
$pdf->writeHTMLCell(45, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>T° recepción: </b>" . (empty($sample->getDatum("recept_temperat_n")) ? "NE" : $sample->getDatum("recept_temperat_n") . " °C");
$pdf->writeHTMLCell(30, $hTxt09, '', '', $txt, 0, 0, false, 0, 'C', true); // centered

$name = AppUtils::readField($userSession, "name", AppConsts::OC_CONTAINER_TYPE, $sample->getDatum("fk_container_type"));
$txt = "<b>Presentación: </b>" . $name;
$pdf->writeHTMLCell(65, $hTxt09, '', '', $txt, 0, 0, false, 0, 'C', true); // centered

$code = AppUtils::readField($userSession, "code", AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit"));
$txt = "<b>Cantidad recibida: </b>" . $sample->getDatum("sample_quantity") . " " . $code;
$pdf->writeHTMLCell(45.9, $hTxt09, '', '', $txt, 0, 1, false, 0, 'C', true); // centered

//------------------------------------------------------------------------------

$txt = "<b>Observaciones: </b>" . (empty($sample->getDatum("recept_notes")) ? "NA" : $sample->getDatum("recept_notes"));
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 0, false, 0, '', true);

$txt = "<b>Desviaciones: </b>" . (empty($sample->getDatum("recept_deviations")) ? "NA" : $sample->getDatum("recept_deviations"));
$pdf->writeHTMLCell(92.95, $hTxt09, '', '', $txt, 0, 1, false, 0, '', true);

//------------------------------------------------------------------------------

$pdf->Ln($hNl);

//------------------------------------------------------------------------------
// results
//------------------------------------------------------------------------------
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) // uses MultiCell()
*/

$pdf->SetFont('helvetica', 'B', 9);

$txt = "RESULTADOS DEL ANÁLISIS";
$pdf->MultiCell(0, $hTit09, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------

$pdf->Ln($hNl);

$pdf->SetFont('helvetica', 'B', 8);

$txt = "A/A";
$pdf->MultiCell(10, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "INICIO/FIN";
$pdf->MultiCell(20, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "ENSAYO";
$pdf->MultiCell(55, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "RESULTADO";
$pdf->MultiCell(40, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "U";
$pdf->MultiCell(10, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "LÍMITES PERMISIBLES";
$pdf->MultiCell(51, $hTit09, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------

$txt = "";
$pdf->MultiCell(10, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "ANÁLISIS";
$pdf->MultiCell(20, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "MÉTODO ANALÍTICO";
$pdf->MultiCell(55, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "";
$pdf->MultiCell(40, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "";
$pdf->MultiCell(10, $hTit09, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = AppUtils::readField($userSession, "name", AppConsts::OC_RESULT_PERMISS_LIMIT, $report->getDatum("fk_result_permiss_limit"));
$pdf->MultiCell(51, $hTit09, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) // uses MultiCell()
*/

$pdf->SetFont('helvetica', '', 7);
$pdf->SetFillColor(220, 220, 220); // gainsboro
$row = 0;

foreach ($report->getChildReportTests() as $reportTest) {

    $test = new ModTest();
    $test->read($userSession, $reportTest->getDatum("fk_test"), FRegistry::MODE_READ);
    $fill = ++$row % 2 === 0;

    $txt = AppUtils::readField($userSession, "code", AppConsts::OC_TEST_ACREDIT_ATTRIB, $test->getDatum("fk_test_acredit_attrib"));
    $pdf->MultiCell(10, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = empty($sample->getDatum("process_start_date")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("process_start_date"));
    $pdf->MultiCell(20, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = $test->getDatum("name");
    $pdf->MultiCell(55, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = $reportTest->getDatum("result") . (empty($reportTest->getDatum("nk_result_unit")) ? "" : AppUtils::readField($userSession, "code", AppConsts::OC_RESULT_UNIT, $reportTest->getDatum("nk_result_unit")));
    $pdf->MultiCell(40, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = $reportTest->getDatum("uncertainty");
    $pdf->MultiCell(10, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = $reportTest->getDatum("permiss_limit");
    $pdf->MultiCell(50.9, $hTxt09, $txt, 0, 'C', $fill, 1, '', '', true, 0, false, true, 0, 'T', false);

    //------------------------------------------------------------------------------

    $txt = '';
    $pdf->MultiCell(10, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = empty($sample->getDatum("process_deadline")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("process_deadline"));
    $pdf->MultiCell(20, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = AppUtils::readField($userSession, "name", AppConsts::OC_TESTING_METHOD, $test->getDatum("fk_testing_method"));
    $pdf->MultiCell(55, $hTxt09, $txt, 0, 'C', $fill, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = '';
    $pdf->MultiCell(40 + 10 + 50.9, $hTxt09, $txt, 0, 'C', $fill, 1, '', '', true, 0, false, true, 0, 'T', false);

    $pdf->Ln(1);
}

//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 8);

$notes = $report->getDatum("process_notes");

$txt = "<b>Observaciones: </b>" . (empty($notes) ? "NA" : $notes);
$pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

if (!empty($report->getDatum("process_deviations"))) {
    $txt = "<b>Desviaciones: </b>" . $report->getDatum("process_deviations");
    $pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);
}

//------------------------------------------------------------------------------
/*
function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='')
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) // uses MultiCell()
*/

$pdf->Ln($hNl);

$pdf->SetFont('helvetica', 'B', 6);

$txt = "NOTA 1: El presente informe se refiere exclusivamente a la muestra sometida a prueba, y no debe ser reproducido parcialmente sin autorización por escrito del laboratorio. La información aquí contenida es de caracter confidencial de \"CEDIMI-cliente\" por lo que obedece a los términos legales que a ello apliquen.";
$pdf->MultiCell(0, $hTxt09, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'B', false);

$txt = "NOTA 2: Los análisis antes enunciados fueron realizados en las instalaciones permanentes del laboratorio, (dirección enunciada en el membrete de la hoja), excepto aquellos ensayos identificados como \"contratados\" (A/A 3), estos fueron ensayados en les instalaciones del laboratorio contratado.";
$pdf->MultiCell(0, $hTxt09, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------
$pdf->Ln(1);

$pdf->SetFont('helvetica', '', 6);

$txt = "(1) Ensayos Acreditados por la Entidad Mexicana de Acreditación";
$pdf->MultiCell(80, $hTxt09, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(2) Ensayos Autorizados por COFEPRIS (Secretaría de Salud).";
$pdf->MultiCell(105.9, $hTxt09, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(3) Ensayos contratados";
$pdf->MultiCell(80, $hTxt09, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(4) Ensayos ausentes de Acreditación/Autorización, con métodos basados en Normas Oficiales.";
$pdf->MultiCell(105.9, $hTxt09, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

//------------------------------------------------------------------------------

$txt = "A/A = Acreditado/Autorizado";
$pdf->MultiCell(40, $hTxt09, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "U = Incertidumbre";
$pdf->MultiCell(30, $hTxt09, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "T° = Temperatura";
$pdf->MultiCell(30, $hTxt09, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "NA = No aplica";
$pdf->MultiCell(30, $hTxt09, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "NE = No especificado";
$pdf->MultiCell(30, $hTxt09, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 8);

//$signImage = dirname(__FILE__).'/../images/user/' . FFiles::createFileNameForId("user_", 6, $report->getDatum("fk_user_valid"), 0, "jpg");
$signImage = '../../images/user/' . FFiles::createFileNameForId(ModUser::PREFIX, 6, $report->getDatum("fk_user_valid"), 0, "jpg");
if (!file_exists($signImage)) {
    //$signImage = dirname(__FILE__).'/../images/user/' . FFiles::createFileNameForId("user_", 6, 0, 0, "jpg"); // default image
    $signImage = '../../images/user/' . FFiles::createFileNameForId(ModUser::PREFIX, 6, 0, 0, "jpg"); // default image
}

//public function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array()) {
$pdf->Image($signImage, 100, '', 96, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);

//public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)

$user = new ModUser();
$user->read($userSession, $report->getDatum("fk_user_valid"), FRegistry::MODE_READ);

$pdf->Ln(20);

$txt = $user->getDatum("name");
$pdf->MultiCell(100, 3, $txt, 0, 'C', false, 1, 100, '', true, 0, false, true, 0, 'B', false);
$txt = empty($user->getDatum("nk_user_job")) ? "ND" : AppUtils::readField($userSession, "name", AppConsts::CC_USER_JOB, $user->getDatum("nk_user_job"));
$pdf->MultiCell(100, 3, $txt, 0, 'C', false, 1, 100, '', true, 0, false, true, 0, 'B', false);
$txt = empty($user->getDatum("nk_user_job")) ? "" : "SIGNATARIO AUTORIZADO";
$pdf->MultiCell(100, 3, $txt, 0, 'C', false, 1, 100, '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------
// end
//------------------------------------------------------------------------------

// close and output PDF document
$pdf->Output($sample->getDatum("sample_num") . ".pdf", "I");

//============================================================+
// END OF FILE
//============================================================+
