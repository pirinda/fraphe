<?php
$a1 = array("v1" => 1, "v2" => 2, "v3" => 3);
//$a2 = array("v1" => "1");
//$a2 = array("v1" => "1", "v2" => "2");
//$a2 = array("v1" => "1", "v2" => "2", "v3" => "3");
//$a2 = array("v5" => "1", "v2" => "2", "v3" => "3");
//$a2 = array("v13" => "3", "v11" => "1", "v12" => "2");
//$a2 = array("1", "v1" => "1", "2");
//$a2 = array("v1" => 1, "v2" => 2, "v3" => 3, "v4" => 4);
$a2 = array("v1" => 5, "v2" => 2, "v3" => 3, "v4" => 4);

echo '<h3>array_diff</h3>';
print_r(array_diff($a1, $a2));

echo '<h3>array_diff_assoc</h3>';
print_r(array_diff_assoc($a1, $a2));

echo '<h3>¿empty array_diff_assoc?</h3>';
var_dump(empty(array_diff_assoc($a1, $a2)));
