<?php

include('./MyException.php');
include('./Elem.php');


// 1. Créer une page HTML correcte
$html = new Elem("html");

$head = new Elem("head");
$head->pushElement(new Elem("title", "Ma page"));
//$head->pushElement(new Elem("meta", "", ["charset" => "UTF-8"]));

$body = new Elem("body");
$body->pushElement(new Elem("h1", "Bienvenue"));
$body->pushElement(new Elem("p", "Texte simple"));

$html->pushElement($head);
$html->pushElement($body);

// 2. Tester validPage()
if ($html->validPage()) {
    echo "Page VALIDE 👍";
} else {
    echo "Page INVALIDE ❌";
}

?>