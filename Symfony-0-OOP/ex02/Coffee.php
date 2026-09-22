<?php


class Coffee extends HotBeverage
{
    public function __construct(
        private string $description,
        private string $comment
    ) {
        parent::__construct("coffee", 6, 6);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}

?>