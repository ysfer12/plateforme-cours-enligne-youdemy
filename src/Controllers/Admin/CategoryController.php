<?php
namespace App\Controllers\Admin;
use App\Models\Admin\CategoryModel;

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function getCategories() {
        return $this->categoryModel->getAllCategories();
    }

    public function addCategory($data) {
        if (empty($data['nom'])) {
            throw new \Exception("Le nom de la catégorie est requis");
        }

        try {
            return $this->categoryModel->addCategory([
                'nom' => $data['nom'],
                'description' => $data['description'] ?? ''
            ]);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'ajout de la catégorie: " . $e->getMessage());
        }
    }

    public function updateCategory($categoryId, $data) {
        if (!$categoryId || !is_numeric($categoryId)) {
            throw new \Exception("ID de catégorie non valide");
        }

        if (empty($data['nom'])) {
            throw new \Exception("Le nom de la catégorie est requis");
        }

        try {
            return $this->categoryModel->updateCategory($categoryId, [
                'nom' => $data['nom'],
                'description' => $data['description'] ?? ''
            ]);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la mise à jour de la catégorie: " . $e->getMessage());
        }
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