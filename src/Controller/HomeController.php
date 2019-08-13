<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{
    

    /**
     * @Route("hello/{prenom}/age/{age}", name="hello")
     * @Route("hello", name="hello_base")
     * @Route("hello/{prenom}/age", name="hello_prenom")
     * This function tell you who you are calling in Url
     */
    public function hello($prenom = "anonyme", $age = 0){
        return $this->render('hello.html.twig', [
            'prenom'=> $prenom,
            'age' => $age
        ]); 
    }


    /**
     * @Route("/", name="homepage")
     */
    public function home(){


        return $this->render('home.html.twig');
    }
}


?>