<?php

class Elem
{
    private array $children = [];

    public function __construct(
        private string $element,
        private string $content = "",
        private array $attributes = []
    ) {
        $allowed = [
            "meta", "img", "hr", "br", "html", "head",
            "body", "title", "h1", "h2", "h3", "h4",
            "h5", "h6", "p", "span", "div",
            "table", "tr", "th", "td", "ul", "ol", "li"
        ];

        if (!in_array($this->element, $allowed)) {
            throw new MyException("Balise HTML invalide");
        }
    }

    public function pushElement(Elem $elem): void
    {
        $this->children[] = $elem;
    }

    public function getHTML(): string
    {
        $html = "<{$this->element}";

        foreach ($this->attributes as $key => $value) {
            $html .= " {$key}=\"{$value}\"";
        }

        $html .= ">";
        $html .= $this->content;

        foreach ($this->children as $child) {
            $html .= $child->getHTML();
        }

        $html .= "</{$this->element}>";

        return $html;
    }
}