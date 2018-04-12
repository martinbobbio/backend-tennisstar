<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="requestfriend")
 */
class RequestFriend
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