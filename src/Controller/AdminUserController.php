<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user{page<\d+>?1}", name="admin_user_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(User::class)
        ->setLimit(5)
        ->setPage($page); 

        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet à l'administrateur de supprimer un utilisateur
     * 
     * @Route("/admin/user/{id}/delete", name="admin_users_delete")
     * 
     * @param User $user
     * @param EntityManager $manager
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager){
        if(count($user->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "L'utilisateur' <strong>{$user->getFullName()}</strong> ne peut pas être supprimée car des réservations sont en cours !"
            );
        }else{
            $manager->remove($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur'<strong> {$user->getFullName()}</strong>  a bien été supprimé !"
            );
        }

        return $this->redirectToRoute('admin_user_index');
    }

}
