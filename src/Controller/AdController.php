<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo, Session $session)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     *
     * Permet de creer une annomce
     *
     * @Route("/ads/new",name="ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager){
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        // $request->request->get('title');
        $form->handleRequest($request);
        
        // dump($ad);
        
        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();
            
            //methode addFlash du controller
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
            
            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
                ]);
            }
            
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Cette fonction affiche une seule annonce grace au convert parametter
     *
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */

        public function show(Ad $ad){
        return $this->render('ad/show.html.twig',[

            'ad'=> $ad
        ]);
    }


    /*
    *Function avec injection de dependence
    *
    public function show($slug, adRepository $repo){

        $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',[

            'ad'=> $ad
        ]);
    }
    */

}
