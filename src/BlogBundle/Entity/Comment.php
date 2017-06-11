<?php

namespace BlogBundle\Entity;

/**
 * Comment
 */
class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $note;


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
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Comment
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }
    /**
     * @var \BlogBundle\Entity\User
     */
    private $author;

    /**
     * @var \BlogBundle\Entity\Article
     */
    private $article;


    /**
     * Set author
     *
     * @param \BlogBundle\Entity\User $author
     *
     * @return Comment
     */
    public function setAuthor(\BlogBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \BlogBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set article
     *
     * @param \BlogBundle\Entity\Article $article
     *
     * @return Comment
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

    /**
     * Set an initial value at the comment creation
     *
     * @return void
     */
    public function setCreatedAtInitialValue()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Set an initial value at each persistence of this comment
     *
     * @return void
     */
    public function setModifedAtInitialValue()
    {
        $this->modifiedAt = new \DateTime('now');
    }
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $modifiedAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return Comment
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $reportings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reportings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reporting
     *
     * @param \BlogBundle\Entity\ReportingComment $reporting
     *
     * @return Comment
     */
    public function addReporting(\BlogBundle\Entity\ReportingComment $reporting)
    {
        $this->reportings[] = $reporting;

        return $this;
    }

    /**
     * Remove reporting
     *
     * @param \BlogBundle\Entity\ReportingComment $reporting
     */
    public function removeReporting(\BlogBundle\Entity\ReportingComment $reporting)
    {
        $this->reportings->removeElement($reporting);
    }

    /**
     * Get reportings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReportings()
    {
        return $this->reportings;
    }
}
