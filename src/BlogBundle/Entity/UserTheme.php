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
    /**
     * @var \BlogBundle\Entity\User
     */
    private $user;

    /**
     * @var \BlogBundle\Entity\Theme
     */
    private $theme;


    /**
     * Set user
     *
     * @param \BlogBundle\Entity\User $user
     *
     * @return UserTheme
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
     * Set theme
     *
     * @param \BlogBundle\Entity\Theme $theme
     *
     * @return UserTheme
     */
    public function setTheme(\BlogBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \BlogBundle\Entity\Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }
}
