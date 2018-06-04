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



    /**
     * @var string
     * @ORM\Column(name="fullplayer", type="boolean", nullable=true)
     */
    private $fullPlayer;


    /**
     * Set fullPlayer
     *
     * @param string $fullPlayer
     * @return User
     */
    public function setFullPlayer($fullPlayer)
    {
        $this->fullPlayer = $fullPlayer;

        return $this;
    }

    /**
     * Get fullPlayer
     *
     * @return string 
     */
    public function getFullPlayer()
    {
        return $this->fullPlayer;
    }
    
    /**
     * @var string
     * @ORM\Column(name="passwordreset", type="integer", nullable=true)
     */
    private $passwordreset;


    /**
     * Set passwordreset
     *
     * @param string $passwordreset
     * @return User
     */
    public function setPasswordreset($passwordreset)
    {
        $this->passwordreset = $passwordreset;

        return $this;
    }

    /**
     * Get passwordreset
     *
     * @return string 
     */
    public function getPasswordreset()
    {
        return $this->passwordreset;
    }


    /**
     * @var string
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;


    /**
     * Set points
     *
     * @param string $points
     * @return User
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return string 
     */
    public function getPoints()
    {
        return $this->points;
    }

    public function addPoints($pointsNew){
        $this->points += $pointsNew;
    }
    
    public function getLevel(){
        if($this->points > 3000){
            return "Nivel 10 - Leyenda TennisStar (".$this->points." puntos)";
        }else if($this->points > 1500){
            return "Nivel 9 - Campeón TennisStar (".$this->points." puntos)";
        }else if($this->points > 750){
            return "Nivel 8 - Maestro TennisStar (".$this->points." puntos)";
        }else if($this->points > 400){
            return "Nivel 7 - Genio TennisStar (".$this->points." puntos)";
        }else if($this->points > 200){
            return "Nivel 6 - Jugador TennisStar (".$this->points." puntos)";
        }else if($this->points > 100){
            return "Nivel 5 - Promesa TennisStar (".$this->points." puntos)";
        }else if($this->points > 50){
            return "Nivel 4 - Futura promesa TennisStar (".$this->points." puntos)";
        }else if($this->points > 20){
            return "Nivel 3 - Experimentado TennisStar (".$this->points." puntos)";
        }else if($this->points > 5){
            return "Nivel 2 - Aprendiz TennisStar (".$this->points." puntos)";
        }else if($this->points > 0){
            return "Nivel 1 - Principiante TennisStar (".$this->points." puntos)";
        }else if($this->points == null){
            return "Aún no ha participado en ningún partido o torneo";
        }
    }


    /**
     * @var string
     * @ORM\Column(name="fullgame", type="boolean", nullable=true)
     */
    private $fullGame;


    /**
     * Set fullGame
     *
     * @param string $fullGame
     * @return User
     */
    public function setFullGame($fullGame)
    {
        $this->fullGame = $fullGame;

        return $this;
    }

    /**
     * Get fullGame
     *
     * @return string 
     */
    public function getFullGame()
    {
        return $this->fullGame;
    }

    







}