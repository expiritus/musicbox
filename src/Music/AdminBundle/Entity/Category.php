<?php

namespace Music\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="dim_category")
 * @ORM\Entity(repositoryClass="Music\AdminBundle\Entity\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="category", type="string")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Albums", mappedBy="category")
     */
    protected $albums;

    public function __construct(){
        $this->albums = new ArrayCollection();
    }

    public function __toString(){
        return $this->getCategory() ? $this->getCategory() : "";
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add albums
     *
     * @param \Music\AdminBundle\Entity\Albums $albums
     * @return Category
     */
    public function addAlbum(\Music\AdminBundle\Entity\Albums $albums)
    {
        $this->albums[] = $albums;

        return $this;
    }

    /**
     * Remove albums
     *
     * @param \Music\AdminBundle\Entity\Albums $albums
     */
    public function removeAlbum(\Music\AdminBundle\Entity\Albums $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbums()
    {
        return $this->albums;
    }
}
