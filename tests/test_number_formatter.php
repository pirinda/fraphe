<?php
//$nf = new \NumberFormatter(Locale::getDefault(), \NumberFormatter::PATTERN_DECIMAL);
$nf = new \NumberFormatter("", \NumberFormatter::PATTERN_DECIMAL);
$nf->setAttribute(\NumberFormatter::MIN_INTEGER_DIGITS, 6);
echo $nf->format(1) . '<br>';
echo date("Y-m-d H:i:s") . '<br>';
echo date(DateTime::W3C) . '<br>';
