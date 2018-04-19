<?php
namespace Test;

echo "This is file A.php<hr>";

class A
{
    public function __construct()
    {
        echo "Class 'A' instantiated!<br>";
    }

    public function doSomething()
    {
        echo "Class 'A': doing something...<br>";
        echo "Class 'A': somethin done!<br>";
    }
}
