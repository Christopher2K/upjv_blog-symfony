<?php
/**
 * Created by PhpStorm.
 * User: Maxence
 * Date: 08/06/2017
 * Time: 21:17
 */

namespace BlogBundle\DataFixtures\ORM;


use BlogBundle\Entity\Comment;
use BlogBundle\Entity\ReportingArticle;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadReportingsPerArticlesData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $reader=$this->getReference('userReader');
        $art1 = $this->getReference('article1');
        $art3 = $this->getReference('article3');

        $ra1 = new ReportingArticle();
        $ra2 = new ReportingArticle();

        $ra1->setArticle($art1);
        $ra2->setArticle($art3);

        $ra1->setArticle($reader);
        $ra2->setArticle($reader);

        $manager->persist($ra1);
        $manager->persist($ra2);

        $this->addReference('ra1', $ra1);
        $this->addReference('ra2', $ra2);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}