<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Repositories\UserRepository;
use App\Entities\User;

class UserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function createUser(array $user)
    {
        ServiceUtils::validateUser($user);

        try {
            $this->userRepository->create($user);
        } catch (\Exception $e) {
            throw new BusinessException("SE-500", $e->getMessage());
        }
    }

    public function getAllUser()
    {
        $usersObjects = null;
        $users = [];
        try {
            $usersObjects = $this->userRepository->getAll() ?? [];

            if ($usersObjects == null || sizeof($usersObjects) == 0) {
                throw new BusinessException('BE-404', 'Not User Found');
            }


            foreach ($usersObjects as $userObject) {
                $users[] = ServiceUtils::mapUserObjectToUserEntity($userObject);
            }
        } catch (\Exception $e) {
            throw new BusinessException("SE-500", $e->getMessage());
        }

        return $users;
    }

    public function getUserById($id): User
    {
        $userObject = null;

        try {
            $userObject = $this->userRepository->get($id) ?? [];

            if ($userObject == null || sizeof($userObject) == 0) {
                throw new BusinessException('BE-404', 'Not User Found');
            }

            $user = ServiceUtils::mapUserObjectToUserEntity($userObject);
        } catch (\Exception $e) {
            throw new BusinessException("SE-500", $e->getMessage());
        }

        return $user;
    }

    public function deleteUser($id): void
    {
        $existingUser = $this->userRepository->get($id) ?? [];

        if ($existingUser == null) {
            throw new BusinessException('BE-404', 'User not found');
        }

        try {
            $this->userRepository->delete($id);
        } catch (\Exception $e) {
            throw new BusinessException("SE-500", $e->getMessage());
        }
    }
}
