<?php

include_once('./MyException.php');
include_once('./Elem.php');
include_once('./TemplateEngine.php');

try {
    // test page valide 
    $htmlValid = new Elem("html");
    
    $head = new Elem("head");
    $head->pushElement(new Elem("title", "Titre valide"));
    $head->pushElement(new Elem("meta", "", ["charset" => "utf-8"]));

    $body = new Elem("body");
    $body->pushElement(new Elem("h1", "Bonjour"));
    $body->pushElement(new Elem("p", "Paragraphe sans balise"));

    $ul = new Elem("ul");
    $ul->pushElement(new Elem("li", "Item 1"));
    $body->pushElement($ul);

    $htmlValid->pushElement($head);
    $htmlValid->pushElement($body);

    echo "Test Page Valide : " . ($htmlValid->validPage() ? "OK (True)" : "KO (False)") . "\n";

    // --- test page invalide 
    $pInvalid = new Elem("p");
    $pInvalid->pushElement(new Elem("span", "Erreur"));

    $bodyInvalid = new Elem("body");
    $bodyInvalid->pushElement($pInvalid);

    $htmlInvalid = new Elem("html");
    $htmlInvalid->pushElement($head);
    $htmlInvalid->pushElement($bodyInvalid);

    echo "Test Page Invalide (balise dans p) : " . (!$htmlInvalid->validPage() ? "OK (False)" : "KO (True)") . "\n";

    // si le fichier est valdie genere
    if ($htmlValid->validPage()) {
        $template = new TemplateEngine($htmlValid);
        $template->createFile("index.html");
        echo "Fichier index.html généré avec succès.\n";
    }

} catch (MyException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}