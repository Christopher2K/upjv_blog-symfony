<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
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
    public function listAction()
    {
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $articles = $articleRepository->findAll();

        return $this->render('BlogBundle:Articles:list.html.twig', [
            'articles' => $articles
        ]);
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

        $article = $articleRepository->find($id);
        $comments = $commentRepository->findAllByArticleWithOrder($article);

        // Form comment
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $newComment = new Comment();
        $newComment->setArticle($article)
            ->setAuthor($user)
            ->setNote(0);

        $formComment = $this->createForm(CommentType::class, $newComment, [
            'action' => $this->generateUrl('comment_add', ['articleId' => $id])
        ]);

        // Get the preivous request in order to display forms errors or naaaaah.
        if ($this->get('session')->has('previousRequest')) {
            $formComment = $this->createForm(CommentType::class, $newComment, [
                'action' => $this->generateUrl('comment_add', ['articleId' => $id])
            ]);
            $formComment->handleRequest($this->get('session')->get('previousRequest'));
            $this->get('session')->remove('previousRequest');
        }

        return $this->render('BlogBundle:Articles:show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'form_comment' => $formComment->createView()
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
        $form->add('submit', SubmitType::class, array('label' => 'Add'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $article->setCreatedAt(date("Y m d"));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $url = $this->generateUrl('article_show', array('id' => $article->getId()));
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:Articles:add.html.twig', array('myForm' => $form->createView()));
    }

    public function navigationAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article');
        $article = $repository->findAll();
        return $this->render('BlogBundle:Articles:navigation.html.twig', array('articles' => $article));
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
            throw $this->createNotFoundException('L article[id='.$id.'] inexistant');
        $form = $this->createForm(ArticleType::class, $article, array('action'=>$this->generateUrl('article_edit_suite',
            array('id'=>$article->getId()))));
        $form->add('submit', SubmitType::class, array('label'=>'Modifier'));
        return $this->render('BlogBundle:Articles:modifier.html.twig', array('myForm'=>$form->createView(), 'article'=>$article));
    }

    /**
     * //treat datas from editAction
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editSuiteAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (!$article = $entityManager->getRepository('BlogBundle:Article')->find($id))
            throw $this->createNotFoundException('L article[id='.$id.'] inexistant');
        $form = $this->createForm(ArticleType::class, $article, array('action'=>$this->generateUrl('article_edit_suite',
            array('id'=>$article->getId()))));
        $form->add('submit',SubmitType::class, array('label'=>'Modifier'));
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $url = $this->generateUrl('article_show', array('id'=>$article->getId()));
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:Articles:modifier.html.twig', array('myForm'=>$form->createView(), 'article'=>$article));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $article = $articleRepository->find($id);
        if($article != null){

            $em->persist($article);
            $em->remove($article);
            $em->flush();
            $url = $this->generateUrl('article_list');
            return $this->redirect($url);
        }
        $url=$this->generateUrl('article_list');
        return $this->redirect($url);
    }

    public function signalerAction($id)
    {
        $signalement = new ReportingArticle;
        $article = new article;
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('BlogBundle:Article')->find($id);
        $signalement->setArticle($article);
        $signalement->setUser($this->getUser());
        $em->persist($signalement);
        $em->flush();
        $url = $this->generateUrl('article_list');
        return $this->redirect($url);
    }
}
