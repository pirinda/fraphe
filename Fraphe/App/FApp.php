<?php
namespace Fraphe\App;

class FApp
{
    public function __construct()
    {
        $this->show();
    }

    public function show()
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '<title>App 1.0</title>';
        echo '</head>';
        echo '<body>';
        echo '<h1>App 1.0</h1>';
        echo '<div>App. root: ' . APP_ROOT . '</div>';
        echo '</body>';
        echo '</html>';
    }
}
