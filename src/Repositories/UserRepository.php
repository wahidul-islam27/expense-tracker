<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\MethodNotSupportedException;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use DateTime;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function create($userData)
    {
        $this->db->insert('user', [
            'user_name' => $userData['user_name'],
            'monthly_income' => $userData['monthly_income'],
            'password' => $userData['password'],
            'income_add_time' => (new DateTime())->format('Y-m-d H:i:s'),
            'role' => $userData['role']
        ]);
    }

    public function getByUsernameAndPassword($username, $password)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('id', 'user_name', 'monthly_income', 'role')
            ->from('user')
            ->where('user_name = :user_name')
            ->andWhere('password = :password')
            ->setParameter('user_name', $username)
            ->setParameter('password', $password)
            ->fetchAssociative();
    }

    public function get($id)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('id', 'user_name', 'monthly_income', 'income_add_time', 'role')
            ->from('user')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();
    }

    public function updatePassword($username, $password)
    {
        $this->db
            ->createQueryBuilder()
            ->update('user')
            ->set('password', ':password')
            ->where('user_name = :user_name')
            ->setParameter('password', $password)
            ->setParameter('user_name', $username)
            ->executeStatement();
    }

    public function getAll()
    {
        return $this->db
            ->createQueryBuilder()
            ->select('id', 'user_name', 'monthly_income', 'income_add_time', 'role')
            ->from('user')
            ->fetchAllAssociative();
    }

    public function update($id, $userData)
    {
        throw new MethodNotSupportedException("Implementation not allowed");
    }

    public function delete($id)
    {
        $this->db
            ->createQueryBuilder()
            ->delete('user')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->executeStatement();
    }

    public function updateMonthlyIncome($id, $income)
    {
        $this->db
            ->createQueryBuilder()
            ->update('user')
            ->set('monthly_income', ':income')
            ->where('id = :id')
            ->setParameter('income', $income)
            ->setParameter('id', $id)
            ->executeStatement();
    }
}
