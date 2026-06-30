<?php
    // parametra
    function maximum($x, $y) {
        if ($x > $y) {
            return $x;
        } else {
            return $y;
        }
    }

    $a = 20;
    $b = 30;

    $test = maximum($a, $b);

    echo "The max of $a and $b is $test \n";
?>