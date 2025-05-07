<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\MethodNotSupportedException;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use DateTime;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    private $user;

    public function create($userData)
    {
        $this->db->insert('user', [
            'user_name' => $userData['user_name'],
            'monthly_income' => $userData['monthly_income'],
            'income_add_time' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    public function get($id)
    {
        $this->user = $this->db
            ->createQueryBuilder()
            ->select('id', 'user_name', 'monthly_income', 'income_add_time')
            ->from('user')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        echo '<pre>';
        var_dump($this->user);
        echo '</pre>';
    }

    public function getAll()
    {
        $users = $this->db
            ->createQueryBuilder()
            ->select('id', 'user_name', 'monthly_income', 'income_add_time')
            ->from('user')
            ->fetchAllAssociative();

        echo '<pre>';
        var_dump($users);
        echo '</pre>';
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
