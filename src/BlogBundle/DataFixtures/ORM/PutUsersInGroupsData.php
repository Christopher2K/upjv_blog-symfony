<?php
/**
 * Created by PhpStorm.
 * User: christopher
 * Date: 22/05/2017
 * Time: 21:09
 */

namespace BlogBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PutUsersInGroupsData
 * @package BlogBundle\DataFixtures\ORM
 *
 * Fixture qui va lier utilisateurs et groupes
 */
class PutUsersInGroupsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // On récupères les référénces paramétrée dans les fixtures des utilisateurs
        $admin = $this->getReference('userAdmin');
        $reader = $this->getReference('userReader');
        $writer = $this->getReference('userWriter');
        $reviewer = $this->getReference('userReviewer');

        $gAdmin = $this->getReference('roleAdmin');
        $gReader = $this->getReference('roleReader');
        $gWriter = $this->getReference('roleWriter');
        $gReviewer = $this->getReference('roleReviewer');

        // Ajoute du role du coté propriétaire !!
        $admin->addRole($gAdmin);
        $reader->addRole($gReader);
        $writer->addRole($gWriter);
        $reviewer->addRole($gReviewer);

        // On flush le tout
        $manager->flush();
    }

    public function getOrder()
    {
        // Fixture éxécutée en position numéro 3
        return 3;
    }
}