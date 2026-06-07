<?php

include('./TemplateEngine.php');

$citadine = new TemplateEngine();

$utilisateur = [
    "{nom}"         => "Alice",
    "{auteur}"      => "aax",
    "{description}" => "Paris",
    "{prix}"        => 20,
];

$citadine->createFile("alice.html", "book_description.html", $utilisateur);

?>