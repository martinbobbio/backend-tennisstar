<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="userMatch")
 */
class UserMatch
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
     * @ORM\ManyToOne(targetEntity="Match")
     * @ORM\JoinColumn(name="id_match", referencedColumnName="id")
     */
    private $match;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Score")
     * @ORM\JoinColumn(name="id_score", referencedColumnName="id")
     */
    private $score;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user2", referencedColumnName="id")
     */
    private $user2;

    public function __construct()
    {
        $this->match = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->user2 = new ArrayCollection();
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
     * Set user2
     *
     * @param User $user2
     * @return User
     */
    public function setUser2($user2)
    {
        $this->user2 = $user2;

        return $this;
    }

    /**
     * Get user2
     *
     * @return User 
     */
    public function getUser2()
    {
        return $this->user2;
    }



    /**
     * Set match
     *
     * @param Match $match
     * @return Match
     */
    public function setMatch($match)
    {
        $this->match = $match;

        return $this;
    }

    /**
     * Get match
     *
     * @return Match 
     */
    public function getMatch()
    {
        return $this->match;
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
     * @var string
     * @ORM\Column(name="finish", type="boolean", nullable=true)
     */
    private $finish;


    /**
     * Set finish
     *
     * @param string $finish
     * @return User
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * Get finish
     *
     * @return string 
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * @var string
     * @ORM\Column(name="win", type="boolean", nullable=true)
     */
    private $win;


    /**
     * Set win
     *
     * @param string $win
     * @return User
     */
    public function setWin($win)
    {
        $this->win = $win;

        return $this;
    }

    /**
     * Get win
     *
     * @return string 
     */
    public function getWin()
    {
        return $this->win;
    }





     

}