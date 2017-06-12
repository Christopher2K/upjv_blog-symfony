<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use BlogBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\ReportingComment;

class CommentsController extends Controller
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
                $this->get('session')->set('previousRequestAdd', $request);
                $this->addFlash('error', 'Le formulaire contient des erreurs.');

            }
        } else {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour poster des commentaires.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @param $commentId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function modifyAction($commentId, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_REVIEWER')) {
            $comment = $this->getDoctrine()
                ->getRepository('BlogBundle:Comment')
                ->find($commentId);

            $user = $this->get('security.token_storage')->getToken()->getUser();

            if ($comment->getAuthor() == $user) {

                $formComment = $this->createForm(CommentType::class, $comment, [
                    'action' => $this->generateUrl('comment_modify', ['commentId' => $comment->getId()])
                ]);

                $formComment->handleRequest($request);

                if ($formComment->isSubmitted() && $formComment->isValid()) {
                    $comment = $formComment->getData();
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $this->addFlash('notice', 'Commentaire modifié avec succès !');
                } else {
                    $this->get('session')->set('previousRequestModify', $request);

                    $this->addFlash('error', 'Le formulaire de modification contient des erreurs.');
                }

            } else {
                $this->addFlash('error', 'Vous n\'avez pas les droits pour modifier des commentaires.');
            }

        } else {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour modifier des commentaires.');
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
            $comment->getAuthor()->getId() === $user->getId()
        ) {


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

    public function listAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_REVIEWER')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $commentsRepository = $this->getDoctrine()->getRepository('BlogBundle:Comment');
            $comments = $commentsRepository->findByAuthor($user);

            $formsModifyComment = array_map(function (Comment $comment) {
                return $this->createForm(CommentType::class, $comment, [
                    'action' => $this->generateUrl('comment_edit', [
                        'commentId' => $comment->getId()
                    ])
                ])->createView();
            }, $comments);

            return $this->render('BlogBundle:Comments:list.html.twig', [
                'comments' => $comments,
                'forms_modify_comment' => $formsModifyComment
            ]);
        } else {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour consulter cette page.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function reportAction($id)
    {
        $signalement = new ReportingComment;
        $comment = new comment;
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('BlogBundle:Comment')->find($id);
        $signalement->setComment($comment);
        $signalement->setUser($this->getUser());
        $em->persist($signalement);
        $em->flush();
        $url = $this->generateUrl('article_list');
        return $this->redirect($url);
    }

}
