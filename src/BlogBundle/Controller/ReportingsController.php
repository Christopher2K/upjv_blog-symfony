<?php
/**
 * Created by PhpStorm.
 * User: Maxence
 * Date: 08/06/2017
 * Time: 21:00
 */

namespace BlogBundle\Controller;

use BlogBundle\Entity\ReportingArticle;
use BlogBundle\Entity\ReportingComment;
use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ReportingsController extends Controller
{
    public function listAction($param="")
    {
        $reportArticleRepository = $this->getDoctrine()->getRepository('BlogBundle:ReportingArticle');
        $reportsArticle = $reportArticleRepository->findAll();
        $reportCommentRepository = $this->getDoctrine()->getRepository('BlogBundle:ReportingComment');
        $reportsComment = $reportCommentRepository->findAll();

        if (is_string($param))
        {
            if($param==='Author')
            {
                usort($reportsArticle,function (ReportingArticle $a, ReportingArticle $b)
                {
                    $al=$a->getUser()->getUsername();
                    $bl=$b->getUser()->getUsername();
                    if($al==$bl) return 0;
                    return ($al > $bl) ? +1 : -1;

                });
            }
            elseif ($param==='Reviewer')
            {
                usort($reportsArticle,function (ReportingComment $a, ReportingComment $b)
                {
                    $al=$a->getUser()->getUsername();
                    $bl=$b->getUser()->getUsername();
                    if($al==$bl) return 0;
                    return ($al > $bl) ? +1 : -1;

                });
            }
            elseif ($param==='Article')
            {
                usort($reportsArticle,function (ReportingArticle $a, ReportingArticle $b)
                {
                    $al=$a->getArticle()->getTitle();
                    $bl=$b->getArticle()->getTitle();
                    if($al==$bl) return 0;
                    return ($al > $bl) ? +1 : -1;

                });
            }
        }
        return $this->render('BlogBundle:Reportings:list.html.twig', [
            'reports_article' => $reportsArticle,
            'reports_comment' => $reportsComment
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReportCommentAction($id, Request $request)
    {
        $referer = $request->headers->get('referer');

        if ($this->isGranted('ROLE_ADMIN') or $this->isGranted('ROLE_READER')) {
            $reportingCommentsRepository = $this
                ->getDoctrine()
                ->getRepository('BlogBundle:ReportingComment');

            $reporting = $reportingCommentsRepository->find($id);

            if ($this->isGranted('ROLE_READER') and $this->getUser() != $reporting->getUser()) {
                return $this->redirect($referer);
            }

            if ($reporting != null) {
                $this->getDoctrine()->getManager()->remove($reporting);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $this->redirect($referer);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReportArticleAction($id, Request $request)
    {
        $referer = $request->headers->get('referer');

        if ($this->isGranted('ROLE_ADMIN') or $this->isGranted('ROLE_READER')) {
            $reportingArticleRepository = $this
                ->getDoctrine()
                ->getRepository('BlogBundle:ReportingArticle');

            $reporting = $reportingArticleRepository->find($id);

            if ($this->isGranted('ROLE_READER') and $this->getUser() != $reporting->getUser()) {
                return $this->redirect($referer);
            }

            if ($reporting != null) {
                $this->getDoctrine()->getManager()->remove($reporting);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $this->redirect($referer);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selfListAction() {
        $reportArticleRepository = $this->getDoctrine()->getRepository('BlogBundle:ReportingArticle');
        $reportCommentRepository = $this->getDoctrine()->getRepository('BlogBundle:ReportingComment');

        $reportsArticle = $reportArticleRepository->findByUser($this->getUser());
        $reportsComment = $reportCommentRepository->findByUser($this->getUser());

        return $this->render('BlogBundle:Reportings:list.html.twig', [
            'reports_article' => $reportsArticle,
            'reports_comment' => $reportsComment,
        ]);
    }
}