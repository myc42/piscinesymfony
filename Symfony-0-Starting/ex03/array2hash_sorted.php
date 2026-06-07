<?php

function array2hash_sorted(array $array)
{
    $result = [];

    foreach ($array as $personne) {
        $nom = $personne[0];
        $age = $personne[1];

        $result[$nom] = $age;
    }

    krsort($result) ;
    return ($result);
}



?>