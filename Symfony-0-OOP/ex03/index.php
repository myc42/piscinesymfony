<?php

include('./Elem.php');
include('./TemplateEngine.php');

$html = new Elem("html");

$head = new Elem("head");
$title = new Elem("title", "Ma page");
$head->pushElement($title);

$body = new Elem("body");

$h1 = new Elem("h1", "Bienvenue");
$p = new Elem("p", "Ceci est un paragraphe.");
$div = new Elem("div", "Contenu d'une div.");

$body->pushElement($h1);
$body->pushElement($p);
$body->pushElement($div);

$html->pushElement($head);
$html->pushElement($body);

$template = new TemplateEngine($html);
$template->createFile("index.html");

echo "Fichier index.html créé avec succès.";

?>