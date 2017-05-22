<?php
/**
 * Created by PhpStorm.
 * User: christopher
 * Date: 22/05/2017
 * Time: 20:56
 */

namespace BlogBundle\DataFixtures\ORM;


use BlogBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUsersData
 * @package BlogBundle\DataFixtures\ORM
 *
 * Fixture qui va créer et insérer les utilisateurs
 *
 * Cette classe étant une fixture possédant un ordre, elle doit hériter de AbstractFixgture et implémenter l'interface
 * OrdererFixtureInterface
 */
class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // On crée un objet, on l'instancie, on y met les donneés et on le marque comme étant persistant à l'aide du manager
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('admin');
        $manager->persist($userAdmin);

        $userReader = new User();
        $userReader->setUsername('reader');
        $userReader->setPassword('reader');
        $manager->persist($userReader);

        $userWriter = new User();
        $userWriter->setUsername('writer');
        $userWriter->setPassword('writer');
        $manager->persist($userWriter);

        $userReviewer = new User();
        $userReviewer->setUsername('reviewer');
        $userReviewer->setPassword('reviewer');
        $manager->persist($userReviewer);

        // Le flush pour enregistrer les données
        $manager->flush();

        // Afin de pouvoir réutiliser ces objets, on les places en référence
        $this->addReference('userAdmin', $userAdmin);
        $this->addReference('userReader', $userReader);
        $this->addReference('userWriter', $userWriter);
        $this->addReference('userReviewer', $userReviewer);
    }

    public function getOrder()
    {
        // L'ordre d'exécution de la fixture
        return 1;
    }

}