<?php


//PHP transforme ça en : $arrays = [ 0 => [1,2], 1 => [3,4], 2 => [5,6] ];


//  function array2hash(array ... $arrays)
//  {
//      echo "Array \n( \n" ;
//       foreach($arrays as $cle  => $valeur)
//       {     
//             $newarray = $arrays[$cle];
//              echo  "   [$newarray[1]] =>";
//              echo  $newarray[0];
//              echo  "\n";
//       }
//      echo ") \n" ;

//  }

//  $user1 =["Pierre",20] ;
//  $user2 =["jhon",50] ;
//  $user3 =["marie",40] ;
//  $user5 =["hello",10] ;
 
// array2hash($user1,$user2,$user3,$user5) ;

function array2hash(array $array)
{
    $result = [];

    foreach ($array as $personne) {
        $nom = $personne[0];
        $age = $personne[1];

        $result[$age] = $nom;
    }

    return $result;
}




?>