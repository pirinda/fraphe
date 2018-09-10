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
use Fraphe\Model\FModelUtils;
use Fraphe\Model\FRegistry;
use app\models\ModConsts;
use app\models\operations\ModReport;

/* ?id={id of reception}&move={next|back}
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get recept to process:
    $report;
    $userSession = FGuiUtils::createUserSession();
    if (!empty($_GET[FRegistry::ID])) {
        $report = new ModReport();
        $report->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
    }

    $process = false;
    if (isset($report)) {
        switch ($_GET["move"]) {
            case "next":
                switch ($report->getDatum("fk_report_status")) {
                    case ModConsts::OC_REPORT_STATUS_PENDING:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_PROCESSING;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_PROCESSING:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_FINISHED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_FINISHED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_VERIFIED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_VERIFIED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_VALIDATED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_VALIDATED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_RELEASED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_RELEASED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_DELIVERED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_DELIVERED:
                        break;

                    case ModConsts::OC_REPORT_STATUS_CANCELLED:
                        break;

                    default:
                }
                break;

            case "back":
                switch ($report->getDatum("fk_report_status")) {
                    case ModConsts::OC_REPORT_STATUS_PENDING:
                        break;

                    case ModConsts::OC_REPORT_STATUS_PROCESSING:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_PENDING;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_FINISHED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_PROCESSING;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_VERIFIED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_FINISHED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_VALIDATED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_VERIFIED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_RELEASED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_VALIDATED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_DELIVERED:
                        $data = array();
                        $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_RELEASED;
                        $report->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_REPORT_STATUS_CANCELLED:
                        break;

                    default:
                }
                break;

            default:
        }

        if ($process) {
            FModelUtils::save($userSession, $report);
        }
    }
}

// redirect:
header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_report.php");
// eof
