<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="clubfavorite")
 */
class ClubFavorite
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
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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