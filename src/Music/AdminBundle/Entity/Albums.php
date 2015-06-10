<?php

namespace Music\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Albums
 *
 * @ORM\Table(name="dim_albums")
 * @ORM\Entity(repositoryClass="Music\AdminBundle\Entity\Repository\AlbumsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Albums
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
     * @ORM\Column(name="group_name", type="string")
     * @Assert\Length(min = 3, minMessage = "Min {{ limit }} characters long")
     */
    private $group_name;

    /**
     * @ORM\Column(name="track_name", type="string")
     */
    private $track_name;

    /**
     * @ORM\Column(name="file_name", type="string")
     */
    private $file_name;


    /**
     * @ORM\Column(name="file_psevdo", type="string")
     */
    private $file_psevdo;

    /**
     * @ORM\Column(name="category_id", type="integer")
     */
    private $category_id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="albums")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;


    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;


    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;


    public function __construct(){
        $this->setCreatedAt(new \DateTime());
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
     * Set group_name
     *
     * @param string $groupName
     * @return Albums
     */
    public function setGroupName($groupName)
    {
        $this->group_name = $groupName;

        return $this;
    }

    /**
     * Get group_name
     *
     * @return string 
     */
    public function getGroupName()
    {
        return $this->group_name;
    }

    /**
     * Set track_name
     *
     * @param string $trackName
     * @return Albums
     */
    public function setTrackName($trackName)
    {
        $this->track_name = $trackName;

        return $this;
    }

    /**
     * Get track_name
     *
     * @return string 
     */
    public function getTrackName()
    {
        return $this->track_name;
    }


    /**
     * Set file_name
     *
     * @param string $fileName
     * @return Albums
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set file_psevdo
     *
     * @param string $filePsevdo
     * @return Albums
     */
    public function setFilePsevdo($filePsevdo)
    {
        $this->file_psevdo = $filePsevdo;

        return $this;
    }

    /**
     * Get file_psevdo
     *
     * @return string 
     */
    public function getFilePsevdo()
    {
        return $this->file_psevdo;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Albums
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(){
        $this->created_at = new \DateTime();
    }

//    /**
//     * @ORM\PreUpdate
//     */
//    public function setUpdatedAtValue(){
//        $this->updated_at = new DateTime();
//    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Albums
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set category_id
     *
     * @param integer $categoryId
     * @return Albums
     */
    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get category_id
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set category
     *
     * @param \Music\AdminBundle\Entity\Category $category
     * @return Albums
     */
    public function setCategory(\Music\AdminBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Music\AdminBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
