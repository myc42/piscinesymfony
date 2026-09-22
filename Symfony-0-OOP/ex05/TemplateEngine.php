<?php

class TemplateEngine
{
    public function __construct(
        private Elem $elem
    ) {}

    public function createFile(string $fileName): int|false
    {
        return file_put_contents($fileName, $this->elem->getHTML());
    }
}