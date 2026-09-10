<?php

// Hapim/krijojmë file-in
$file = fopen("test.txt", "w");

// Shkruajmë diçka në file
fwrite($file, "Ky tekst u shkrua para se file-i te mbyllej.");

// Mbyllim file-in
fclose($file);

echo "File-i u mbyll me sukses!";

?>
