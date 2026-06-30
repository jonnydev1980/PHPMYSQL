<?php 

function pjestueshmeria($numri)
{
    if ($numri % 2 == 0) {
        echo "numri eshte i plotpjestueshem me 2";
    } else {
        echo "$numri nuk eshte i plotpjestueshem me 2";
    }
}
         
pjestueshmeria(8);
echo "<br>";
pjestueshmeria(7);
    
?>