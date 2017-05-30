<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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

    public function showAction($id)
    {
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $commentRepository = $this->getDoctrine()->getRepository('BlogBundle:Comment');

        $article = $articleRepository->find($id);
        $comments = $commentRepository->findBy([
            'article' => $article
        ]);

        return $this->render('BlogBundle:Articles:show.html.twig', [
            'article' => $article,
            'comments' => $comments
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $url = $this->generateUrl('article_show', array('id' => $article->getId()));
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:Articles:add.html.twig', array('myForm' => $form->createView()));
    }

    public function add2Action($title, $content, $createdAt, $author, $themes)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = new Article();
        //$article->setAuthor($author);
        $article->setContent($content);
        $article->setCreatedAt($createdAt);
        $article->setTitle($title);
        //$article->addTheme($themes);
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('article_show', array('id' => $article->getId()));
    }

    public function add3Action(Request $request)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('content', TextType::class)
            ->add('createdAt', TextType::class)
            ->add('title', TextType::class)
            ->add('envoyer', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
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
}
