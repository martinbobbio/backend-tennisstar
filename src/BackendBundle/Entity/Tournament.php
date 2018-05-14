<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="tournament")
 */
class Tournament
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

     public $players;
     public function setPlayers($players)
     {
         $this->players = $players;
 
         return $this;
     }
     public function getPlayers()
     {
         return $this->players;
     }
     
     /**
     * @var string
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

     /**
     * Set title
     *
     * @param string $title
     * @return Tournament
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @var string
     * @ORM\Column(name="lat", type="float", length=20, nullable=true)
     */
    private $lat;

     /**
     * Set lat
     *
     * @param string $lat
     * @return Tournament
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }


    /**
     * @var string
     * @ORM\Column(name="lon", type="float", length=20, nullable=true)
     */
    private $lon;

     /**
     * Set lon
     *
     * @param string $lon
     * @return Tournament
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return string 
     */
    public function getLon()
    {
        return $this->lon;
    }


    /**
     * @var string
     * @ORM\Column(name="status", type="integer", length=2, nullable=false)
     */
    private $status;

     /**
     * Set status
     *
     * @param string $status
     * @return Tournament
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @var string
     * @ORM\Column(name="count", type="integer", length=3, nullable=false)
     */
    private $count;

     /**
     * Set count
     *
     * @param string $count
     * @return Tournament
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return string 
     */
    public function getCount()
    {
        return $this->count;
    }


    /**
     * @var string
     * @ORM\Column(name="google_place_id", type="string", length=255, nullable=true)
     */
    private $google_place_id;

     /**
     * Set google_place_id
     *
     * @param string $google_place_id
     * @return Tournament
     */
    public function setGooglePlaceId($google_place_id)
    {
        $this->google_place_id = $google_place_id;

        return $this;
    }

    /**
     * Get google_place_id
     *
     * @return string 
     */
    public function getGooglePlaceId()
    {
        return $this->google_place_id;
    }


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="matchs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $creator;

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator(User $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @ORM\Column(name="date_tournament", type="datetime")
     */
    private $dateTournament;

    public function getDateTournament()
    {
        return $this->dateTournament;
    }
    public function setDateTournament($dateTournament)
    {
        $this->dateTournament = $dateTournament;

        return $this;
    }

    public $dateHour;

    public function getDateHour()
    {
        return $this->dateHour;
    }
    public function setDateHour($dateHour)
    {
        $this->dateHour = $dateHour;

        return $this;
    }



   /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;
    
   /**
   * @var \DateTime
   *
   * @ORM\Column(name="update_at", type="datetime")
   */
   private $updateAt;

   /**
    * Set createAt
    *
    * @param \DateTime $createAt
    * @return Shows
    */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime 
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     * @return Artistas
     * @ORM\PreUpdate 
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime 
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
    * @ORM\PrePersist
    */
    public function setCreateAtValue(){
        $this->createAt = new \DateTime();
    }
    
    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function setUpdateAtValue(){
        $this->updateAt = new \DateTime();
    }
    





}