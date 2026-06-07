#!/usr/bin/php
<?php

$lines = file("ex06.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$elements = [];

// parsing 
foreach ($lines as $line)
{
    // Sépare le nom du reste
    $tmp = explode(" = ", $line);

    $name = $tmp[0];

    // Sépare les infos
    $infos = explode(", ", $tmp[1]);

    $position = explode(":", $infos[0])[1];
    $number   = explode(":", $infos[1])[1];
    $small    = trim(explode(":", $infos[2])[1]);
    $molar    = explode(":", $infos[3])[1];
    $electron = explode(":", $infos[4])[1];

    $elements[] = array(
        "name" => $name,
        "position" => (int)$position,
        "number" => $number,
        "small" => $small,
        "molar" => $molar,
        "electron" => $electron
    );
}

$html = "<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<title>Mendeleiev</title>
<style>
table {
    border-collapse: collapse;
}
td {
    border: 1px solid black;
    padding: 10px;
    width: 140px;
    vertical-align: top;
}
.empty {
    border: none;
}
h4 {
    margin: 0;
}
ul {
    padding-left: 15px;
    margin: 0;
}
</style>
</head>
<body>
<table>
";


$currentPos = 0;
$first = true;

foreach ($elements as $el)
{
    // Nouvelle ligne si position = 0
    if ($el["position"] == 0)
    {
        if (!$first)
            $html .= "</tr>";

        $html .= "<tr>";
        $currentPos = 0;
        $first = false;
    }

    // cellules vides
    while ($currentPos < $el["position"])
    {
        $html .= "<td class='empty'></td>";
        $currentPos++;
    }

    // cellule élément
    $html .= "<td>";
    $html .= "<h4>".$el["name"]."</h4>";
    $html .= "<ul>";
    $html .= "<li>No ".$el["number"]."</li>";
    $html .= "<li>".$el["small"]."</li>";
    $html .= "<li>".$el["molar"]."</li>";
    $html .= "<li>".$el["electron"]." electron(s)</li>";
    $html .= "</ul>";
    $html .= "</td>";

    $currentPos++;
}


$html .= "</tr>
</table>
</body>
</html>";


 // 5. Écriture fichier
 
file_put_contents("mendeleiev.html", $html);

?>