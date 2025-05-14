<?php

declare(strict_types=1);


namespace App\Controllers;

use App\Services\CategoryService;
use App\View;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService)
    {
        parent::__construct();
    }


    public function createCategory()
    {

        $this->init();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryName = trim($_POST['category_name'] ?? '');

            if (!empty($categoryName)) {
                $categoryObj = [
                    "category_name" => $categoryName
                ];
                $this->categoryService->createCategory($categoryObj);
                header('Location: /expense-tracker/public/category');
                exit;
            }
        }

        $categories = $this->categoryService->getAll();

        echo View::make('category', ["categories" => $categories]);
    }

    public function delete()
    {
        $this->init();
        try {
            $id = $_GET['id'];
            $this->categoryService->deleteCategory($id);
            header('Location: /expense-tracker/public/category');
        } catch (\Exception $e) {
            $error = 'Internal Server Error';
        }
    }

    public function update()
    {
        $this->init();
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $categoryName = trim($_POST['category_name'] ?? '');

                if (!empty($categoryName)) {
                    $categoryObj = [
                        "category_name" => $categoryName
                    ];
                    $this->categoryService->createCategory($categoryObj);
                    // Redirect to avoid form resubmission
                    header('Location: /expense-tracker/public/category');
                    exit;
                }
            }

            $id = $_GET['id'];

            $categories = $this->categoryService->getAll();
            $editCategory = array_filter($categories, function ($category, $id) {
                if ($category->getId() === $id) {
                    return $category;
                }

                return null;
            });

            echo View::make('category', ["categories" => $categories, "editCategory" => $editCategory]);
        } catch (\Exception $e) {
        }
    }

    private function init()
    {
        if (empty($this->loggedInUserId)) {
            die('Access Denied');
        }

        if ($this->loggedInUserId[1] != 'admin') {
            die('Access Denied');
        }
    }
}
