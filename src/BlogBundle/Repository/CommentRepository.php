<?php

namespace BlogBundle\Repository;

use BlogBundle\Entity\Article;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllByArticleWithOrder(Article $article)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.article = :article')
            ->setParameter('article', $article->getId())
            ->orderBy('c.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
