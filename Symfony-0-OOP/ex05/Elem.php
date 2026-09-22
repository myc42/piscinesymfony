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


    public function validPage(): bool
    {
        if ($this->element === 'html') {
            if (count($this->children) !== 2) {
                return false;
            }
            if ($this->children[0]->element !== 'head' || $this->children[1]->element !== 'body') {
                return false;
            }
        }

        if ($this->element === 'head') {
            $titleCount = 0;
            $metaCharsetCount = 0;

            foreach ($this->children as $child) {
                if ($child->element === 'title') {
                    $titleCount++;
                }
                if ($child->element === 'meta' && isset($child->attributes['charset'])) {
                    $metaCharsetCount++;
                }
            }

            if ($titleCount !== 1 || $metaCharsetCount !== 1 || count($this->children) !== 2) {
                return false;
            }
        }

    
        if ($this->element === 'p' && !empty($this->children)) {
            return false;
        }


        if ($this->element === 'table') {
            foreach ($this->children as $child) {
                if ($child->element !== 'tr') {
                    return false;
                }
            }
        }

        if ($this->element === 'tr') {
            foreach ($this->children as $child) {
                if ($child->element !== 'th' && $child->element !== 'td') {
                    return false;
                }
            }
        }

   
        if ($this->element === 'ul' || $this->element === 'ol') {
            foreach ($this->children as $child) {
                if ($child->element !== 'li') {
                    return false;
                }
            }
        }

  
        foreach ($this->children as $child) {
            if (!$child->validPage()) {
                return false;
            }
        }

        return true;
    }

}