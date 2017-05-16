<?php

namespace BlogBundle\Entity;

/**
 * ReportingArticle
 */
class ReportingArticle
{
    /**
     * @var int
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var \BlogBundle\Entity\User
     */
    private $user;

    /**
     * @var \BlogBundle\Entity\Article
     */
    private $article;


    /**
     * Set user
     *
     * @param \BlogBundle\Entity\User $user
     *
     * @return ReportingArticle
     */
    public function setUser(\BlogBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BlogBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set article
     *
     * @param \BlogBundle\Entity\Article $article
     *
     * @return ReportingArticle
     */
    public function setArticle(\BlogBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \BlogBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}
