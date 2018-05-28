<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="score")
 */
class Score
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
     * @var string
     * @ORM\Column(name="status", type="integer", length=2, nullable=true)
     */
    private $status;
    
    /**
     * Set status
     *
     * @param string $status
     * @return Match
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
     * @ORM\Column(name="first_set_J1", type="integer", length=2, nullable=true)
     */
    private $first_set_j1;
    
    /**
     * Set first_set_j1
     *
     * @param string $first_set_j1
     * @return Score
     */
    public function setFirstSetJ1($first_set_j1)
    {
        $this->first_set_j1 = $first_set_j1;

        return $this;
    }

    /**
     * Get first_set_J1
     *
     * @return string 
     */
    public function getFirstSetJ1()
    {
        return $this->first_set_j1;
    }

    /**
     * @var string
     * @ORM\Column(name="first_set_J2", type="integer", length=2, nullable=true)
     */
    private $first_set_j2;
    
    /**
     * Set first_set_j2
     *
     * @param string $first_set_j2
     * @return Score
     */
    public function setFirstSetJ2($first_set_j2)
    {
        $this->first_set_j2 = $first_set_j2;

        return $this;
    }

    /**
     * Get first_set_j2
     *
     * @return string 
     */
    public function getFirstSetJ2()
    {
        return $this->first_set_j2;
    }

    


    /**
     * @var string
     * @ORM\Column(name="second_set_J1", type="integer", length=2, nullable=true)
     */
    private $second_set_j1;


    /**
     * Set second_set_j1
     *
     * @param string $second_set_j1
     * @return Score
     */
    public function setSecondSetJ1($second_set_j1)
    {
        $this->second_set_j1 = $second_set_j1;

        return $this;
    }

    /**
     * Get second_set_j1
     *
     * @return string 
     */
    public function getSecondSetJ1()
    {
        return $this->second_set_j1;
    }

    /**
     * @var string
     * @ORM\Column(name="second_set_J2", type="integer", length=2, nullable=true)
     */
    private $second_set_j2;
    
    
        /**
         * Set second_set_j2
         *
         * @param string $second_set_j2
         * @return Score
         */
        public function setSecondSetJ2($second_set_j2)
        {
            $this->second_set_j2 = $second_set_j2;
    
            return $this;
        }
    
        /**
         * Get second_set_j2
         *
         * @return string 
         */
        public function getSecondSetJ2()
        {
            return $this->second_set_j2;
        }


    /**
     * @var string
     * @ORM\Column(name="third_set_J1", type="integer", length=2, nullable=true)
     */
    private $third_set_j1;


    /**
     * Set second_set_j1
     *
     * @param string $second_set_j1
     * @return Score
     */
    public function setThirdSetJ1($third_set_j1)
    {
        $this->third_set_j1 = $third_set_j1;

        return $this;
    }

    /**
     * Get third_set_j1
     *
     * @return string 
     */
    public function getThirdSetJ1()
    {
        return $this->third_set_j1;
    }


/**
     * @var string
     * @ORM\Column(name="third_set_J2", type="integer", length=2, nullable=true)
     */
    private $third_set_j2;
    
    
/**
     * Set second_set_j2
     *
     * @param string $second_set_j2
     * @return Score
     */
     public function setThirdSetJ2($third_set_j2)
        {
            $this->third_set_j2 = $third_set_j2;
    
            return $this;
        }
    
        /**
         * Get third_set_j2
         *
         * @return string 
         */
        public function getThirdSetJ2()
        {
            return $this->third_set_j2;
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