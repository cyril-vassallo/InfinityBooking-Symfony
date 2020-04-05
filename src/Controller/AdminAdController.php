<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * Permet d'afficher la liste des annonces en pagination avec avec la valeur par défault page = 1 
     * 
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
                    ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'éditer une annonce d'existe
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param  $ad
     * @return response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce <strong> {$ad->getTitle()}</strong> a bien été enregistrée"
            );
        }

        return $this->render('admin/ad/edit.html.twig',[
            'ad' => $ad,
            'form'=> $form->createView()
        ]);

    }

    /**
     * Permet à l'administrateur de supprimer un annonce
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param EntityManager $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager){
        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "L'annonce <strong>{$ad->getTitle()}</strong> ne peut pas être supprimée car des réservations sont en cours !"
            );
        }else{
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }

}
