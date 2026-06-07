<?php

include('./MyException.php');
include('./Elem.php');
include('./TemplateEngine.php');

try {

    // HTML
    $html = new Elem("html");

    // HEAD
    $head = new Elem("head");
    $title = new Elem("title", "Ma page");
    $head->pushElement($title);

    // BODY
    $body = new Elem("body");

    $h1 = new Elem("h1", "Bienvenue", ["class" => "title"]);
    $p = new Elem("p", "Lorem ipsum", ["class" => "text-muted"]);

    $body->pushElement($h1);
    $body->pushElement($p);

    // assemblage
    $html->pushElement($head);
    $html->pushElement($body);

    // génération fichier
    $template = new TemplateEngine($html);
    $template->createFile("index.html");

    echo "Fichier généré avec succès";

} catch (MyException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>