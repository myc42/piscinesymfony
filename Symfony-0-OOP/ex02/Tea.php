<?php


class Tea extends HotBeverage
{
    private string $description;
    private string $comment ;

    public function __construct(string $description, string $comment )
    {

     ///Puisque Tea et Coffee étendent HotBeverage, elles doivent obligatoirement donner des valeurs aux attributs du parent (name, price, resistance). Pour faire ça proprement en PHP, on utilise la fonction parent::__construct(...) à l'intérieur du constructeur de l'enfant.
        parent::__construct("Thé", 3, 5);
        $this->description = $description;
        $this->comment = $comment;

    }

    public function getDescription() : string
    {
        return $this->description ;
    }

     public function getComment() : string
    {
        return  $this->comment ;
    }
}

?>