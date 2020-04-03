<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * Permet d'afficher la liste des réservations
     * 
     * @return Response
     * 
     * @Route("/admin/bookings", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo)
    {   $bookings = $repo->findAll(); 
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Permet à un administrateur d'éditer une réservation
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     * 
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager){
        // $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration()); // 1 ère méthode faire le calcule
        $booking->setAmount(0); // 2 ième méthode mettre 0 en argument est équivalent à empty pour la méthode prePersist()
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($booking);
            $manager->flush();


            $this->addFlash(
                "success",
                "La réservation n° <strong> {$booking->getId()}</strong> a bien été modifié par l'administrateur"
            );

            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig',[
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet à l'administrateur de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     * 
     * @param Booking $booking
     * @param EntityManager $manager
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager){
        
        $this->addFlash(
            "success",
            "La réservation n° <strong> {$booking->getId()}</strong> a bien été supprimé"
        );
        $manager->remove($booking);
        $manager->flush();

        return $this->redirectToRoute('admin_bookings_index');
    }


}
