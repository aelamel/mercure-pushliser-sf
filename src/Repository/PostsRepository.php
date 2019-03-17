<?php

namespace App\Repository;


use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PostsRepository extends ServiceEntityRepository
{
    private $key = ['id'];

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Post::class);
    }

    public function findOrCreateOne($data) {
        $criteria = array_intersect($this->key, $data);

        $entity = $this->findOneBy($criteria);
        if ($entity == null) {
            $entity = new Post();
        }
        return $entity;
    }

    /**
     * @param Post $post
     * @param $data
     * @return Post
     */
    public function createOrUpdate(Post $post, $data)
    {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $post->setValue($key, $value);
            }
        }
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();

        return $post;
    }


    /**
     * @param Post $post
     * @param User $user
     * @return Post
     */
    public function addSubscriber(Post $post, User $user)
    {
        $post->addSubscriber($user);

        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();

        return $post;
    }


    /***
     * @param Post $post
     * @param User $user
     * @return Post
     */
    public function removeSubscriber(Post $post, User $user)
    {
        $post->deleteSubscriber($user);

        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();

        return $post;
    }
}