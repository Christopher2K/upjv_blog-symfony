<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use BlogBundle\Entity\UserTheme;
use BlogBundle\Form\CommentType;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\ReportingArticle;
use BlogBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticlesController extends Controller
{

    /**
     * GET & DISPLAY ALL ARTICLES
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');

        if ($this->isGranted('ROLE_READER')) {
            $userThemeRepository = $this->getDoctrine()->getRepository('BlogBundle:UserTheme');
            $userThemes = $userThemeRepository->findByUser($this->getUser());

            $themes = array_map(function (UserTheme $ut) {
                return $ut->getTheme();
            }, $userThemes);

            if ($this->isGranted('ROLE_REVIEWER')) {
                $articles = $articleRepository->findByThemes($themes, true);
            } else {
                $articles = $articleRepository->findByThemes($themes);
            }

            return $this->render('BlogBundle:Articles:list.html.twig', [
                'articles' => $articles
            ]);
        }

        if ($this->isGranted('ROLE_WRITER')) {
            $articles = $articleRepository->findByAuthor($this->getUser());

            $average = 0;
            $length = 0;

            foreach ($articles as $article) {
                foreach ($article->getComments() as $comment) {
                    $average += $comment->getNote();
                }
                $length += sizeof($article->getComments());
            }

            if ($length > 0) {
                $average = $average / $length;
            } else {
                $average = -1;
            }


            return $this->render('BlogBundle:Articles:list_author.html.twig', [
                'articles' => $articles,
                'average' => $average
            ]);
        }

    }

    /**
     * DISPLAY AN ANTICLE AND HIS COMMENTS
     *
     * @param $id - ID of the article
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        // Datas
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $commentRepository = $this->getDoctrine()->getRepository('BlogBundle:Comment');

        $userThemeRepository = $this->getDoctrine()->getRepository('BlogBundle:UserTheme');
        $userThemes = $userThemeRepository->findByUser($this->getUser());
        $userThemes = array_filter($userThemes, function(UserTheme $ut) {
           return $ut->getIsReviewer();
        });

        $article = $articleRepository->find($id);
        $comments = $commentRepository->findAllByArticleWithOrder($article);
        $isReviewer = false;

        foreach ($article->getThemes() as $articleTheme) {
            foreach ($userThemes as $userTheme) {
                if ($articleTheme == $userTheme) {
                    $isReviewer = true;
                }
            }
        }



        $average = 0;
        if (sizeof($comments) > 0) {
            foreach ($comments as $comment) {
                $average += $comment->getNote();
            }
            $average = $average / sizeof($comments);
        } else {
            $average = -1;
        }

        // Form comment
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $newComment = new Comment();
        $newComment->setArticle($article)
            ->setAuthor($user)
            ->setNote(0);

        $formsModifyComment = array_map(function (Comment $comment) {
            return $this->createForm(CommentType::class, $comment, [
                'action' => $this->generateUrl('comment_edit', ['commentId' => $comment->getId()])
            ])->createView();
        }, $comments);

        $formComment = $this->createForm(CommentType::class, $newComment, [
            'action' => $this->generateUrl('comment_add', ['articleId' => $id])
        ]);

        // Get the preivous request in order to display forms errors or naaaaah.
        if ($this->get('session')->has('previousRequestAdd')) {
            $formComment = $this->createForm(CommentType::class, $newComment, [
                'action' => $this->generateUrl('comment_add', ['articleId' => $id])
            ]);
            $formComment->handleRequest($this->get('session')->get('previousRequest'));
            $this->get('session')->remove('previousRequestAdd');
        }

        return $this->render('BlogBundle:Articles:show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'form_comment' => $formComment->createView(),
            'average' => $average,
            'isReviewer' => $isReviewer,
            'forms_modify_comment' => $formsModifyComment
        ]);
    }

    /**
     * Add an article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article,
            array('action' => $this->generateUrl('article_add')));
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $article->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $url = $this->generateUrl('article_show', array('id' => $article->getId()));
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:Articles:add.html.twig', array('article_form' => $form->createView()));
    }

    /**
     * get User informations and display in form
     * then call editActionSuite which treats datas
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (!$article = $entityManager->getRepository('BlogBundle:Article')->find($id))
            throw $this->createNotFoundException('L article[id=' . $id . '] inexistant');
        $form = $this->createForm(ArticleType::class, $article, array('action' => $this->generateUrl('article_edit_next',
            array('id' => $article->getId()))));
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('BlogBundle:Articles:edit.html.twig', array('myForm' => $form->createView(), 'article' => $article));
    }

    /**
     * //treat datas from editAction
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editNextAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (!$article = $entityManager->getRepository('BlogBundle:Article')->find($id))
            throw $this->createNotFoundException('L article[id=' . $id . '] inexistant');


        $form = $this->createForm(ArticleType::class, $article, array('action' => $this->generateUrl('article_edit_next',
            array('id' => $article->getId()))));
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);

        if ($form->isValid() && $this->getUser() == $article->getAuthor()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $url = $this->generateUrl('article_show', array('id' => $article->getId()));
            return $this->redirect($url);
        }

        return $this->render('BlogBundle:Articles:edit.html.twig', array('myForm' => $form->createView(), 'article' => $article));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $article = $articleRepository->find($id);

        if ($article != null) {
            if($this->isGranted('ROLE_ADMIN')||($article->getAuthor()==$this->getUser()))
            $em->remove($article);
            $em->flush();
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $url = $request->headers->get('referer');
        } else {
            $url = $this->generateUrl('article_list');
        }

        return $this->redirect($url);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reportAction($id)
    {
        $reporting = new ReportingArticle();
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('BlogBundle:Article')->find($id);
        $reporting->setArticle($article);
        $reporting->setUser($this->getUser());
        $em->persist($reporting);
        $em->flush();

        return $this->redirect($this->generateUrl('article_show', ['id' => $id]));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function readAction($id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('BlogBundle:Article')->find($id);
        $user->addReadArticle($article);
        $em->flush();

        return $this->redirect($this->generateUrl('article_show', ['id' => $id]));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unreadAction($id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('BlogBundle:Article')->find($id);
        $user->removeReadArticle($article);
        $em->flush();
        $url = $this->generateUrl('article_show', ['id' => $id]);
        return $this->redirect($url);
    }


    /**
     * RETRIEVE THE GET PARAMETER FROM THE REQUEST AND FETCH ARTICLES
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $searchParam = $request->query->get('s');
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $articles = $articleRepository->findByAuthorOrTitle($searchParam);

        return $this->render('BlogBundle:Articles:search.html.twig', [
            'search_param' => $searchParam,
            'articles' => $articles
        ]);
    }
}
