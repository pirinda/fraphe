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
use Fraphe\Model\FModelUtils;
use Fraphe\Model\FRegistry;
use app\models\ModConsts;
use app\models\operations\ModJob;

/* ?id={id of reception}&move={next|back}
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get recept to process:
    $job;
    $userSession = FGuiUtils::createUserSession();
    if (!empty($_GET[FRegistry::ID])) {
        $job = new ModJob();
        $job->read($userSession, intval($_GET[FRegistry::ID]), FRegistry::MODE_READ);
    }

    $process = false;
    if (isset($job)) {
        switch ($_GET["move"]) {
            case "next":
                switch ($job->getDatum("fk_job_status")) {
                    case ModConsts::OC_JOB_STATUS_PENDING:
                        $data = array();
                        $data["fk_job_status"] = ModConsts::OC_JOB_STATUS_PROCESSING;
                        $job->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_JOB_STATUS_PROCESSING:
                        $data = array();
                        $data["fk_job_status"] = ModConsts::OC_JOB_STATUS_FINISHED;
                        $job->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_JOB_STATUS_FINISHED:
                        break;

                    case ModConsts::OC_JOB_STATUS_CANCELLED:
                        break;

                    default:
                }
                break;

            case "back":
                switch ($job->getDatum("fk_job_status")) {
                    case ModConsts::OC_JOB_STATUS_PENDING:
                        break;

                    case ModConsts::OC_JOB_STATUS_PROCESSING:
                        $data = array();
                        $data["fk_job_status"] = ModConsts::OC_JOB_STATUS_PENDING;
                        $job->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_JOB_STATUS_FINISHED:
                        $data = array();
                        $data["fk_job_status"] = ModConsts::OC_JOB_STATUS_PROCESSING;
                        $job->setData($data);
                        $process = true;
                        break;

                    case ModConsts::OC_JOB_STATUS_CANCELLED:
                        break;

                    default:
                }
                break;

            default:
        }

        if ($process) {
            FModelUtils::save($userSession, $job);
        }
    }
}

// redirect:
header("Location: " . $_SESSION[FAppConsts::ROOT_DIR_WEB] . "app/views/operations/view_job.php");
// eof
