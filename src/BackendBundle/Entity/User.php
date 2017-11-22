<?php

namespace BackendBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="user")
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
     * @ORM\Column(name="firstname", type="string", length=45, nullable=true)
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
     * @ORM\Column(name="lastname", type="string", length=45, nullable=true)
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