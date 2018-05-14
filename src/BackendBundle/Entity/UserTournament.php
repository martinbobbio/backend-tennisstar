<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="userTournament")
 */
class UserTournament
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
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
     *
     * @ORM\ManyToOne(targetEntity="Tournament")
     * @ORM\JoinColumn(name="id_tournament", referencedColumnName="id")
     */
    private $tournament;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Score")
     * @ORM\JoinColumn(name="id_score", referencedColumnName="id")
     */
    private $score;

    /**
     * @var string
     * @ORM\Column(name="instance", type="string", length=30, nullable=true)
     */
    private $instance;

    /**
     * @var string
     * @ORM\Column(name="win", type="boolean", nullable=true)
     */
    private $win;

    public function __construct()
    {
        $this->match = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->score = new ArrayCollection();
    }


    /**
     * Set user
     *
     * @param User $user
     * @return User
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tournament
     *
     * @param Tournament $tournament
     * @return Tournament
     */
    public function setTournament($tournament)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return Tournament 
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Set score
     *
     * @param Score $score
     * @return Score
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return Score 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set instance
     *
     * @param string $instance
     * @return string
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return string 
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set win
     *
     * @param boolean $win
     * @return boolean
     */
    public function setWin($win)
    {
        $this->win = $win;

        return $this;
    }

    /**
     * Get win
     *
     * @return boolean 
     */
    public function getWin()
    {
        return $this->win;
    }

     

}