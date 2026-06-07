<?php


$states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
];

$capitals = [
    'OR' => 'Salem',
    'AL' => 'Montgomery',
    'NJ' => 'Trenton',
    'KS' => 'Topeka',
];

function capital_city_from($state)
{
    global $states, $capitals;

    if (!isset($states[$state])) {
        return "Unknown\n";
    }

    $abbr = $states[$state];

    if (!isset($capitals[$abbr])) {
        return  "Unknown\n";
    }

 
    return "$capitals[$abbr]\n";
    
}


?>