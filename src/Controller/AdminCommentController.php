<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(CommentRepository $repo, $page) 
    {

        $limit = 10;
        $start = $page * 10 - 10; //determins the offset for pagination.
        $total = count($repo->findAll());
        $pages = ceil($total / $limit);// rounds number above.

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }




    /**
     * to edit users comments via admin comments page
     *
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire numéro {$comment->getId()} a bien été modifié !"
            );
            return $this->redirectToRoute("admin_comments_index");
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }




    /**
     * Delete a users comment
     * 
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager){
        $id = $comment->getId();
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success', 
            "Le Commentaire n° {$id} a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_comments_index");
    }
}
