<?php

class TemplateEngine
{
    public function __construct(
        private Elem $elem
    ) {}

    public function createFile(string $fileName)
    {
        $html = $this->elem->getHTML();

        return file_put_contents($fileName, $html);
    }
}