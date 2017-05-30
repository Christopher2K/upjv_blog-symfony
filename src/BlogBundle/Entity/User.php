<?php

namespace BlogBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;


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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /*
     * FROM USER INTERFACE
     */

    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        return '';
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->password) = unserialize($serialized);
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $readArticles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->readArticles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add role
     *
     * @param \BlogBundle\Entity\Role $role
     *
     * @return User
     */
    public function addRole(\BlogBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \BlogBundle\Entity\Role $role
     */
    public function removeRole(\BlogBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Add readArticle
     *
     * @param \BlogBundle\Entity\Article $readArticle
     *
     * @return User
     */
    public function addReadArticle(\BlogBundle\Entity\Article $readArticle)
    {
        $this->readArticles[] = $readArticle;

        return $this;
    }

    /**
     * Remove readArticle
     *
     * @param \BlogBundle\Entity\Article $readArticle
     */
    public function removeReadArticle(\BlogBundle\Entity\Article $readArticle)
    {
        $this->readArticles->removeElement($readArticle);
    }

    /**
     * Get readArticles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReadArticles()
    {
        return $this->readArticles;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $writenArticles;


    /**
     * Add writenArticle
     *
     * @param \BlogBundle\Entity\Article $writenArticle
     *
     * @return User
     */
    public function addWritenArticle(\BlogBundle\Entity\Article $writenArticle)
    {
        $this->writenArticles[] = $writenArticle;

        return $this;
    }

    /**
     * Remove writenArticle
     *
     * @param \BlogBundle\Entity\Article $writenArticle
     */
    public function removeWritenArticle(\BlogBundle\Entity\Article $writenArticle)
    {
        $this->writenArticles->removeElement($writenArticle);
    }

    /**
     * Get writenArticles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWritenArticles()
    {
        return $this->writenArticles;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $themes;


    /**
     * Add theme
     *
     * @param \BlogBundle\Entity\UserTheme $theme
     *
     * @return User
     */
    public function addTheme(\BlogBundle\Entity\UserTheme $theme)
    {
        $this->themes[] = $theme;

        return $this;
    }

    /**
     * Remove theme
     *
     * @param \BlogBundle\Entity\UserTheme $theme
     */
    public function removeTheme(\BlogBundle\Entity\UserTheme $theme)
    {
        $this->themes->removeElement($theme);
    }

    /**
     * Get themes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Return the username
     *
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }
}
