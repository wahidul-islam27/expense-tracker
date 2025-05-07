<?php

declare(strict_types=1);

namespace App\Repositories;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function create($categoryData)
    {

        $this->db
            ->insert('category', [
                'category_name' => $categoryData['category_name']
            ]);
    }

    public function get($id)
    {
        return $this->db->createQueryBuilder()
            ->select('category_id', 'category_name')
            ->from('category')
            ->where('category_id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();
    }

    public function getAll()
    {
        return $this->db->createQueryBuilder()
            ->select('category_id', 'category_name')
            ->from('category')
            ->fetchAllAssociative();
    }

    public function update($id, $categoryData) {
        return $this->db->createQueryBuilder()
                ->update('category')
                ->set('category_name', ':category_name')
                ->where('category_id = :id')
                ->setParameter('category_name', $categoryData['category_name'])
                ->setParameter('id', $id)
                ->executeStatement();
    }

    public function delete($id) {
        return $this->db
            ->createQueryBuilder()
            ->delete('category')
            ->where('category_id = :id')
            ->setParameter('id', $id)
            ->executeStatement();
    }
}
