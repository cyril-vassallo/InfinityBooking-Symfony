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
        return $this->render('home.html.twig',[
            'bestAds' => $adRepo->findBestAds(3),
            'bestUsers' => $userRepo->findBestUsers()
        ]);
    }
    
}


?>