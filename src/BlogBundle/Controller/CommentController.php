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
     *
     * @param $articleId -- Article we redirect to after the process
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
                'action' => $this->generateUrl('comment_add_from_article', ['articleId' => $articleId])
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

        return $this->redirectToRoute('article_show', ['id' => $articleId]);
    }

}
