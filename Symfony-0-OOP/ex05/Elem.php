<?php

class Elem
{
    private string $element;
    private string $content;
    private array $children = [];
    private array $attribus = [];

    public function __construct(string $element, string $content = "", array $attribus = [])
    {
        $allowed = [
            "meta", "img", "hr", "br", "html", "head",
            "body", "title", "h1", "h2", "h3", "h4",
            "h5", "h6", "p", "span", "div" ,  "table", "tr" , "th" , "td", "ul","ol", "li" ,
        ];

        if (!in_array($element, $allowed)) {
             throw new MyException("Balise HTML invalide : " . $element);
        }

        $this->element = $element;
        $this->content = $content;
        $this->attribus = $attribus;
    }

    public function pushElement(Elem $elem): void
    {
        $this->children[] = $elem;
    }
    public function getHTML(): string
    {
        $html = "<{$this->element}";

        foreach ($this->attribus as $key => $value) {
            $html .= " $key=\"$value\"";
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
            // 1. Racine doit être html
            if ($this->element !== "html") {
                return false;
            }

            // 2. Doit contenir exactement head + body
            if (count($this->children) !== 2) {
                return false;
            }

            $head = null;
            $body = null;

            foreach ($this->children as $child) {
                if ($child->element === "head") {
                    $head = $child;
                } elseif ($child->element === "body") {
                    $body = $child;
                } else {
                    return false;
                }
            }

            if (!$head || !$body) {
                return false;
            }

            // 3. HEAD validation
            $titleCount = 0;
            $metaCount = 0;

            foreach ($head->children as $child) {
                if ($child->element === "title") {
                    $titleCount++;
                } elseif ($child->element === "meta") {
                    $metaCount++;
                } else {
                    return false;
                }
            }

            if ($titleCount !== 1 || $metaCount !== 1) {
                return false;
            }

            // 4. BODY validation (tout ici directement)
            foreach ($body->children as $child) {

                // p = texte uniquement
                if ($child->element === "p") {
                    if (!empty($child->children)) {
                        return false;
                    }
                }

                // ul / ol = uniquement li
                elseif ($child->element === "ul" || $child->element === "ol") {
                    foreach ($child->children as $li) {
                        if ($li->element !== "li") {
                            return false;
                        }
                    }
                }

                // table rules
                elseif ($child->element === "table") {
                    foreach ($child->children as $tr) {
                        if ($tr->element !== "tr") {
                            return false;
                        }

                        foreach ($tr->children as $cell) {
                            if ($cell->element !== "td" && $cell->element !== "th") {
                                return false;
                            }
                        }
                    }
                }

                // head interdit dans body
                elseif ($child->element === "head") {
                    return false;
                }
            }

            return true;
        }
}