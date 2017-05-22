<?php
/**
 * Created by PhpStorm.
 * User: christopher
 * Date: 22/05/2017
 * Time: 21:33
 */

namespace BlogBundle\DataFixtures\ORM;


use BlogBundle\Entity\Theme;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadThemesData
 * @package BlogBundle\DataFixtures\ORM
 *
 * Fixture de création des thèmes
 */
class LoadThemesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $t1 = new Theme();
        $t1->setTitle('Theme 1');

        $t2 = new Theme();
        $t2->setTitle('Theme 2');

        $t3 = new Theme();
        $t3->setTitle('Theme 3');

        $t4 = new Theme();
        $t4->setTitle('Theme 4');

        $t5 = new Theme();
        $t5->setTitle('Theme 5');

        $t6 = new Theme();
        $t6->setTitle('Theme 6');

        $manager->persist($t1);
        $manager->persist($t2);
        $manager->persist($t3);
        $manager->persist($t4);
        $manager->persist($t5);
        $manager->persist($t6);

        $this->setReference('t1', $t1);
        $this->setReference('t2', $t2);
        $this->setReference('t3', $t3);
        $this->setReference('t4', $t4);
        $this->setReference('t5', $t5);
        $this->setReference('t6', $t6);

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }

}