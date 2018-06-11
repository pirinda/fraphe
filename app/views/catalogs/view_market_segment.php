<?php
// start session:
if (!isset($_SESSION)) {
    session_start();
}

// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe" . DIRECTORY_SEPARATOR . "fraphe.php";

use Fraphe\App\FApp;
use Fraphe\App\FAppConsts;
use Fraphe\App\FAppNavbar;
use Fraphe\App\FGuiUtils;

echo '<!DOCTYPE html>';
echo '<html>';
echo FApp::composeHtmlHead();
echo '<body>';
echo FAppNavbar::compose();

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Segmentos de mercado</h3>';

$conn = FGuiUtils::createConnection();
$sql = <<<SQL
SELECT name, id_market_segment
FROM cc_market_segment
WHERE NOT deleted
ORDER BY name, id_market_segment
SQL;

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>ID</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($conn->query($sql) as $row) {
    echo '<tr>';
    echo '<th>' . $row['name'] . '</th>';
    echo '<th>' . $row['id_market_segment'] . '</th>';
    echo '<th>ID</th>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
