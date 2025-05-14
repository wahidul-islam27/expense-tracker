<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Repositories\UserRepository;
use App\Entities\User;
use App\Exceptions\SystemException;
use App\Security\JWTUtils;
use App\Services\ServiceUtils;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function loginUser($uesrname, $password): bool
    {
        $userObject = $this->userRepository->getByUsernameAndPassword($uesrname, $password) ?? [];

        if (empty($userObject)) {
            return false;
        }

        $token = JWTUtils::generateJwtToken($userObject);
        ServiceUtils::setCookieValue($token);
        
        return true;
    }

    public function createUser(array $user)
    {
        ServiceUtils::validateUser($user);

        if ($this->checkIfUserIsAdmin($user['user_name'])) {
            $user['role'] = 'admin';
        }

        try {
            $this->userRepository->create($user);
        } catch (\Exception $e) {
            throw new BusinessException(500, $e->getMessage());
        }
    }

    public function updateMonthlyIncome($userObject)
    {
        ServiceUtils::validateUser($userObject);

        $user = $this->userRepository->getByUsernameAndPassword($userObject['user_name'], $userObject['password']) ?? [];

        if (empty($user)) {
            throw new BusinessException(400, 'Password is Incorrect');
        }

        try {
            $this->userRepository->updateMonthlyIncome($user['id'], $userObject['monthly_income']);
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }
    }

    public function updatePassword($username, $password)
    {
        try {
            $this->userRepository->updatePassword($username, $password);
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }
    }

    public function getAllUser()
    {
        $usersObjects = null;
        $users = [];
        try {
            $usersObjects = $this->userRepository->getAll() ?? [];

            if ($usersObjects == null || sizeof($usersObjects) == 0) {
                throw new BusinessException(404, 'Not User Found');
            }


            foreach ($usersObjects as $userObject) {
                $users[] = ServiceUtils::mapUserObjectToUserEntity($userObject);
            }
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }

        return $users;
    }

    public function getUserById($id): User
    {
        $userObject = null;

        try {
            $userObject = $this->userRepository->get($id) ?? [];

            if ($userObject == null || sizeof($userObject) == 0) {
                throw new BusinessException(404, 'Not User Found');
            }

            $user = ServiceUtils::mapUserObjectToUserEntity($userObject);
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }

        return $user;
    }

    public function deleteUser($id): void
    {
        $existingUser = $this->userRepository->get($id) ?? [];

        if ($existingUser == null) {
            throw new BusinessException(404, 'User not found');
        }

        try {
            $this->userRepository->delete($id);
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }
    }

    private function checkIfUserIsAdmin($email)
    {
        if (strtolower(substr($email, 0, 5)) === 'admin') {
            return true;
        }
        return false;
    }
}
