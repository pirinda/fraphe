<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe/fraphe.php";
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

class AppPDF extends TCPDF {

    private $issueDate;

    function setIssueDate(int $issueDate) {
        $this->issueDate = $issueDate;
    }

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES . 'report_header.jpg';
		$this->Image($image_file, 10, 10, 196, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);
	}

	// Page footer
	public function Footer() {
		// Position at 25 mm from bottom
		$this->SetY(-25);
		// Set font
        $image_file = K_PATH_IMAGES . 'cedimi_footer.jpg';
        $this->Image($image_file, 10, '', 196, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);

		$this->SetFont('helvetica', 'I', 8);

        $this->SetY(-30);

        //public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false) {

        $txt = "F1-P1IR R0";
        $this->MultiCell(75, 0, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'B', false);

        $txt = "FECHA DE EMISIÓN: " . FLibUtils::formatLocDate($this->issueDate);
        $this->MultiCell(50, 0, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'B', false);

		// Page number
        $txt = 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
        $this->MultiCell(75, 0, $txt, 0, 'R', false, 0, '', '', true, 0, false, true, 0, 'B', false);
        //$this->Cell(0, 10, $txt, 0, false, 'C', 0, '', 0, false, 'T', 'T');
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
$pdf->SetSubject("Informe de resultados");
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

//------------------------------------------------------------------------------
// report
//------------------------------------------------------------------------------

// add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 12);

$txt = "INFORME DE RESULTADOS";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

$pdf->Ln(3);

//------------------------------------------------------------------------------
// customer
//------------------------------------------------------------------------------

$pdf->SetFillColor(180, 180, 180);

$pdf->SetFont('helvetica', 'B', 9);
$txt = "DATOS DEL CLIENTE";
$pdf->MultiCell(0, 4, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 8);

$sample = new ModSample();
$sample->read($userSession, $report->getDatum("fk_sample"), FRegistry::MODE_READ);

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

$txt = "<b>NOMBRE: </b>" . $txtCustomer;
$pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>DIRECCIÓN: </b>" . $txtAddress;
$pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>INFORMACIÓN DEL CONTACTO: </b>" . $txtContact;
$pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

$pdf->Ln(4);

//------------------------------------------------------------------------------
// Sample:
//------------------------------------------------------------------------------

$y1 = $pdf->getY();

$pdf->SetFont('helvetica', 'B', 9);
$txt = "DATOS DE LA MUESTRA";
$pdf->MultiCell(90, 4, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 8);

$txt = "<b>FOLIO INTERNO: </b>" . $sample->getDatum("sample_num");
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$name = AppUtils::readField($userSession, "name", AppConsts::OC_CONTAINER_TYPE, $sample->getDatum("fk_container_type"));
$txt = "<b>PRESENTACIÓN: </b>" . $name;
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$code = AppUtils::readField($userSession, "code", AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit"));
$txt = "<b>CANTIDAD RECIBIDA: </b>" . $sample->getDatum("sample_quantity") . " " . $code;
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DE PRODUCCIÓN: </b>" . (empty($sample->getDatum("sample_date_mfg_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("sample_date_mfg_n")));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DE CADUCIDAD: </b>" . (empty($sample->getDatum("sample_date_sell_by_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("sample_date_sell_by_n")));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>LOTE: </b>" . (empty($sample->getDatum("sample_lot")) ? "NE" : $sample->getDatum("sample_lot"));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>T° DE RECEPCIÓN: </b>" . (empty($sample->getDatum("recept_temperat_n")) ? "NE" : $sample->getDatum("recept_temperat_n") . " °C");
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DE RECEPCIÓN: </b>" . (empty($sample->getDatum("recept_datetime_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("recept_datetime_n")));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DE ANÁLISIS: </b>" . (empty($sample->getDatum("process_start_date")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("process_start_date")));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>TÉRMINO DEL ANÁLISIS: </b>" . (empty($sample->getDatum("process_deadline")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("process_deadline")));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$yNotes = $pdf->getY();

$txt = "<b>OBSERVACIONES: </b>" . (empty($sample->getDatum("recept_notes")) ? "NA" : $sample->getDatum("recept_notes"));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>DESVIACIONES: </b>" . (empty($sample->getDatum("recept_deviations")) ? "NA" : $sample->getDatum("recept_deviations"));
$pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

$y2 = $pdf->getY();

//------------------------------------------------------------------------------
// Sampling:
//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', 'B', 9);

$txt = "DATOS DEL MUESTREO";
$pdf->MultiCell(90, 4, $txt, 0, 'C', true, 1, 111, $y1, true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 8);

$txt = "<b>FOLIO CADENA: </b>" . (empty($sample->getDatum("ref_chain_custody")) ? "NE" : $sample->getDatum("ref_chain_custody"));
$pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DEL MUESTREO: </b>" . (empty($sample->getDatum("sampling_datetime_n")) ? "NE" : FLibUtils::formatLocDate($sample->getDatum("sampling_datetime_n")));
$pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

if ($sample->getDatum("is_sampling_company")) {
    $name = AppUtils::readField($userSession, "name", AppConsts::CC_USER, $sample->getDatum("fk_user_sampler"));
    $txt = "<b>QUÍMICO DE MUESTREO: </b>" . (empty($name) ? "NE" : $name);
}
else {
    $txt = "<b>QUÍMICO DE MUESTREO: </b>El cliente";
}
$pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>LUGAR DE LA TOMA: </b>" . (empty($sample->getDatum("sampling_area")) ? "NE" : $sample->getDatum("sampling_area"));
$pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

$name = AppUtils::readField($userSession, "name", AppConsts::OC_SAMPLING_METHOD, $sample->getDatum("fk_sampling_method"));
$txt = "<b>MÉTODO DE MUESTREO: </b>" . (empty($name) ? "NE" : $name);
$pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

if ($sample->getDatum("is_sampling_company")) {
    $txt = "<b>N° DE GUÍA: </b>" . (empty($sample->getDatum("sampling_guide")) ? "NE" : $sample->getDatum("sampling_guide"));
    $pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

    $name = "";
    for ($i = 1; $i <= 3; $i++) {
        if (!empty($sample->getDatum("nk_sampling_equipt_$i"))) {
            $name .= (empty($name) ? "" : ", ") . AppUtils::readField($userSession, "name", AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt_$i"));
        }
    }

    $txt = "<b>EQUIPOS UTILIZADOS: </b>" . (empty($name) ? "NE" : $name);
    $pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);
}
else {
    $txt = "<b>N° DE GUÍA: </b>NA";
    $pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

    $txt = "<b>EQUIPOS UTILIZADOS: </b>NA";
    $pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);
}

$txt = "<b>CONDICIONES DE TOMA: </b>" . (empty($sample->getDatum("sampling_conditions")) ? "NE" : $sample->getDatum("sampling_conditions"));
$pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>OBSERVACIONES: </b>" . (empty($report->getDatum("process_notes")) ? "NA" : $report->getDatum("process_notes"));
$pdf->writeHTMLCell(90, 4, 111, $yNotes, $txt, 0, 1, false, 0, '', true);

$txt = "<b>DESVIACIONES: </b>" . (empty($report->getDatum("process_deviations")) ? "NA" : $report->getDatum("process_deviations"));
$pdf->writeHTMLCell(90, 4, 111, '', $txt, 0, 1, false, 0, '', true);

if ($pdf->getY() > $y2) {
    $y2 = $pdf->getY();
}

//------------------------------------------------------------------------------
// Results:
//------------------------------------------------------------------------------

// LETTER PAGE FORMAT MAX WIDTH: 8.5 in * 254 mm = 195.9 mm >>> 196 mm

$pdf->SetFont('helvetica', 'B', 9);

$txt = $sample->getDatum("sample_name");
$pdf->MultiCell(0, 4, $txt, 0, 'C', true, 1, '', $y2 + 5, true, 0, false, true, 0, 'B', false);

$pdf->Ln(3);

$pdf->SetFont('helvetica', 'B', 9);

$txt = "A/A";
$pdf->MultiCell(10, 4, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "ENSAYO";
$pdf->MultiCell(70, 4, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "RESULTADO";
$pdf->MultiCell(40, 4, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "U";
$pdf->MultiCell(10, 4, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "LÍMITES PERMISIBLES";
$pdf->MultiCell(56, 4, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 8);

$txt = "";
$pdf->MultiCell(130, 4, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = AppUtils::readField($userSession, "name", AppConsts::OC_RESULT_PERMISS_LIMIT, $report->getDatum("fk_result_permiss_limit"));
$pdf->MultiCell(56, 4, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 8);

foreach ($report->getChildReportTests() as $reportTest) {
    $test = new ModTest();
    $test->read($userSession, $reportTest->getDatum("fk_test"), FRegistry::MODE_READ);

    //public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)

    $txt = AppUtils::readField($userSession, "code", AppConsts::OC_TEST_ACREDIT_ATTRIB, $test->getDatum("fk_test_acredit_attrib"));
    $pdf->MultiCell(10, 4, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = $test->getDatum("name");
    $pdf->MultiCell(70, 4, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'T', false);

    $yTest = $pdf->getY();

    $txt = $reportTest->getDatum("result");
    if (!empty($reportTest->getDatum("nk_result_unit"))) {
        $txt .= " " . AppUtils::readField($userSession, "code", AppConsts::OC_RESULT_UNIT, $reportTest->getDatum("nk_result_unit"));
    }
    $pdf->MultiCell(40, 4, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = $reportTest->getDatum("uncertainty");
    $pdf->MultiCell(10, 4, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'T', false);

    $txt = $reportTest->getDatum("permiss_limit");
    $pdf->MultiCell(56, 4, $txt, 0, 'C', false, 1, '', '', true, 0, false, true, 0, 'T', false);

    $txt = AppUtils::readField($userSession, "name", AppConsts::OC_TESTING_METHOD, $test->getDatum("fk_testing_method"));
    $pdf->MultiCell(70, 4, $txt, 0, 'C', false, 1, 25, $yTest + 4, true, 0, false, true, 0, 'T', false);

    $pdf->Ln(3);
}

//------------------------------------------------------------------------------

$notes = $report->getDatum("process_notes");
$annex = count($sample->getChildSamplingImages()) > 0 || $sample->getDatum("is_def_sampling_img");

if ($annex) {
    $notes .= (empty($notes) ? "" : " ") . "Se anexan imágenes del lugar de la toma de muestra.";
}

$txt = "<b>OBSERVACIONES DEL ANÁLISIS: </b>" . (empty($notes) ? "NA" : $notes);
$pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

if (!empty($report->getDatum("process_deviations"))) {
    $txt = "<b>DESVIACIONES DEL ANÁLISIS: </b>" . $report->getDatum("process_deviations");
    $pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);
}

//------------------------------------------------------------------------------

$pdf->Ln(3);

//public function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)
//public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)

$pdf->SetFont('helvetica', '', 7);

$txt = "SIMBOLOGÍA:";
$pdf->MultiCell(25, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$pdf->SetFont('helvetica', '', 6);

$txt = "(1) Ensayos Acreditados por la Entidad Mexicana de Acreditación";
$pdf->MultiCell(75, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(2) Ensayos Autorizados por COFEPRIS (Secretaría de Salud).";
$pdf->MultiCell(86, 3, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

$txt = "";
$pdf->MultiCell(25, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(3) Ensayos contratados";
$pdf->MultiCell(75, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(4) Ensayos ausentes de Acreditación/ Autorización, con métodos basados en Normas Oficiales.";
$pdf->MultiCell(86, 3, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

//------------------------------------------------------------------------------

$txt = "U = Incertidumbre";
$pdf->MultiCell(30, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "NA = No aplica";
$pdf->MultiCell(30, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "NE = No especificado";
$pdf->MultiCell(30, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "T° = Temperatura";
$pdf->MultiCell(30, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "N° = Número";
$pdf->MultiCell(30, 3, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "A/A = Acreditado Autorizado";
$pdf->MultiCell(30, 3, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

//------------------------------------------------------------------------------

$pdf->Ln(1);

$pdf->SetFont('helvetica', 'B', 6);

$txt = "NOTA 1: El presente informe se refiere exclusivamente a la muestra sometida a prueba, y no debe ser reproducido parcialmente sin autorización por escrito del laboratorio. La información aquí contanida es de caracter confidencial de \"CEDIMI-cliente\" por lo que obedece a los términos legales que a ello apliquen.";
$pdf->MultiCell(0, 3, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->Ln(1);

$txt = "NOTA 2: Los análisis antes enunciados fueron realizados en las instalaciones permanentes del Laboratorio, (dirección enunciada en el membrete de la hoja), excepto aquellos ensayos identificados como \"contratados\" (A/A 3), estos fueron ensayados en les instalaciones del laboratorio contratado.";
$pdf->MultiCell(0, 3, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'B', false);

//------------------------------------------------------------------------------

$pdf->Ln(3);

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
// annex
//------------------------------------------------------------------------------

if ($annex) {
    // add a page
    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 12);

    $txt = "ANEXO AL INFORME DE RESULTADOS";
    $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

    $pdf->Ln(3);

    $pdf->SetFont('helvetica', 'B', 9);
    $txt = "DATOS DEL CLIENTE";
    $pdf->MultiCell(0, 4, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

    $pdf->SetFont('helvetica', '', 8);

    $txt = "<b>NOMBRE: </b>" . $txtCustomer;
    $pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

    $txt = "<b>DIRECCIÓN: </b>" . $txtAddress;
    $pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

    $txt = "<b>INFORMACIÓN DEL CONTACTO: </b>" . $txtContact;
    $pdf->writeHTMLCell(0, 4, '', '', $txt, 0, 1, false, 0, '', true);

    $pdf->Ln(4);

    $txt = "<b>FOLIO INTERNO: </b>" . $sample->getDatum("sample_num");
    $pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

    $txt = "<b>IDENTIFICACIÓN DE LA MUESTRA: </b>" . $sample->getDatum("sample_name");
    $pdf->writeHTMLCell(90, 4, '', '', $txt, 0, 1, false, 0, '', true);

    $pdf->Ln(4);

    //public function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array()) {
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
}




//Close and output PDF document
$pdf->Output($sample->getDatum("sample_num") . ".pdf", "I");


//============================================================+
// END OF FILE
//============================================================+
