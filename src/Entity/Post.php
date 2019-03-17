<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Post
 * @package App\Entity
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\Repository\PostsRepository")
 */
class Post
{
    use EntityHelper;

    /**
     * @var  integer
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var  string
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity="User", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="post_user")
     */
    private $subscribers;

    /**
     * @var  \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;
    /**
     * @var  \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * @param mixed $subscribers
     */
    public function setSubscribers($subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * @param User $user
     */
    public function addSubscriber(User $user)
    {
        if (!$this->subscribers->contains($user)) {
            $this->subscribers->add($user);
        }
    }

    public function deleteSubscriber($user)
    {
        if ($this->subscribers->contains($user)) {
            $this->subscribers->removeElement($user);
        }
    }
}