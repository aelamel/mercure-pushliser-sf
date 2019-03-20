<?php

namespace App\Controller\Api;


use App\Entity\Post;
use App\Exception\ControllerExceptionHandler;
use App\Service\PostsService;
use App\Service\TokenService;
use App\Service\UsersService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractFOSRestController
{
    use ControllerExceptionHandler;

    /** @var  UsersService */
    private $_usersService;

    /** @var  TokenService */
    private $_tokenService;

    public function __construct(
        UsersService $usersService,
        TokenService $tokenService)
    {
        $this->_usersService = $usersService;
        $this->_tokenService = $tokenService;
    }

    /**
     * @Rest\Get("/users")
     * @return View
     */
    public function index()
    {
        return $this->handleExceptionsIn(function () {
            return $this->view([
                'users' => $this->_usersService->retrieveNotLoggedUsers()
            ]);
        });
    }

    /**
     * @Rest\Post("/users/signin")
     * @param Request $request
     */
    public function signIn(Request $request) {
        return $this->handleExceptionsIn(function () use ($request) {
            $userData = $request->request->get('user');
            $user = $this->_usersService->signin($userData);
            $subscriptionToken = $this->_tokenService->generatePostSubscriptionToken($userData['id']);
            $cookie = "mercureAuthorization={$subscriptionToken}; Path=/hub; HttpOnly;";
            return $this->view([
                'user' => $user
            ], Response::HTTP_OK, [
                'Authorization' => $subscriptionToken,
                'set-cookie' =>  $cookie
            ]);
        });
    }

    /**
     * @Rest\Post("/users/logout")
     * @param Request $request
     */
    public function signOut(Request $request) {
        return $this->handleExceptionsIn(function () use ($request) {
            $userData = $request->request->get('user');
            $user = $this->_usersService->signOut($userData);
            return $this->view([
                'user' => $user
            ], Response::HTTP_OK);
        });
    }

    /**
     * @Rest\Get("/users/{userId}/posts")
     * @param $userId
     */
    public function getUserPosts($userId) {
        return $this->handleExceptionsIn(function () use ($userId) {
            $posts = $this->_usersService->retrieveUserPosts($userId);
            return $this->view([
                'posts' => $posts
            ], Response::HTTP_OK);
        });
    }

}