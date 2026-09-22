<?php


class Tea extends HotBeverage
{
    public function __construct(
        private string $description,
        private string $comment
    ) {
        parent::__construct("tea", 6, 6);
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