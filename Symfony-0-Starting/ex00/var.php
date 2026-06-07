<?php


$variables = [
    "a" => 10,
    "b" => "10",
    "c" => "ten",
    "d" => 10.0
];

echo "My first variables:\n";

foreach ($variables as $key => $value) {

    echo $key . " contains : " . $value;
    echo " and has type: " . gettype($value) . "\n";
}

?>