<?php
namespace App\Controllers\Catalogue;
use App\Models\Catalogue\CoursModel;

class CoursController {
    private $model;
    private $coursParPage = 6;

    public function __construct(CoursModel $model = null) {
        $this->model = $model ?? new CoursModel();
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $categories = $this->model->getCategories();
        $totalCours = $this->model->getTotalCourses($search, $category);
        $totalPages = ceil($totalCours / $this->coursParPage);
        $cours = $this->model->getCourses($search, $category, $page, $this->coursParPage);

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

    public function getCourseDetails($courseId, $userId = null) {
        return $this->model->getCourseDetails($courseId, $userId);
    }

    public function getSimilarCourses($categoryId, $excludeCourseId, $limit = 3) {
        return $this->model->getSimilarCourses($categoryId, $excludeCourseId, $limit);
    }
}
