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
use Fraphe\Lib\FLibUtils;
use Fraphe\Model\FRegistry;
use app\AppConsts;
use app\AppUtils;
use app\models\catalogs\ModContact;
use app\models\catalogs\ModEntity;
use app\models\operations\ModReport;
use app\models\operations\ModSample;
use app\models\operations\ModTest;

require_once $_SESSION[FAppConsts::ROOT_DIR] . "app/reports/app_tcpdf_include.php";

class AppPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES . 'cedimi_header.jpg';
		$this->Image($image_file, 10, 10, 196, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-20);
		// Set font
        $image_file = K_PATH_IMAGES . 'cedimi_footer.jpg';
        $this->Image($image_file, 10, '', 196, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);

		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new AppPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
//------------------------------------------------------------------------------
$userSession = FGuiUtils::createUserSession();
$report = new ModReport();
$report->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);


// add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);

$txt = "INFORME DE RESULTADOS";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

$pdf->Ln(5);


//------------------------------------------------------------------------------
// Customer:
//------------------------------------------------------------------------------


$pdf->SetFillColor(180, 180, 180);

$pdf->SetFont('helvetica', 'B', 10);
$txt = "DATOS DEL CLIENTE";
$pdf->MultiCell(0, 5, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 9);

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
$pdf->writeHTMLCell(0, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>DIRECCIÓN: </b>" . $txtAddress;
$pdf->writeHTMLCell(0, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>INFORMACIÓN DEL CONTACTO: </b>" . $txtContact;
$pdf->writeHTMLCell(0, 5, '', '', $txt, 0, 1, false, 0, '', true);

$pdf->Ln(5);


//------------------------------------------------------------------------------
// Sample:
//------------------------------------------------------------------------------

$y = $pdf->getY();

$pdf->SetFont('helvetica', 'B', 10);
$txt = "DATOS DE LA MUESTRA";
$pdf->MultiCell(90, 5, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 9);

$txt = "<b>FOLIO INTERNO: </b>" . $sample->getDatum("sample_num");
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$name = AppUtils::readField($userSession, "name", AppConsts::OC_CONTAINER_TYPE, $sample->getDatum("fk_container_type"));
$txt = "<b>PRESENTACIÓN: </b>" . $name;
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$code = AppUtils::readField($userSession, "code", AppConsts::OC_CONTAINER_UNIT, $sample->getDatum("fk_container_unit"));
$txt = "<b>CANTIDAD RECIBIDA: </b>" . $sample->getDatum("sample_quantity") . " " . $code;
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DE PRODUCCIÓN: </b>" . (empty($sample->getDatum("sample_date_mfg_n")) ? "NE" : FLibUtils::formatStdDate($sample->getDatum("sample_date_mfg_n")));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>CADUCIDAD: </b>" . (empty($sample->getDatum("sample_date_sell_by_n")) ? "NE" : FLibUtils::formatStdDate($sample->getDatum("sample_date_sell_by_n")));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>LOTE: </b>" . (empty($sample->getDatum("sample_lot")) ? "NE" : $sample->getDatum("sample_lot"));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>T° DE RECEPCIÓN: </b>" . (empty($sample->getDatum("recept_temperat_n")) ? "NE" : $sample->getDatum("recept_temperat_n") . "°");
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA RECEPCIÓN: </b>" . (empty($sample->getDatum("recept_datetime_n")) ? "NE" : FLibUtils::formatStdDate($sample->getDatum("recept_datetime_n")));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DE ANÁLISIS: </b>" . (empty($sample->getDatum("process_start_date")) ? "NE" : FLibUtils::formatStdDate($sample->getDatum("process_start_date")));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>TÉRMINO DEL ANÁLISIS: </b>" . (empty($sample->getDatum("process_deadline")) ? "NE" : FLibUtils::formatStdDate($sample->getDatum("process_deadline")));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>OBSERVACIONES: </b>" . (empty($sample->getDatum("recept_notes")) ? "NE" : $sample->getDatum("recept_notes"));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>DESVIACIONES: </b>" . (empty($sample->getDatum("recept_deviations")) ? "NE" : $sample->getDatum("recept_deviations"));
$pdf->writeHTMLCell(90, 5, '', '', $txt, 0, 1, false, 0, '', true);

$y2 = $pdf->getY();

$pdf->SetFont('helvetica', 'B', 10);

$txt = "DATOS DEL MUESTREO";
$pdf->MultiCell(90, 5, $txt, 0, 'C', true, 1, 111, $y, true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 9);

$txt = "<b>FOLIO CADENA: </b>" . (empty($sample->getDatum("ref_chain_custody")) ? "NE" : $sample->getDatum("ref_chain_custody"));
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>FECHA DEL MUESTREO: </b>" . (empty($sample->getDatum("sampling_datetime_n")) ? "NE" : FLibUtils::formatStdDate($sample->getDatum("sampling_datetime_n")));
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$name = AppUtils::readField($userSession, "name", AppConsts::CC_USER, $sample->getDatum("fk_user_sampler"));
$txt = "<b>MUESTREADOR: </b>" . (empty($name) ? "NE" : $name);
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>LUGAR DE LA TOMA: </b>" . (empty($sample->getDatum("sampling_area")) ? "NE" : $sample->getDatum("sampling_area"));
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$name = AppUtils::readField($userSession, "name", AppConsts::OC_SAMPLING_METHOD, $sample->getDatum("fk_sampling_method"));
$txt = "<b>MÉTODO DE MUESTREO: </b>" . (empty($name) ? "NE" : $name);
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>N° DE GUÍA: </b>" . (empty($sample->getDatum("sampling_guide")) ? "NE" : $sample->getDatum("sampling_guide"));
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$name = "";
for ($i = 1; $i <= 3; $i++) {
    if (!empty($sample->getDatum("nk_sampling_equipt_$i"))) {
        $name .= (empty($name) ? "" : ", ") . AppUtils::readField($userSession, "name", AppConsts::OC_SAMPLING_EQUIPT, $sample->getDatum("nk_sampling_equipt_$i"));
    }
}

$txt = "<b>EQUIPOS UTILIZADOS: </b>" . (empty($name) ? "NE" : $name);
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>CONDICIONES DE TOMA: </b>" . (empty($sample->getDatum("sampling_conditions")) ? "NE" : $sample->getDatum("sampling_conditions"));
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$pdf->Ln(5);

$txt = "<b>OBSERVACIONES: </b>" . (empty($report->getDatum("process_notes")) ? "NE" : $report->getDatum("process_notes"));
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);

$txt = "<b>DESVIACIONES: </b>" . (empty($report->getDatum("process_deviations")) ? "NE" : $report->getDatum("process_deviations"));
$pdf->writeHTMLCell(90, 5, 111, '', $txt, 0, 1, false, 0, '', true);


//------------------------------------------------------------------------------
// Sample:
//------------------------------------------------------------------------------

$pdf->SetFont('helvetica', 'B', 10);

$txt = "IDENTIFICACIÓN DE LA MUESTRA";
$pdf->MultiCell(0, 5, $txt, 0, 'C', true, 1, '', $y2 + 5, true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 9);

$txt = $sample->getDatum("sample_name");
$pdf->MultiCell(0, 5, $txt, 0, 'C', false, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', 'B', 10);

$txt = "A/A";
$pdf->MultiCell(10, 5, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "ENSAYO";
$pdf->MultiCell(70, 5, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "RESULTADO";
$pdf->MultiCell(40, 5, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "U";
$pdf->MultiCell(10, 5, $txt, 0, 'C', true, 0, '', '', true, 0, false, true, 0, 'B', false);

$txt = "LÍMITES PERMISIBLES";
$pdf->MultiCell(56, 5, $txt, 0, 'C', true, 1, '', '', true, 0, false, true, 0, 'B', false);

$pdf->SetFont('helvetica', '', 9);

foreach ($report->getChildReportTests() as $reportTest) {
    $test = new ModTest();
    $test->read($userSession, $reportTest->getDatum("fk_test"), FRegistry::MODE_READ);
    $aa = AppUtils::readField($userSession, "code", AppConsts::OC_TEST_ACREDIT_ATTRIB, $test->getDatum("fk_test_acredit_attrib"));

    $txt = $aa;
    $pdf->MultiCell(10, 5, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'B', false);

    $txt = $test->getDatum("name");
    $pdf->MultiCell(70, 5, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'B', false);

    $txt = $reportTest->getDatum("result");
    $pdf->MultiCell(40, 5, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'B', false);

    $txt = $reportTest->getDatum("uncertainty");
    $pdf->MultiCell(10, 5, $txt, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'B', false);

    $txt = $reportTest->getDatum("permiss_limit");
    $pdf->MultiCell(56, 5, $txt, 0, 'C', false, 1, '', '', true, 0, false, true, 0, 'B', false);
}

$pdf->Ln(5);

$txt = "<b>OBSERVACIONES DEL ANÁLISIS: </b>" . $report->getDatum("process_notes");
$pdf->writeHTMLCell(0, 5, '', '', $txt, 0, 1, false, 0, '', true);

// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)

$txt = "SIMBOLOGÍA:";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$pdf->SetFont('helvetica', '', 8);

$txt = "(1) Ensayos Acreditados por la Entidad Mexicana de Acreditación";
$pdf->MultiCell(80, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(2) Ensayos Autorizados por COFEPRIS (Secretaría de Salud).";
$pdf->MultiCell(80, 5, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);


$txt = "";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(3) Ensayos contratados";
$pdf->MultiCell(80, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "(4) Ensayos ausentes de Acreditación/ Autorización, con métodos basados en Normas Oficiales.";
$pdf->MultiCell(80, 5, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);


$txt = "U = Incertidumbre";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "NA = no aplica";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "U = No especificado";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "T° = temperatura";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "N° = Número";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T', false);

$txt = "A/A = Acreditado Autorizado";
$pdf->MultiCell(30, 5, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

$pdf->SetFont('helvetica', 'B', 9);

$txt = "NOTA 1: El presente informe se refiere exclusivamente a la muestra sometidaa prueba y no debe ser reproducido parcialmente in autorización por escrito del laboratorio. La información aquí contanida es de caracter confidencial de \"CEDIMI-Cliente\" por lo que obedece a los términos legales que a ello apliquen.";
$pdf->MultiCell(0, 5, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'B', false);

$txt = "NOTA 2: Los análisis antes enunciados fueron realizados en las instalaciones permanentes del Laboratorio, (dirección enunciada en el membrete de la hoja), excepto aquellos ensayos identificados como \"contratados\" (A/A 3), estos fueron ensayados en les instalaciones del laboratorio contratado.";
$pdf->MultiCell(0, 5, $txt, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'B', false);

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
