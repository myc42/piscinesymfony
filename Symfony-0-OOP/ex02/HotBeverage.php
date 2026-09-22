<?php 

class HotBeverage
{

//Ici, les propriétés sont déclarées dans le corps de la classe puis initialisées dans le constructeur.(a revoir )

    public function __construct(
        protected string $name,
        protected int $price,
        protected int $resistance
    ) {}


    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int 
    {
        return $this->price;
    }

    public function getResistance(): int 
    {
        return $this->resistance;
    }
}




?>