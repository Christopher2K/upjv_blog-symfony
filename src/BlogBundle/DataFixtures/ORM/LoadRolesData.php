<?php
/**
 * Created by PhpStorm.
 * User: christopher
 * Date: 22/05/2017
 * Time: 21:03
 */

namespace BlogBundle\DataFixtures\ORM;


use BlogBundle\Entity\Role;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadRolesData
 * @package BlogBundle\DataFixtures\ORM
 *
 * Fixture de création des rôles utilisateurs
 */
class LoadRolesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $roleAdmin = new Role();
        $roleAdmin->setRole('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        $roleReader = new Role();
        $roleReader->setRole('ROLE_READER');
        $manager->persist($roleReader);

        $roleWriter = new Role();
        $roleWriter->setRole('ROLE_WRITER');
        $manager->persist($roleWriter);

        $roleReviewer = new Role();
        $roleReviewer->setRole('ROLE_REVIEWER');
        $manager->persist($roleReviewer);

        $manager->flush();

        $this->addReference('roleAdmin', $roleAdmin);
        $this->addReference('roleReader', $roleReader);
        $this->addReference('roleWriter', $roleWriter);
        $this->addReference('roleReviewer', $roleReviewer);
    }

    public function getOrder()
    {
        return 2;
    }
}