<?php

namespace App\Service;


use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostsService
{

    /** @var PostsRepository  */
    private $_postsRepository;

    /** @var  UsersRepository */
    private $_userRepository;


    /** @var  PostNotificationService */
    private $_postNotificationService;


    public function __construct(PostsRepository $postsRepository,
                                UsersRepository $userRepository,
                                PostNotificationService $notificationService)
    {
        $this->_postsRepository = $postsRepository;
        $this->_userRepository = $userRepository;
        $this->_postNotificationService = $notificationService;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->_postsRepository->findAll();
    }


    /**
     * @param $data
     * @param null $id
     * @return Post|null|object
     */
    public function createOrUpdate($data, $id = null) {

        if(empty($data) || !isset($data['title'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Missing data");
        }

        if ($id != null) {
          $data = array_merge(['id' => $id], $data);
        }

        $post = $this->_postsRepository->findOrCreateOne($data);
        $post = $this->_postsRepository->createOrUpdate($post, $data);

        if ($id !== null) {
          $this->_postNotificationService->sendUpdateNotification($post);
        }

        return $post;
    }


    /**
     * @param Post $post
     * @param $userId
     */
    public function addSubscriber(Post $post, $userId)
    {
        if(empty($userId)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'No user id provided');
        }

        /** @var User $user */
        $user = $this->_userRepository->find($userId);
        if ($user !== null) {
            $this->_postsRepository->addSubscriber($post, $user);
        }
    }

    /**
     * @param Post $post
     * @param $userId
     */
    public function removeSubscriber(Post $post, $userId)
    {
        if(empty($userId)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'No user id provided');
        }

        /** @var User $user */
        $user = $this->_userRepository->find($userId);
        if ($user !== null) {
            $this->_postsRepository->removeSubscriber($post, $user);
        }
    }

}