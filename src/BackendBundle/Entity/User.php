<?php

namespace BackendBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * 
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="BackOfficeBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(){

        return $this->id;

    }


     /**
     * @var string
     * @Assert\NotBlank() 
     * @ORM\Column(name="firstname", type="string", length=45, nullable=false)
     */
    private $firstname;


    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Users
     */
    public function setfirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getfirstname()
    {
        return $this->firstname;
    }


    /**
     * @var string
     * @Assert\NotBlank() 
     * @ORM\Column(name="lastname", type="string", length=45, nullable=false)
     */
    private $lastname;


    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setlastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getlastname()
    {
        return $this->lastname;
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Users
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");

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
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Order
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = new \DateTime("now");

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    protected $email;
    
    /**
     * Overridden so that username is now optional
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->setUsername($email);
        return parent::setEmail($email);
    }



    /**
     * Get lastName
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }







}