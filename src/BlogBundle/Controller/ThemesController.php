<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ThemesController extends Controller
{
    public function chooseThemeAction()
    {
        $themeRepository = $this->getDoctrine()->getRepository('BlogBundle:Theme');
        $theme = $themeRepository->findAll();

        return $this->render('BlogBundle:Themes:choose_theme.html.twig', array(
            'theme' => $theme
        ));

    }

    public function exampleAction(Request $request, $id){
        //..
        $inputValue = $request->get("button_name");
        //..
    }


}
