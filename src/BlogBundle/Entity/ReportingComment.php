<?php

namespace BlogBundle\Entity;

/**
 * ReportingComment
 */
class ReportingComment
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
     * @var \BlogBundle\Entity\Comment
     */
    private $comment;


    /**
     * Set user
     *
     * @param \BlogBundle\Entity\User $user
     *
     * @return ReportingComment
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
     * Set comment
     *
     * @param \BlogBundle\Entity\Comment $comment
     *
     * @return ReportingComment
     */
    public function setComment(\BlogBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \BlogBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }
}
