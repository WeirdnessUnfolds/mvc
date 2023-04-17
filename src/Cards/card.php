<?php 

namespace App\Card;
use App\Controller;

class Card
{   /* Represents the color and value of the card, random
    number between 1 and 52. */
    protected $cardvalue;
    protected $cardgraphic;

    public function __construct()
    {
        $this->cardvalue = random_int(1, 52);
        $this->cardgraphic = null;
    }   

    public function getcardNumrep(): int
    {
        return $this->cardvalue;
    }

    public function getAsGraphic(): string 
    {
        if ( $this->cardvalue <= 14)
        {
            $utf8_card  = mb_ord("🂡", "UTF-8");
        }
        
        else if ($this->cardvalue > 14 and $this->cardvalue < 28) {
            $utf8_card  = mb_ord("🂱", "UTF-8");
        }
        
        else if ($this->cardvalue > 28 and $this->cardvalue < 42)
        {
            $utf8_card  = mb_ord("🃁", "UTF-8");

        }
        
        else if ($this->cardvalue > 42)
        {
            $utf8_card  = mb_ord("🃑", "UTF-8");

        }  
        for($i = 1; $i <= ($this->cardvalue % 14); $i++)
        {   
            ++$utf8_card;
         }
    
    return mb_chr($utf8_card, "UTF-8");
}






}