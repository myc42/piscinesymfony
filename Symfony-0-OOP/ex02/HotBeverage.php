<?php 

class HotBeverage
{

//Ici, les propriétés sont déclarées dans le corps de la classe puis initialisées dans le constructeur.(a revoir )

    protected string $name ;
    protected int  $price ;
    protected int  $resistance ;

    public function __construct(string $name, int $price , int $resistance)
    {
        $this->name = $name;
        $this->price = $price;
        $this->resistance = $resistance;

    }


    public function getName() : string
    {
        return $this->name;
    }

    public function getPrice() : int 
    {
        return $this->price;

    }

    public function getResistance() : int 
    {
        return $this->resistance;
    }
}




?>