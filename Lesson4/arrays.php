<?php

// $sports = array("Football", "Basketball", "Handball", "Voleyball");

$sports = ["Football", "Basketball", "Handball", "Voleyball"];

// echo $sports[0];

// echo end($sports);

// echo count($sports);

$length = count($sports);

for ($i = 0; $i < $length; $i++) {
    echo $sports[$i], "\n";
}

?>

<?php

// Krijo një array me disa vlera < 10 dhe gjej mesataren e tyre

$numbers = [2, 5, 7, 9, 4];

$sum = 0;
$length = count($numbers);

for ($i = 0; $i < $length; $i++) {
    $sum += $numbers[$i];
}

$average = $sum / $length;

echo "Shuma: " . $sum . "\n";
echo "Mesatarja: " . $average;

?>