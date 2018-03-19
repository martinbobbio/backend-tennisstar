<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="playerUser")
 */
class PlayerUser
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

    public $fileIds;
    

    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=30, nullable=true)
     */
    private $firstname;


    /**
     * Set firstname
     *
     * @param string $firstname
     * @return PlayerUser
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    
    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=30, nullable=true)
     */
    private $lastname;


    /**
     * Set lastname
     *
     * @param string $lastname
     * @return PlayerUser
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }


    /**
     * @var string
     * @ORM\Column(name="age", type="integer", length=3, nullable=true)
     */
    private $age;
    
    
    /**
     * Set age
     *
     * @param string $age
     * @return PlayerUser
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int 
     */
    public function getAge()
    {
        return $this->age;
    }


    /**
     * @var string
     * @ORM\Column(name="img_src", type="string", length=255, nullable=true)
     */
    private $img_src;
    
  /**
  * Set img_src
  *
  * @param string $img_src
  * @return PlayerUser
  */
  public function setImgSrc($img_src)
  {
      $this->img_src = $img_src;

      return $this;
  }

  /**
  * Get img_src
  *
  * @return string 
  */
  public function getImgSrc()
  {
      return $this->img_src;
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