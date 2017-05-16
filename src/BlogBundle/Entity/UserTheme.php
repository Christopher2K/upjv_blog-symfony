<?php

namespace BlogBundle\Entity;

/**
 * UserTheme
 */
class UserTheme
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var bool
     */
    private $isReviewer;


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
     * Set isReviewer
     *
     * @param boolean $isReviewer
     *
     * @return UserTheme
     */
    public function setIsReviewer($isReviewer)
    {
        $this->isReviewer = $isReviewer;

        return $this;
    }

    /**
     * Get isReviewer
     *
     * @return bool
     */
    public function getIsReviewer()
    {
        return $this->isReviewer;
    }
}

