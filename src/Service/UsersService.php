<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersService
{
    /** @var  UsersRepository */
    private $_userRepository;


    public function __construct(UsersRepository $userRepository)
    {
        $this->_userRepository = $userRepository;
    }

    /**
     * @return array
     */
    public function retrieveNotLoggedUsers()
    {
        return $this->_userRepository->findBy([
            'loggedIn' => false
        ]);
    }


    /**
     * @param $userData
     * @return User
     */
    public function signin($userData)
    {
        if (empty($userData) && !isset($userData['id'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Missing data");
        }

        /** @var User $user */
        $user = $this->_userRepository->find($userData['id']);
        return $this->_userRepository->update($user, ['loggedIn' => true]);
    }

    /**
     * @param $userData
     * @return User
     */
    public function signOut($userData)
    {
        if (empty($userData) && !isset($userData['id'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Missing data");
        }

        /** @var User $user */
        $user = $this->_userRepository->find($userData['id']);
        return $this->_userRepository->update($user, ['loggedIn' => false]);
    }

}