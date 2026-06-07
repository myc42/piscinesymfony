<?php

class TemplateEngine
{
     public function createFile(HotBeverage $text)
     {
          $fileName = get_class($text) . ".html";
          $contenu = file_get_contents("template.html");

         
           $reflection = new ReflectionClass($text);

        $donnees = [];

        foreach ($reflection->getProperties() as $property) {
            $nomAttribut = $property->getName();

      
            $getter = 'get' . ucfirst($nomAttribut);

            if (method_exists($text, $getter)) {
                $donnees['{' . $nomAttribut . '}'] = $text->$getter();
            }
           }


          $texteFinal = str_replace(
               array_keys($donnees),
               array_values($donnees), 
               $contenu
          );

          return file_put_contents($fileName, $texteFinal);
     }
}