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
use app\models\operations\ModJob;
use app\models\operations\ModJobTest;
use app\models\operations\ModRecept;
use app\models\operations\ModReport;
use app\models\operations\ModReportTest;

/* ?id={id of reception}&move={next|back}
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get recept to process:
    $recept;
    $userSession = FGuiUtils::createUserSession();
    if (!empty($_GET[FRegistry::ID])) {
        $recept = new ModRecept();
        $recept->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
    }

    $process = false;
    $createJobs = false;
    if (isset($recept)) {
        switch ($_GET["move"]) {
            case "next":
                switch ($recept->getDatum("fk_recept_status")) {
                    case ModConsts::OC_RECEPT_STATUS_NEW:
                        $data = array();
                        $data["fk_recept_status"] = ModConsts::OC_RECEPT_STATUS_PROCESSING;
                        $recept->setData($data);
                        $process = true;
                        $createJobs = true;
                        break;

                    case ModConsts::OC_RECEPT_STATUS_PROCESSING:
                        break;

                    case ModConsts::OC_RECEPT_STATUS_CANCELLED:
                        break;

                    default:
                }
                break;

            case "back":
                switch ($recept->getDatum("fk_recept_status")) {
                    case ModConsts::OC_RECEPT_STATUS_NEW:
                        break;

                    case ModConsts::OC_RECEPT_STATUS_PROCESSING:
                        $data = array();
                        $data["fk_recept_status"] = ModConsts::OC_RECEPT_STATUS_NEW;
                        $recept->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_RECEPT_STATUS_CANCELLED:
                        break;

                    default:
                }
                break;

            default:
        }

        if ($process) {
            if ($createJobs) {
                foreach ($recept->getChildSamples() as $sample) {
                    $sample->setParentRecept($recept);

                    $jobs = array();

                    foreach ($sample->getChildSampleTests() as $sampleTest) {
                        $job;
                        $data;
                        $fkProcessArea = $sampleTest->getDatum("fk_process_area");

                        // get or create job:

                        if (array_key_exists(strval($fkProcessArea), $jobs)) {
                            $job = $jobs[strval($fkProcessArea)];
                        }
                        else {
                            $data = array();
                            $data["job_num"] = $sample->getDatum("sample_num");
                            $data["job_date"] = $recept->getDatum("recept_datetime");
                            //$data["process_days"] = ?;
                            $data["process_start_date"] = $sample->getDatum("process_start_date");
                            //$data["process_deadline"] = ?;
                            //$data["is_system"] = ?;
                            //$data["is_deleted"] = ?;
                            $data["fk_company_branch"] = $sample->getDatum("fk_company_branch");
                            $data["fk_process_area"] = $fkProcessArea;
                            $data["fk_sample"] = $sample->getId();
                            $data["fk_recept"] = $recept->getId();
                            $data["fk_job_status"] = ModConsts::OC_JOB_STATUS_PENDING;
                            $job = new ModJob();
                            $job->setData($data);

                            $jobs[strval($fkProcessArea)] = $job;
                        }

                        // create job test:

                        $data = array();

                        //$data["job_test"] = ?;
                        $data["process_days"] = $sampleTest->getDatum("process_days");
                        $data["process_start_date"] = $sampleTest->getDatum("process_start_date");
                        $data["process_deadline"] = $sampleTest->getDatum("process_deadline");
                        $data["ext_job_num"] = "";
                        $data["ext_tracking_num"] = "";
                        $data["ext_result_deadline_n"] = null;
                        $data["ext_result_released_n"] = null;
                        //$data["is_system"] = ?;
                        //$data["is_deleted"] = ?;
                        //$data["fk_job"] = ?;
                        $data["fk_test"] = $sampleTest->getDatum("fk_test");
                        $data["fk_entity"] = $sampleTest->getDatum("fk_entity");
                        $data["fk_sample_test"] = $sampleTest->getId();
                        $data["fk_job_test_status"] = ModConsts::OC_JOB_STATUS_PENDING;
                        $jobTest = new ModJobTest();
                        $jobTest->setData($data);

                        $job->getChildJobTests()[] = $jobTest;
                    }

                    // add jobs to sample as auxiliar childs to be save withing sample in a single database transaction:
                    foreach ($jobs as $job) {
                        $sample->getAuxChildJobs()[] = $job;
                    }

                    // create report:

                    $data = array();

                    $data["report_num"] = $sample->getDatum("sample_num");
                    $data["report_date"] = $recept->getDatum("recept_datetime");
                    $data["process_deviats"] = "";
                    $data["process_notes"] = "";
                    $data["reissue"] = 0;
                    //$data["is_system"] = ?;
                    //$data["is_deleted"] = ?;
                    $data["fk_company_branch"] = $sample->getDatum("fk_company_branch");
                    $data["fk_customer"] = $sample->getDatum("fk_customer");
                    $data["fk_sample"] = $sample->getId();
                    $data["fk_recept"] = $recept->getId();
                    $data["fk_report_delivery_type"] = $sample->getDatum("fk_report_delivery_type");
                    $data["nk_report_reissue_cause"] = null;
                    $data["fk_report_status"] = ModConsts::OC_REPORT_STATUS_PENDING;
                    $report = new ModReport();
                    $report->setData($data);

                    foreach ($sample->getChildSampleTests() as $sampleTest) {
                        // create report test:

                        $data = array();

                        //$data["report_test"] = ?;
                        $data["result"] = "";
                        //$data["is_system"] = ?;
                        //$data["is_deleted"] = ?;
                        //$data["fk_report"] = ?;
                        $data["fk_test"] = $sampleTest->getDatum("fk_test");
                        //$data["fk_job_test"] = ?;
                        $data["fk_sample_test"] = $sampleTest->getId();
                        $data["nk_result_unit"] = null;
                        $reportTest = new ModReportTest();
                        $reportTest->setData($data);

                        $report->getChildReportTests()[] = $reportTest;
                    }

                    // add report to sample as auxiliar child to be save withing sample in a single database transaction:
                    $sample->setAuxChildReport($report);
                }
            }

            FModelUtils::save($userSession, $recept);
        }
    }
}

// redirect:
header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_recept.php");
// eof
