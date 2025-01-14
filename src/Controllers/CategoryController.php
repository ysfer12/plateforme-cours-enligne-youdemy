<?php
namespace App\Controllers;

use App\Models\CategoryModel;

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function getCategories() {
        return $this->categoryModel->getAllCategories();
    }

    public function deleteCategory($categoryId) {
        if (!$categoryId || !is_numeric($categoryId)) {
            throw new \Exception("ID de catégorie non valide");
        }

        try {
            return $this->categoryModel->deleteCategory($categoryId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCategoryById($categoryId) {
        if (!$categoryId || !is_numeric($categoryId)) {
            throw new \Exception("ID de catégorie non valide");
        }

        $category = $this->categoryModel->getCategoryById($categoryId);
        if (!$category) {
            throw new \Exception("Catégorie non trouvée");
        }

        return $category;
    }

    public function getCourseCount($categoryId) {
        if (!$categoryId || !is_numeric($categoryId)) {
            throw new \Exception("ID de catégorie non valide");
        }

        return $this->categoryModel->getCourseCount($categoryId);
    }
}