<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher la liste des commentaire du site
     * 
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     * 
     * @param PaginationService $pagination
     * @return Response
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                    ->setLimit(25)
                    ->setPage($page);
                    
        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet à un administrateur d'éditer un commentaire
     *
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * 
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire n° <strong> {$comment->getId()}</strong> a bien été modifié !"
            );
        }


        return $this->render('admin/comment/edit.html.twig',[
            'comment'=> $comment,
            'form' => $form->createView()
        ]);

    }


    /**
     * Permet à l'administrateur de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * 
     * @param Comment $comment
     * @param EntityManager $manager
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager){
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong> {$comment->getAuthor()->getFullName()}</strong>  a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_comments_index');
    }
}
