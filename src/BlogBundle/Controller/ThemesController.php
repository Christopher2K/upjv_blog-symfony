<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Theme;
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
}
