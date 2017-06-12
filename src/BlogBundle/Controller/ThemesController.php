<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Theme;
use BlogBundle\Entity\UserTheme;
use BlogBundle\Form\ThemeType;
use BlogBundle\Form\UserThemeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ThemesController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $themeRepository = $this->getDoctrine()->getRepository('BlogBundle:Theme');
        $themes = $themeRepository->findAll();
        return $this->render('BlogBundle:Themes:list.html.twig', [
            'themes' => $themes
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme, array('action' => $this->generateUrl('admin_theme_add')));
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);
            $entityManager->flush();
            $url = $this->generateUrl('admin_theme_list');
            return $this->redirect($url);
        }

        return $this->render('BlogBundle:Themes:add.html.twig', [
            'monFormulaire' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $themeRepository = $this->getDoctrine()->getRepository('BlogBundle:Theme');
        $theme = $themeRepository->find($id);

        if ($theme != null) {
            $em->remove($theme);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_theme_list'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listReadAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $themes = $this->getDoctrine()
            ->getRepository('BlogBundle:Theme')
            ->findAll();

        $userThemes = $this->getDoctrine()
            ->getRepository('BlogBundle:UserTheme')
            ->findByUser($user);

        $alreadySelectedThemesId = array_map(function (UserTheme $ut) {
            return $ut->getTheme()->getId();
        }, $userThemes);

        $remaningThemes = [];
        foreach ($themes as $theme) {
            if (!in_array($theme->getId(), $alreadySelectedThemesId)) {
                $remaningThemes[] = $theme;
            }
        }

        $userTheme = new UserTheme();
        $userTheme->setUser($user)
            ->setIsReviewer(false);

        $userThemeForm = $this->createForm(UserThemeType::class, $userTheme, [
            'themes' => $remaningThemes
        ]);
        $userThemeForm->handleRequest($request);

        if ($userThemeForm->isSubmitted() && $userThemeForm->isValid()) {
            $userTheme = $userThemeForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($userTheme);
            $em->flush();

            $userThemes = $this->getDoctrine()
                ->getRepository('BlogBundle:UserTheme')
                ->findByUser($user);

            $alreadySelectedThemesId = array_map(function (UserTheme $ut) {
                return $ut->getTheme()->getId();
            }, $userThemes);

            $remaningThemes = [];
            foreach ($themes as $theme) {
                if (!in_array($theme->getId(), $alreadySelectedThemesId)) {
                    $remaningThemes[] = $theme;
                }
            }

            $userThemeForm = $this->createForm(UserThemeType::class, $userTheme, [
                'themes' => $remaningThemes
            ]);

            $this->addFlash('notice', 'Theme enregistré avec succès');
        } else if ($userThemeForm->isSubmitted() && !$userThemeForm->isValid()) {
            $this->addFlash('error', 'Le formulaire contient des erreurs.');
        }

        return $this->render('BlogBundle:Themes:listRead.html.twig', [
            'user_themes' => $userThemes,
            'user_theme_form' => $userThemeForm->createView()
        ]);
    }

    /**
     * @param $themeId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReadAction($themeId)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userThemes = $this->getDoctrine()
            ->getRepository('BlogBundle:UserTheme')
            ->findByUser($user);

        $deleted = false;

        foreach ($userThemes as $ut) {
            if ($ut->getTheme()->getId() == $themeId) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($ut);
                $em->flush();
                $deleted = true;
                $this->addFlash('notice', 'Theme supprimé avec succès');
            }
        }

        if (!$deleted) {
            $this->addFlash('error', 'Le theme n\' pas pu être supprimé');
        }

        return $this->redirectToRoute('theme_list_read');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listReviewAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userThemes = $this->getDoctrine()
            ->getRepository('BlogBundle:UserTheme')
            ->findByUser($user);

        $reviewThemes = array_filter($userThemes, function (UserTheme $ut) {
            return $ut->getIsReviewer();
        });

        $_notReviewer = array_filter($userThemes, function (UserTheme $ut) {
            return !$ut->getIsReviewer();
        });

        $availiableThemes = array_map(function (UserTheme $ut) {
            return $ut->getTheme();
        }, $_notReviewer);


        $modifiedUserTheme = new UserTheme();
        $modifiedUserTheme->setUser($user);
        $modifiedUserTheme->setIsReviewer(true);


        $userThemeForm = $this->createForm(UserThemeType::class, $modifiedUserTheme, [
            'themes' => $availiableThemes
        ]);
        $userThemeForm->handleRequest($request);

        if ($userThemeForm->isSubmitted() && $userThemeForm->isValid()) {
            $modifiedUserTheme = $userThemeForm->getData();
            $themeId = $modifiedUserTheme->getTheme()->getId();

            foreach ($userThemes as $ut) {
                if ($ut->getTheme()->getId() == $themeId) {
                    $ut->setIsReviewer(true);
                }
            }

            $userThemes = $this->getDoctrine()
                ->getRepository('BlogBundle:UserTheme')
                ->findByUser($user);

            $reviewThemes = array_filter($userThemes, function (UserTheme $ut) {
                return $ut->getIsReviewer();
            });

            $_notReviewer = array_filter($userThemes, function (UserTheme $ut) {
                return !$ut->getIsReviewer();
            });

            $availiableThemes = array_map(function (UserTheme $ut) {
                return $ut->getTheme();
            }, $_notReviewer);


            $userThemeForm = $this->createForm(UserThemeType::class, $modifiedUserTheme, [
                'themes' => $availiableThemes
            ]);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Theme modifié avec succès');

        }

        return $this->render('BlogBundle:Themes:listReview.html.twig', [
            'user_themes' => $reviewThemes,
            'user_theme_form' => $userThemeForm->createView()
        ]);
    }

    /**
     * REMOVE THE THEME FROM THE REVIEW THEME LIST
     * @param $themeId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReviewAction($themeId)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userThemes = $this->getDoctrine()
            ->getRepository('BlogBundle:UserTheme')
            ->findByUser($user);

        $updated = false;

        foreach ($userThemes as $ut) {
            if ($ut->getTheme()->getId() == $themeId) {
                $em = $this->getDoctrine()->getManager();
                $ut->setIsReviewer(false);
                $em->flush();
                $updated = true;
                $this->addFlash('notice', 'Theme mis à jour avec succès');
            }
        }

        if (!$updated) {
            $this->addFlash('error', 'Le theme n\' pas pu être modifié');
        }

        return $this->redirectToRoute('theme_list_review');
    }
}
