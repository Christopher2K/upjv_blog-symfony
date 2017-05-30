<?php

namespace BlogBundle\DataFixtures\ORM;


use BlogBundle\Entity\Comment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommentsPerArticlesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $reviewer = $this->getReference('userReviewer');
        $art1 = $this->getReference('article1');
        $art3 = $this->getReference('article3');

        $c1 = new Comment();
        $c2 = new Comment();

        $c3 = new Comment();
        $c4 = new Comment();

        $c1->setAuthor($reviewer)
            ->setNote(1)
            ->setContent("Commentaire 1")
            ->setArticle($art1);
        $manager->persist($c1);

        $c2->setAuthor($reviewer)
            ->setNote(5)
            ->setContent("Commentaire 2")
            ->setArticle($art3);
        $manager->persist($c2);

        $c3->setAuthor($reviewer)
            ->setNote(10)
            ->setContent("Commentaire 3")
            ->setArticle($art3);
        $manager->persist($c3);

        $c4->setAuthor($reviewer)
            ->setNote(9)
            ->setContent("Commentaire 4")
            ->setArticle($art3);
        $manager->persist($c4);

        $manager->flush();

        $this->addReference('c1', $c1);
        $this->addReference('c2', $c2);
        $this->addReference('c3', $c3);
        $this->addReference('c4', $c4);
    }

    public function getOrder()
    {
        return 6;
    }

}