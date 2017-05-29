<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticlesController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlogBundle:Articles:index.html.twig', array(
            // ...
        ));
    }

}
