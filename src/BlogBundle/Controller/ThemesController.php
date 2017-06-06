<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Theme;
use BlogBundle\Form\ThemeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ThemesController extends Controller
{
    public function chooseThemeAction(Request $request)
    {
        {
            $theme = new Theme();
            $form = $this->createFormBuilder($theme)
                ->add('title', EntityType::class, array(
                        'class' => 'BlogBundle:Theme',
                        'choice_label' => 'title',
                        'multiple' => true,
                        'expanded' => true,
                ))
                ->add('save', SubmitType::class, array('label' => 'Create Post'))
                ->getForm();

            return $this->render('BlogBundle:Themes:choose_theme.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    public function listAction(){
        $uR = $this->getDoctrine()->getRepository('BlogBundle:Theme');
        $themes=$uR->findAll();
        return $this->render('BlogBundle:Themes:list.html.twig', [
            'themes' => $themes
        ]);
    }

    public function ajouterAction(Request $request)
    {
        $theme = new Theme();
        $form=$this->createForm(ThemeType::class,$theme,array('action'=>$this->generateUrl(('admin_theme_ajouter'))));
        $form->add('submit',SubmitType::class,array('label'=>'Ajouter'));
        $form->handleRequest($request);
        if ($form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);
            $entityManager->flush();
            $url=$this->generateUrl('admin_theme_list');
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:Themes:ajouter.html.twig',array('monFormulaire'=>$form->createView()));
    }

    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository('BlogBundle:Theme');
        $user = $userRepository->find($id);
        if($user!=null){
            $em->persist($user);
            $em->remove($user);
            $em->flush();
            $url=$this->generateUrl('admin_theme_list');
            return $this->redirect($url);
        }
        $url=$this->generateUrl('admin_theme_list');
        return $this->redirect($url);
    }
}
