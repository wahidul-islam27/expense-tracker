<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\MethodNotSupportedException;
use App\Repositories\BaseRepository;
use App\Repositories\ExpenseRepositoryInterface;


class ExpenseRepository extends BaseRepository implements ExpenseRepositoryInterface
{

    public function create($expenseData)
    {
        $this->db->insert('expense', [
            'user_id' => $expenseData['user_id'],
            'category_id' => $expenseData['category_id'],
            'description' => $expenseData['description'],
            'amount' => $expenseData['amount'],
            'expense_date' => $expenseData['expense_date']
        ]);
    }

    public function get($id)
    {
        throw new MethodNotSupportedException('Implementation not supported');
    }

    public function getAll()
    {
        throw new MethodNotSupportedException('Implementation not supported');
    }

    public function update($id, $expenseData)
    {
        return $this->db
            ->createQueryBuilder()
            ->update('expense')
            ->set('description', ':description')
            ->set('amount', ':amount')
            ->set('expense_date', ':expense_date')
            ->where('expense_id = :id')
            ->setParameter('description', $expenseData['description'])
            ->setParameter('amount', $expenseData['amount'])
            ->setParameter('expense_date', $expenseData['expense_date'])
            ->setParameter('id', $id)
            ->executeStatement();
    }

    public function delete($id)
    {
        return $this->db
            ->createQueryBuilder()
            ->delete('expense')
            ->where('expense_id = :id')
            ->setParameter('id', $id)
            ->executeStatement();
    }

    public function getExpenseByCategoryId($categoryId)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('expense_id', 'user_id', 'description', 'amount', 'expense_date')
            ->from('expense')
            ->where('category_id = :category_id')
            ->setParameter('category_id', $categoryId)
            ->fetchAllAssociative();
    }

    public function getExpenseByUserId($userId)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('expense_id', 'category_id', 'description', 'amount', 'expense_date')
            ->from('expense')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->fetchAllAssociative();
    }
}
