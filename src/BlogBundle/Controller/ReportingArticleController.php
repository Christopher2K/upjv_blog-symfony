<?php
/**
 * Created by PhpStorm.
 * User: Maxence
 * Date: 08/06/2017
 * Time: 21:00
 */

namespace BlogBundle\Controller;

use BlogBundle\Entity\ReportingArticle;
use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ReportingArticleController extends Controller
{
    public function listAction()
    {
        $reportRepository = $this->getDoctrine()->getRepository('BlogBundle:ReportingArticle');
        $reports = $reportRepository->findAll();

        return $this->render('BlogBundle:ReportingArticles:list.html.twig', [
            'reports' => $reports
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listPersoAction()
    {
        $reportRepository = $this->getDoctrine()->getRepository('BlogBundle:ReportingArticle');
        $reports = $reportRepository->findAll();
        $reportRepository2 = $this->getDoctrine()->getRepository('BlogBundle:ReportingComment');
        $reports2 = $reportRepository2->findAll();

        return $this->render('BlogBundle:ReportingArticles:listperso.html.twig', [
            'reports' => $reports,
            'reports2' => $reports2,
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository('BlogBundle:ReportingArticle');
        $report = $userRepository->find($id);
        $userRepository2 = $this->getDoctrine()->getRepository('BlogBundle:ReportingComment');
        $report2 = $userRepository2->find($id);

        if ($report != null) {
            $em->persist($report);
            $em->remove($report);
            $em->flush();
        }

        if ($report2 != null) {
            $em->persist($report2);
            $em->remove($report2);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reporting_list'));
    }

    //    public function testAction()
//    {
//        $report = new ReportingArticle();
//        $report2 = new ReportingArticle();
//        $entityManager = $this->getDoctrine()->getManager();
//        $user=$entityManager->getRepository('BlogBundle:User')->find(2);
//        $art1 = $entityManager->getRepository('BlogBundle:Article')->find(3);
//        $art2 = $entityManager->getRepository('BlogBundle:Article')->find(2);
//        $report->setArticle($art1);
//        $report->setUser($user);
//        $entityManager->persist($report);
//        $report2->setArticle($art2);
//        $report2->setUser($user);
//        $entityManager->persist($report2);
//        $entityManager->flush();
//    }
}