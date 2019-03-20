<?php

namespace App\Controller\Api;


use App\Entity\Post;
use App\Exception\ControllerExceptionHandler;
use App\Service\PostsService;
use App\Service\TokenService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends AbstractFOSRestController
{
    use ControllerExceptionHandler;

    /** @var  PostsService */
    private $_postsService;

    /** @var  TokenService */
    private $_tokenService;


    public function __construct(
        PostsService $postsService,
        TokenService $tokenService)
    {
        $this->_postsService = $postsService;
        $this->_tokenService = $tokenService;
    }

    /**
     * @Rest\Get("/posts")
     * @return View
     */
    public function index()
    {
        return $this->handleExceptionsIn(function () {
            return $this->view([
                'posts' => $this->_postsService->findAll()
            ]);
        });
    }

    /**
     * @Rest\Put("/posts/{post}")
     * @param Post $post
     * @param Request $request
     */
    public function updatePost(Post $post, Request $request) {
        return $this->handleExceptionsIn(function () use ($post, $request) {
            $postData = $request->request->get('post');

            $this->_postsService->createOrUpdate($postData, $post->getId());
            return $this->view([], Response::HTTP_NO_CONTENT);
        });
    }


    /**
     * @Rest\Put("/posts/{post}/subscribe")
     * @param Post $post
     * @param Request $request
     */
    public function subscribe(Post $post, Request $request) {

        return $this->handleExceptionsIn(function () use ($post, $request) {
            $userId = $request->request->get('user');
            $this->_postsService->addSubscriber($post, $userId);
            $subscriptionToken = $this->_tokenService->generatePostSubscriptionToken($userId);
            $cookie = "mercureAuthorization={$subscriptionToken}; Path=/hub; HttpOnly;";
            return $this->view([], Response::HTTP_OK, [
                'set-cookie' =>  $cookie
            ]);
        });
    }

    /**
     * @Rest\Put("/posts/{post}/unsubscribe")
     * @param Post $post
     * @param Request $request
     */
    public function unsubscribe(Post $post, Request $request) {

        return $this->handleExceptionsIn(function () use ($post, $request) {
            $userId = $request->request->get('user');
            $this->_postsService->removeSubscriber($post, $userId);
            $subscriptionToken = $this->_tokenService->generatePostSubscriptionToken($userId);
            $cookie = "mercureAuthorization={$subscriptionToken}; Path=/hub; HttpOnly;";
            return $this->view([], Response::HTTP_OK, [
                'set-cookie' =>  $cookie
            ]);
        });
    }
}