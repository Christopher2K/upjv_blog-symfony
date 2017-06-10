<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
        $form->remove('username');
        //$form->add('roles',CheckboxType::class,$user.getRoles());
        $form->add('submit',SubmitType::class,array('label'=>'Modifier'));
        return $this->render('BlogBundle:Users:modifier.html.twig',array('monFormulaire'=>$form->createView(),'user'=>$user));
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
        $user=$entityManager->getRepository('BlogBundle:User')->find($id);
        $form = $this->createForm(UserType::class,$user,array('action'=>$this->generateUrl('admin_user_edit_suite',
            array('id'=>$user->getId()))));
        $form->remove('username');
        $form->add('submit',SubmitType::class,array('label'=>'Modifier'));
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $url=$this->generateUrl('admin_user_list');
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:Users:modifier.html.twig',array('monFormulaire'=>$form->createView(),'user'=>$user));
    }

    /**
     * show form and treat to add user into database
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function ajouterAction(Request $request)
    {
        $user = new User();
        $form=$this->createForm(UserType::class,$user,array('action'=>$this->generateUrl(('admin_user_ajouter'))));
        $form->add('submit',SubmitType::class,array('label'=>'Ajouter'));
        $form->handleRequest($request);
        if ($form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $url=$this->generateUrl('admin_user_list');
            return $this->redirect($url);
        }
        return $this->render('BlogBundle:Users:ajouter.html.twig',array('monFormulaire'=>$form->createView()));
    }

    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository('BlogBundle:User');
        $user = $userRepository->find($id);
        if($user!=null){

            $em->persist($user);
            $em->remove($user);
            $em->flush();
            $url=$this->generateUrl('admin_user_list');
            return $this->redirect($url);
        }
        $url=$this->generateUrl('admin_user_list');
        return $this->redirect($url);
    }

}
