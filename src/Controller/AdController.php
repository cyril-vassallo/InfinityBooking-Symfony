<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo, Session $session) {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     *
     * Permet de créer une annonce
     *
     * @Route("/ads/new",name="ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface  $manager){
        $ad = new Ad();
        // $image = new Image();
        // $image->setUrl('http://placehold.it/500x200');
        // $image->setCaption('Super image1');
        // $image2 = new Image();
        // $image2->setUrl('http://placehold.it/500x200');
        // $image2->setCaption('Super image2');
        // $ad->addImage($image);
        // $ad->addImage($image2);
        
        //creation du formulaire a partir de la class du formulaire créé avec la cli php bin/console make:form et l'entité à binder
        $form = $this->createForm(AnnonceType::class, $ad);
        // $request->request->get('title'); // A la mano
        //Relie le formulaire à l'entité 
        //on demande au formulaire créer d'attraper les données de la requête
        $form->handleRequest($request);
        // dump($ad);
        
        //on utilise encore deux méthodes de l'objet form
        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            //$manager = $this->getDoctrine()->getManager(); // sans injection de dépendances
            $manager->persist($ad); // préparer l'enregistrement des données dans $ad
            $manager->flush(); //
            
            //method addFlash
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }
            
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("ads/{slug}/edit" , name="ads_edit")
     *
     * @return response
     */
    public function edit(Ad $ad , Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);

        //on utilise encore deux méthodes de l'objet form
        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            //$manager = $this->getDoctrine()->getManager(); // sans injection de dépendances
            $manager->persist($ad); // préparer l'enregistrement des données dans $ad
            $manager->flush(); //
            
            //method addFlash
            $this->addFlash(
                'success',
                "Les modifications <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }


        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);

    }

    /**
     * Cette fonction affiche une seule annonce grace au ParamConverter qui inject un objet qui dépend du paramètre dans la route
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
