<?php

namespace BackendBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\PlayerUser;
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
     * @ORM\OneToOne(targetEntity="PlayerUser")
     * @ORM\JoinColumn(name="playerUser_id", referencedColumnName="id")
     */
    private $playerUser;

    /**
     * Set playerUser
     *
     * @param PlayerUser $playerUser
     * @return User
     */
    public function setPlayerUser($playerUser)
    {
        $this->playerUser = $playerUser;

        return $this;
    }

    /**
     * Get playerUser
     *
     * @return User 
     */
    public function getPlayerUser()
    {
        return $this->playerUser;
    }



    /**
     * @ORM\OneToOne(targetEntity="SkillUser")
     * @ORM\JoinColumn(name="skillUser_id", referencedColumnName="id")
     */
    private $skillUser;

    /**
     * Set skillUser
     *
     * @param PlayerUser $skillUser
     * @return User
     */
    public function setSkillUser($skillUser)
    {
        $this->skillUser = $skillUser;

        return $this;
    }

    /**
     * Get skillUser
     *
     * @return User 
     */
    public function getSkillUser()
    {
        return $this->skillUser;
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
        $this->email = $email;
        
        return $this;
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



    protected $username;
    
    /**
     * Overridden so that username is now optional
     *
     * @param string $username
     * @return User
     */
    public function setUsername1($username)
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

    public function getName(){
        return $this->playerUser->getFirstname();
    }




}