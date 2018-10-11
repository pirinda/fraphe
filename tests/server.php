<?php
var_dump($_SERVER);
$s1 = "/test1/app/views/operations/view_recept.php?recept_st=all&dateTp1&dateTs=1539297053";
$s2 = "/test1/app/views/operations/view_recept.php?recept_st=all&dateTp=1&dateTs=1539297053";
echo "<hr>" . str_replace("dateTp1", "hola", $s1);
echo "<hr>" . str_replace("dateTs=1539297053", "adios", $s2);
