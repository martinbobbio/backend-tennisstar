<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="skillUser")
 */
class SkillUser
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
     * @var string
     * @ORM\Column(name="gameLevel", type="string", length=30, nullable=true)
     */
    private $gameLevel;


    /**
     * Set gameLevel
     *
     * @param string $gameLevel
     * @return PlayerUser
     */
    public function setGameLevel($gameLevel)
    {
        $this->gameLevel = $gameLevel;

        return $this;
    }

    /**
     * Get gameLevel
     *
     * @return string 
     */
    public function getGameLevel()
    {
        return $this->gameLevel;
    }

    
    /**
     * @var string
     * @ORM\Column(name="gameStyle", type="string", length=30, nullable=true)
     */
    private $gameStyle;


    /**
     * Set gameStyle
     *
     * @param string $gameStyle
     * @return PlayerUser
     */
    public function setGameStyle($gameStyle)
    {
        $this->gameStyle = $gameStyle;

        return $this;
    }

    /**
     * Get gameStyle
     *
     * @return string 
     */
    public function getGameStyle()
    {
        return $this->gameStyle;
    }



    /**
     * @var string
     * @ORM\Column(name="typeBackhand", type="string", length=30, nullable=true)
     */
    private $typeBackhand;


    /**
     * Set typeBackhand
     *
     * @param string $typeBackhand
     * @return PlayerUser
     */
    public function setTypeBackhand($typeBackhand)
    {
        $this->typeBackhand = $typeBackhand;

        return $this;
    }

    /**
     * Get typeBackhand
     *
     * @return string 
     */
    public function getTypeBackhand()
    {
        return $this->typeBackhand;
    }



    /**
     * @var string
     * @ORM\Column(name="forehand", type="integer", length=3, nullable=true)
     */
    private $forehand;
    
    
    /**
     * Set forehand
     *
     * @param string $forehand
     * @return PlayerUser
     */
    public function setForehand($forehand)
    {
        $this->forehand = $forehand;

        return $this;
    }

    /**
     * Get forehand
     *
     * @return int 
     */
    public function getForehand()
    {
        return $this->forehand;
    }

    /**
     * @var string
     * @ORM\Column(name="backhand", type="integer", length=3, nullable=true)
     */
    private $backhand;
    
    
    /**
     * Set backhand
     *
     * @param string $backhand
     * @return PlayerUser
     */
    public function setBackhand($backhand)
    {
        $this->backhand = $backhand;

        return $this;
    }

    /**
     * Get backhand
     *
     * @return int 
     */
    public function getBackhand()
    {
        return $this->backhand;
    }


    /**
     * @var string
     * @ORM\Column(name="service", type="integer", length=3, nullable=true)
     */
    private $service;
    
    
    /**
     * Set service
     *
     * @param string $service
     * @return PlayerUser
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return int 
     */
    public function getService()
    {
        return $this->service;
    }


    /**
     * @var string
     * @ORM\Column(name="volley", type="integer", length=3, nullable=true)
     */
    private $volley;
    
    
    /**
     * Set volley
     *
     * @param string $volley
     * @return PlayerUser
     */
    public function setVolley($volley)
    {
        $this->volley = $volley;

        return $this;
    }

    /**
     * Get volley
     *
     * @return int 
     */
    public function getVolley()
    {
        return $this->volley;
    }

    /**
     * @var string
     * @ORM\Column(name="resistence", type="integer", length=3, nullable=true)
     */
    private $resistence;
    
    
    /**
     * Set resistence
     *
     * @param string $resistence
     * @return PlayerUser
     */
    public function setResistence($resistence)
    {
        $this->resistence = $resistence;

        return $this;
    }

    /**
     * Get resistence
     *
     * @return int 
     */
    public function getResistence()
    {
        return $this->resistence;
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