<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="requestmatch")
 */
class RequestMatch
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getId(){

        return $this->id;

    }


    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_send", referencedColumnName="id")
     */
    private $user_send;

    /**
     * Set user_send
     *
     * @param User $user_send
     * @return User
     */
    public function setUserSend($user_send)
    {
        $this->user_send = $user_send;

        return $this;
    }

    /**
     * Get user_send
     *
     * @return User 
     */
    public function getUserSend()
    {
        return $this->user_send;
    }



    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_receive", referencedColumnName="id")
     */
    private $user_receive;

    /**
     * Set user_receive
     *
     * @param User $user_receive
     * @return User
     */
    public function setUserReceive($user_receive)
    {
        $this->user_receive = $user_receive;

        return $this;
    }

    /**
     * Get user_receive
     *
     * @return User 
     */
    public function getUserReceive()
    {
        return $this->user_receive;
    }


    /**
     * @var string
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;


    /**
     * Set status
     *
     * @param string $status
     * @return User
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
     * @ORM\Column(name="google_place_id", type="string", length=255, nullable=true)
     */
    private $google_place_id;

     /**
     * Set google_place_id
     *
     * @param string $google_place_id
     * @return Match
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
     * @ORM\Column(name="date_match", type="datetime")
     */
    private $dateMatch;

    public function getDateMatch()
    {
        return $this->dateMatch;
    }
    public function setDateMatch($dateMatch)
    {
        $this->dateMatch = $dateMatch;

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
     * @var string
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

     /**
     * Set title
     *
     * @param string $title
     * @return Match
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
    * @return PlayerUser
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
     * @return PlayerUser
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