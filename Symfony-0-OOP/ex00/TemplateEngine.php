<?php

class TemplateEngine
{
   
     public function createFile($fileName, $templateName, $parameters)
     {
            $contenu = file_get_contents($templateName);
            if (!$contenu )
                    return false ;
            $texteFinal = str_replace(
                                        array_keys($parameters),   // Ce qu'on cherche : les {trous}
                                        array_values($parameters), // Ce qu'on met à la place : les mots
                                        $contenu             // Le texte de départ
                                    ) ;
             return file_put_contents($fileName, $texteFinal); ;
        
     }
    
}
?>