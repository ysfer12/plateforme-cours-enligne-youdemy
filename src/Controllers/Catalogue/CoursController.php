<?php
namespace App\Controllers\Catalogue;
use App\Models\Catalogue\CoursModel;

class CoursController {
    private $model;
    private $coursParPage = 6;

    public function __construct(CoursModel $model) {
        $this->model = $model;
    }

    public function index() {
        // Get search parameters
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get data from model
        $categories = $this->model->getCategories();
        $totalCours = $this->model->getTotalCourses($search, $category);
        $totalPages = ceil($totalCours / $this->coursParPage);
        $cours = $this->model->getCourses($search, $category, $page, $this->coursParPage);

        // Prepare data for view
        $viewData = [
            'search' => $search,
            'category' => $category,
            'categories' => $categories,
            'cours' => $cours,
            'page' => $page,
            'totalPages' => $totalPages,
            'coursParPage' => $this->coursParPage
        ];

        return $viewData;
    }
}
