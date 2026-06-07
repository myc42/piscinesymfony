<?php

include('./Text.php');
include('./TemplateEngine.php');

$elements = new Text(["aaa", "bbb"]);

$elements->append("ccc");
$elements->append("ddd");


$engine = new TemplateEngine();

$engine->createFile("mendeleiev.html", $elements);

// echo "Le fichier mendeleiev.html a bien été généré pour l'Exercice 01 !";