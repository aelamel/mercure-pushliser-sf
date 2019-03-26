<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TokenService
{

    /** @var JwtGenerator */
    private $_tokenGenerator;

    /** @var  UsersRepository */
    private $_userRepository;


    public function __construct(
        JwtGenerator $jwtGenerator,
        UsersRepository $usersRepository)
    {
        $this->_tokenGenerator = $jwtGenerator;
        $this->_userRepository = $usersRepository;
    }

    /**
     * @param $userId
     * @return string
     */
    public function generatePostSubscriptionToken($userId) {
        /** @var User $user */
        $user = $this->_userRepository->find($userId);
        if ($user) {
            $subscriptionToken = $this->_tokenGenerator->generateNotificationToken($user->getPosts());
            $cookie = "mercureAuthorization={$subscriptionToken}; Path=/hub; HttpOnly;";
            return [$subscriptionToken, $cookie];
        } else {
            throw new HttpException(Response::HTTP_NOT_FOUND, "No User found");
        }
    }
}