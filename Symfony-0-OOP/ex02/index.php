<?php 

include('./HotBeverage.php');
include('./Tea.php');
include('./Coffee.php');
include('./TemplateEngine.php');

$engine = new TemplateEngine();
$newcoffee = new coffee("aa","bb");
$newtea = new tea("cc","dd");

$engine->createFile($newcoffee);
$engine->createFile($newtea);




?>