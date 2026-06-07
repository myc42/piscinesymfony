<?php


// lines un array donc il doit forcement boucler meme sil nya qun element .

// $lines = file("ex01.txt");

// foreach ($lines as $line) {

//     echo $line ;
// }
//echo $lines ;


// $fichier = fopen("ex01.txt", "r");

// if ($fichier) {

//     while (($ligne = fgets($fichier)) !== false) {
//         echo $ligne;
//         echo "\n";
//     }

//     fclose($fichier);
// }


$contenu = file_get_contents("ex01.txt");

//$contenu = str_replace(",", "\n", $contenu);   

echo $contenu;

?>