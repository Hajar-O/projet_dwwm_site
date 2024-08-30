<?php

namespace App\Controller\admin;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    #[Route('/admin/comments-list', name: 'admin_comments')]
    public function listComments(CommentRepository $commentRepository)
    {

        $comments = $commentRepository->findAll();
        return $this->render('admin/comment/admin-comment-list.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('/admin/comments-delete/{id}', name: 'admin_comment_delete')]
    public function deleteComment(int $id, CommentRepository $commentRepository, Request $request, EntityManagerInterface $manager)
    {

        $comment = $commentRepository->find($id);

        try {
            $manager->remove($comment);
            $manager->flush();

            $this->addFlash('success', 'Commentaire supprimé.');
            return $this->redirectToRoute('admin_comments');
        } catch (\Exception $e) {
            //$this->addFlash('error', 'Une erreur est survenue lors le suppression.');

            return $this->render('admin/error/error.html.twig', [
                'error' => $e->getMessage()
            ]);
        }

        return $this->redirectToRoute('admin_ingredients');
    }
}

