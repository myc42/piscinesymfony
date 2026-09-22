<?php

class TemplateEngine
{
     public function createFile(HotBeverage $text)
     {
        $fileName = get_class($text) . ".html";
        $contenu = file_get_contents("template.html");

        $reflection = new ReflectionClass($text);
        $donnees = [];

        // Correspondance spéciale quand le nom de l'attribut PHP diffère de la balise HTML
        $mapping = [
            'name' => 'nom'
        ];

        foreach ($reflection->getProperties() as $property) {
            $nomAttribut = $property->getName();
            $getter = 'get' . ucfirst($nomAttribut);

            if (method_exists($text, $getter)) {
                // Si une correspondance existe (ex: 'name' devient 'nom'), on l'utilise, sinon on garde le nom original
                $cleTemplate = $mapping[$nomAttribut] ?? $nomAttribut;

                $donnees['{' . $cleTemplate . '}'] = $text->$getter();
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