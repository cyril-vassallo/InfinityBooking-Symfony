<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * Permet de connecter une administrateur
     * 
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUserName = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig',[
            'hasError' => $error !== null,
            'lastUserName' => $lastUserName 
        ]);
    }


    /**
     *  Permet de  déconnecter l'administrateur
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout(){
        //rien
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
