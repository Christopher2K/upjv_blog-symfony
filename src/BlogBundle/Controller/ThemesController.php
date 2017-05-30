<?php

namespace BlogBundle\Controller;

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
            // create a task and give it some dummy data for this example
            $theme = new Theme();
            $theme->settitle('Toast');

            $form = $this->createFormBuilder($theme)
                ->add('task', TextType::class)
                ->add('save', SubmitType::class, array('label' => 'Create Post'))
                ->getForm();

            return $this->render('BlogBundle:Themes:choose_theme.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }
}
