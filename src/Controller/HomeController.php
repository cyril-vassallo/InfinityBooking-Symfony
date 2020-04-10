<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Egulias\EmailValidator\Warning\Warning;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{
    
    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo){
        $this->addFlash(
            'danger',
            "Attention !! Ce site n'est pas un réel site de réservation. Il est une démonstration pour portfolio développeur. Cette application tourne sur <a href='https://symfony.com/'><strong> Symfony 4.3 </strong></a> installé dans un service container de Heroku !"
        );
        $this->addFlash(
            'warning',
            "Certaines des images peuvent ne pas s'afficher correctement car les sources proviennent de l'api <a href='http://lorempixel.com/'><strong> lorempixel.com </strong> </a>très largement sollicitée par la communauté des développeurs, merci de votre comprehension."
        );


        return $this->render('home.html.twig',[
            'bestAds' => $adRepo->findBestAds(3),
            'bestUsers' => $userRepo->findBestUsers()
        ]);
    }
    
}


?>