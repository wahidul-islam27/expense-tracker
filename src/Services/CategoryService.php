<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Entities\Category;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;

class CategoryService
{
    private $categoryRepo;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
    }

    public function createCategory($categoryData): void
    {
        ServiceUtils::validateCategory($categoryData);

        try {
            $this->categoryRepo->create($categoryData);
        } catch (\Exception $e) {
            throw new SystemException("SE-500", $e->getMessage());
        }
    }

    public function getCategory($id): Category
    {
        try {
            $categoryObj = $this->categoryRepo->get($id);

            $category = ServiceUtils::mapCategoryObjectToCategoryEntity($categoryObj);
        } catch (\Exception $e) {
            throw new SystemException("SE-500", $e->getMessage());
        }

        return $category;
    }

    public function getAll(): array
    {
        $categories = [];

        try {
            $categoryObjs = $this->categoryRepo->getAll() ?? [];

            if (sizeof($categoryObjs) == 0) {
                throw new BusinessException('BE-400', 'No Category found');
            }

            foreach ($categoryObjs as $categoryObj) {
                $categories[] = ServiceUtils::mapCategoryObjectToCategoryEntity($categoryObj);
            }
        } catch (\Exception $e) {
            throw new SystemException("SE-500", $e->getMessage());
        }

        return $categories;
    }

    public function updateCategory($id, $categoryObj): void
    {
        ServiceUtils::validateCategory($categoryObj);

        $existingCategory = $this->categoryRepo->get($id) ?? null;

        if ($existingCategory == null) {
            throw new BusinessException('BE-400', 'Category not found');
        }

        try {
            $this->categoryRepo->update($id, $categoryObj);
        } catch (\Exception $e) {
            throw new SystemException("SE-500", $e->getMessage());
        }
    }

    public function deleteCategory($id): void
    {
        $existingCategory = $this->categoryRepo->get($id) ?? null;

        if ($existingCategory == null) {
            throw new BusinessException('BE-400', 'Category not found');
        }

        try {
            $this->categoryRepo->delete($id);
        } catch (\Exception $e) {
            throw new SystemException("SE-500", $e->getMessage());
        }
    }
}
