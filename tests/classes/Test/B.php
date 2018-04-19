<?php
namespace Test;

echo "This is file B.php<hr>";

class B
{
    public function __construct()
    {
        echo "Class 'B' instantiated!<br>";
    }

    public function doSomething()
    {
        echo "Class 'B': doing something...<br>";

        $a = new A();
        $a->doSomething();

        echo "Class 'B': something done!<br>";
    }
}
