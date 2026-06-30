<?php

$x = 5; //global variable

function localVariable() {
    $y = 10;
    echo $y; //variabel lokale
}

localVariable();
echo "\n, $x";

?>