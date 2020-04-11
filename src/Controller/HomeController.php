<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{
    
    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo){
        $this->addFlash(
            'danger',
            "Attention !! <strong>Ce site n'est pas un réel site de réservation!!</strong> Il est une démonstration web basée sur le framework <a href='https://symfony.com/'><strong><i class='fas fa-link'></i>Symfony 4.3  </strong></a> et installé dans un service container de <a href='https://www.heroku.com/'><strong> <i class='fas fa-link'></i>Heroku</strong></a> !"
        );
        $this->addFlash(
            'warning',
            "Certaines des images peuvent ne pas s'afficher correctement car les sources proviennent de l'api <a href='http://lorempixel.com/'><strong> <i class='fas fa-link'></i>lorempixel.com </strong> </a>très largement sollicitée par la communauté des développeurs, merci de votre comprehension."
        );


        return $this->render('home.html.twig',[
            'bestAds' => $adRepo->findBestAds(3),
            'bestUsers' => $userRepo->findBestUsers()
        ]);
    }
    
}


?>