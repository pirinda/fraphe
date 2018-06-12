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
echo FAppNavbar::compose("catalogs");

echo '<div class="container" style="margin-top:50px">';
echo '<h3>Ensayo</h3>';
echo '<form class="form-horizontal" action="action_page.php">';

echo '<div class="form_group">';
echo '<label class="control-label col-sm-2" for="process_area">Área proceso:</label>';
echo '<div class="col-sm-10">';
echo '<select class="form-control" id="process_area">';

echo '</select>';
echo '</div>';
echo '</div>';

echo ' <button type="submit" class="btn btn-default">Guardar</button>';

echo '</form>';
echo '</div>';

echo FApp::composeFooter();
echo '</body>';
echo '</html>';
