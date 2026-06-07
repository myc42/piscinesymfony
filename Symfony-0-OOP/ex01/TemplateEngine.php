<?php


class TemplateEngine
{
   
     public function createFile($fileName, $text)
     {
          $htmlContenu = $text->readData();
          return file_put_contents($fileName, $htmlContenu);
     }
    
}
?>