<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\User;
use BlogBundle\Form\Type\UserType;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UsersController extends Controller
{
    /**
     * Return and display users list
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $userRepository = $this->getDoctrine()->getRepository('BlogBundle:User');
        $users= $userRepository->findAll();
        return $this->render('BlogBundle:Users:list.html.twig', [
            'users' => $users
        ]);
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
        if (!$user=$entityManager->getRepository('BlogBundle:User')->find($id))
            throw $this->createNotFoundException('L utilisateur[id='.$id.'] inexistant');
        $form = $this->createForm(UserType::class,$user,array('action'=>$this->generateUrl('admin_user_edit_suite',
            array('id'=>$user->getId()))));
        $form->add('submit',SubmitType::class,array('label'=>'Modifier'));
        return $this->render('BlogBundle:User:modifier.html.twig',array('monFormulaire'=>$form->createView()));
    }

    public function editActionSuite(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user=$entityManager->getRepository('BlogBundle:User')->find($id);
        $form = $this->createForm(UserType::class,$user,array('action'=>$this->generateUrl('admin_user_edit_suite',
            array('id'=>$user->getId()))));
        $form->add('submit',SubmitType::class,array('label'=>'Modifier'));
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $url=$this->generateUrl('user_list');
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:User:modifier.html.twig',array('monFormulaire'=>$form->createView()));
    }
}
