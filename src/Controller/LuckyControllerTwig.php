<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{

    /**
    * 
    * @route("/lucky", name="lucky")
    * 
    * 
    */
    public function number(): Response 
    {

        $number = random_int(0, 100);

        $data = [
            'number' => $number

        ];

        return $this->render('lucky_number.html.twig', $data);
    } 

    /**
     * 
     * @route("/", name="home")
     * 
     * 
     */
    public function home(): Response
    {

        return $this->render('home.html.twig');

    }



     /**
      * 
      * @route("/about", name="about")
      */
      public function about(): Response
    {

        return $this->render('about.html.twig');

    }

    /**
     * 
     * @route ("/api/quote", name="quote")
     * 
     * 
     */

    public function quote(): Response 
    {
        $date = date("l jS \of F Y h:i:s A");
        $quotenum = random_int(1, 5);
        switch ($quotenum) {
        case 1:
            $quote = "\"It's a dangerous business, Frodo, going out your door. You step onto the road,
            and if you don't keep your feet, there's no knowing wher you might be swept off to.\" - Bilbo Baggins";
            break;
        case 2:
            $quote = "\"Faithless is he that says farewell when the road darkens.\" - Gimli";
            break;
        
        case 3:
            $quote = "\"Do not think this ends here.. The history of Light and Shadow will be written in blood!\" - Ganondorf";
            break;
        case 4:
            $quote = "\"Tell me, who are you, alone, yourself, and nameless?\" - Tom Bombadil";
            break;
        case 5:
            $quote = "\"I name you Elf-friend; and may the stars shine upon the end of your road!
            Seldom have we had such delight in strangers,a and it is fair to hear words of the Ancient Speech from the lips of 
            other wanderers in the world.\" - Gildor Inglorion";
            break;
        }

        $data = [
        'datetime' => $date,
        'quote' => $quote
        ];
        
        return $this->render('quote.html.twig', $data);
    }

       /**
      * 
      * @route("/report", name="report")
      */
      public function report(): Response
    {

        return $this->render('report.html.twig');

    }

}