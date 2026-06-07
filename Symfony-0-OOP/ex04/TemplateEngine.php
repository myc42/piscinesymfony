<?php

class TemplateEngine
{
    private Elem $elem;

    public function __construct(Elem $elem)
    {
        $this->elem = $elem;
    }

    public function createFile(string $fileName)
    {
        $html = $this->elem->getHTML();

        return file_put_contents($fileName, $html);
    }
}

?>