<?php
/**
 * Created by PhpStorm.
 * User: christopher
 * Date: 22/05/2017
 * Time: 21:39
 */

namespace BlogBundle\DataFixtures\ORM;


use BlogBundle\Entity\Article;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadArticlesData
 * @package BlogBundle\DataFixtures\ORM
 *
 * Fixture de créations d'article, lié à l'auteur avec des themes associés.
 */
class LoadArticlesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $author = $this->getReference('userWriter');
        $t1 = $this->getReference('t1');
        $t2 = $this->getReference('t2');
        $t3 = $this->getReference('t3');
        $t4 = $this->getReference('t4');
        $t5 = $this->getReference('t5');
        $t6 = $this->getReference('t6');

        $article1 = new Article();
        $article1->setTitle("Premier article");
        $article1->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum molestie 
            blandit. Ut non lacus urna. Curabitur malesuada dictum tristique. Etiam consectetur, felis eget dictum 
            luctus, magna lectus commodo justo, in fermentum mauris quam a ipsum. Curabitur id purus lacinia, tincidunt 
            magna et, tristique dolor. Integer aliquet tortor ipsum. Vivamus in ornare lectus. Pellentesque sodales 
            sagittis dolor, vitae egestas enim laoreet nec. Vivamus eu purus odio. Suspendisse euismod tortor non 
            laoreet luctus. Nunc porttitor gravida consectetur.Maecenas semper tempor iaculis. Nullam molestie sit
            amet enim a sodales. Phasellus varius, ipsum vitae interdum semper, risus ante imperdiet nisl, sit amet 
            ultrices est sem quis urna. Nunc egestas est bibendum, vestibulum sem congue, elementum ante. Phasellus 
            convallis vestibulum tincidunt. Vestibulum ut turpis venenatis, cursus dolor at, mattis massa. Sed aliquet
            quam nec vulputate ultrices. Curabitur luctus, massa a fermentum commodo, enim magna porttitor dui, eget 
            consequat leo turpis in mauris. Vestibulum maximus in augue sit amet maximus. Nullam ut ipsum dui. Nulla 
            aliquet sit amet lectus sed maximus. Curabitur semper, dolor eget pharetra fringilla, lacus dolor congue 
            nisi, non molestie lacus nisl ultrices dolor. Donec quis nunc eu erat fermentum semper ut nec ante.");
        $article1->setAuthor($author);
        $article1->addTheme($t1);
        $article1->addTheme($t2);
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setTitle("Deuxième article");
        $article2->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum molestie 
            blandit. Ut non lacus urna. Curabitur malesuada dictum tristique. Etiam consectetur, felis eget dictum 
            luctus, magna lectus commodo justo, in fermentum mauris quam a ipsum. Curabitur id purus lacinia, tincidunt 
            magna et, tristique dolor. Integer aliquet tortor ipsum. Vivamus in ornare lectus. Pellentesque sodales 
            sagittis dolor, vitae egestas enim laoreet nec. Vivamus eu purus odio. Suspendisse euismod tortor non 
            laoreet luctus. Nunc porttitor gravida consectetur.Maecenas semper tempor iaculis. Nullam molestie sit
            amet enim a sodales. Phasellus varius, ipsum vitae interdum semper, risus ante imperdiet nisl, sit amet 
            ultrices est sem quis urna. Nunc egestas est bibendum, vestibulum sem congue, elementum ante. Phasellus 
            convallis vestibulum tincidunt. Vestibulum ut turpis venenatis, cursus dolor at, mattis massa. Sed aliquet
            quam nec vulputate ultrices. Curabitur luctus, massa a fermentum commodo, enim magna porttitor dui, eget 
            consequat leo turpis in mauris. Vestibulum maximus in augue sit amet maximus. Nullam ut ipsum dui. Nulla 
            aliquet sit amet lectus sed maximus. Curabitur semper, dolor eget pharetra fringilla, lacus dolor congue 
            nisi, non molestie lacus nisl ultrices dolor. Donec quis nunc eu erat fermentum semper ut nec ante.");
        $article2->setAuthor($author);
        $article2->addTheme($t3);
        $article2->addTheme($t4);
        $manager->persist($article2);

        $article3 = new Article();
        $article3->setTitle("Troisième article");
        $article3->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum molestie 
            blandit. Ut non lacus urna. Curabitur malesuada dictum tristique. Etiam consectetur, felis eget dictum 
            luctus, magna lectus commodo justo, in fermentum mauris quam a ipsum. Curabitur id purus lacinia, tincidunt 
            magna et, tristique dolor. Integer aliquet tortor ipsum. Vivamus in ornare lectus. Pellentesque sodales 
            sagittis dolor, vitae egestas enim laoreet nec. Vivamus eu purus odio. Suspendisse euismod tortor non 
            laoreet luctus. Nunc porttitor gravida consectetur.Maecenas semper tempor iaculis. Nullam molestie sit
            amet enim a sodales. Phasellus varius, ipsum vitae interdum semper, risus ante imperdiet nisl, sit amet 
            ultrices est sem quis urna. Nunc egestas est bibendum, vestibulum sem congue, elementum ante. Phasellus 
            convallis vestibulum tincidunt. Vestibulum ut turpis venenatis, cursus dolor at, mattis massa. Sed aliquet
            quam nec vulputate ultrices. Curabitur luctus, massa a fermentum commodo, enim magna porttitor dui, eget 
            consequat leo turpis in mauris. Vestibulum maximus in augue sit amet maximus. Nullam ut ipsum dui. Nulla 
            aliquet sit amet lectus sed maximus. Curabitur semper, dolor eget pharetra fringilla, lacus dolor congue 
            nisi, non molestie lacus nisl ultrices dolor. Donec quis nunc eu erat fermentum semper ut nec ante.");
        $article3->setAuthor($author);
        $manager->persist($article3);
        $article3->addTheme($t5);
        $article3->addTheme($t6);

        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}