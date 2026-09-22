<?php

class Text
{
    private $stockages = [];

    function __construct($tbchaine)
    {

        $this->stockages = $tbchaine ;
    }

    public function append($nouvelleChaine)
    {
        $this->stockages[] = $nouvelleChaine;
    }
    
    public function readData()
    {
        $html = "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Mendeleiev</title>
        </head> 
        <body>"; 
        
        foreach($this->stockages as $stokage)
        {
               $html .= "<p>" . $stokage . "</p>";
        }
        
        $html .= "</body>
        </html>"; 
        
        return $html; 
    }

    //  public function createFile($fileName, $text)
    //  {
    //       $htmlContenu = $text->readData();
    //       return file_put_contents($fileName, $htmlContenu);
    //  }
}
?>