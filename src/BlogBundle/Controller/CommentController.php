<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use BlogBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    /**
     * ADD A COMMENT TO THE SPECIFIED ARTICLE AND SEND A FLASH MESSAGE TO TELL WHAT HAPPENED
     * @param int $articleId -- On which article add a comment ?
     * @param Request $request -- HTTP Request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction($articleId, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_REVIEWER')) {
            $article = $this->getDoctrine()
                ->getRepository('BlogBundle:Article')
                ->find($articleId);

            $user = $this->get('security.token_storage')->getToken()->getUser();

            $newComment = new Comment();
            $newComment->setArticle($article)
                ->setAuthor($user)
                ->setNote(0);

            $formComment = $this->createForm(CommentType::class, $newComment, [
                'action' => $this->generateUrl('comment_add', ['articleId' => $articleId])
            ]);
            $formComment->handleRequest($request);

            if ($formComment->isSubmitted() && $formComment->isValid()) {
                $newComment = $formComment->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($newComment);
                $em->flush();
                $this->addFlash('notice', 'Commentaire enregistré avec succès !');
            } else {
                // Allow to display forms error even after the redirection to the article page !
                $this->get('session')->set('previousRequest', $request);
                $this->addFlash('error', 'Le formulaire contient des erreurs.');

            }
        } else {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour poster des commentaires.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * DELETE A COMMENT FROM AN ARTICLE AND SEND BACK WHAT HAPPENED
     * @param int $commentId -- Comment to delete
     * @param Request $request -- HTTP Request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($commentId, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $comment = $this->getDoctrine()
            ->getRepository('BlogBundle:Comment')
            ->find($commentId);

        if ($this->get('security.authorization_checker')->isGranted('ROLE_REVIEWER') &&
            $comment->getAuthor()->getId() === $user->getId()) {


            $manager = $this->getDoctrine()->getManager();

            if ($comment !== null) {
                $manager->remove($comment);
                $manager->flush();
                $this->addFlash('notice', 'Commentaire supprimé avec succès !');
            } else {
                $this->addFlash('error', 'Commentaire introuvable !');
            }
        } else {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

}
