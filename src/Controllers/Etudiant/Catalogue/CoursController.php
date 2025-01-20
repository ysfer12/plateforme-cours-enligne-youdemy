<?php
namespace App\Controllers\Etudiant\Catalogue;

use App\Models\Etudiant\Catalogue\CoursModel;

class CoursController {
    private $coursModel;

    public function __construct() {
        $this->coursModel = new CoursModel();
    }

    public function index($userId = null, $filters = []) {
        $search = $filters['search'] ?? '';
        $categoryId = $filters['category'] ?? 0;
        $page = max(1, $filters['page'] ?? 1);
        $coursesPerPage = 9;

        $totalCourses = $this->coursModel->countCourses($search, $categoryId);
        $totalPages = ceil($totalCourses / $coursesPerPage);

        $categories = $this->coursModel->getCategories();

        $courses = $this->coursModel->getCourses(
            $userId, 
            $search, 
            $categoryId, 
            $page, 
            $coursesPerPage
        );

        return [
            'courses' => $courses,
            'categories' => $categories,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'search' => $search,
            'categoryId' => $categoryId,
            'totalCourses' => $totalCourses
        ];
    }

    public function filtrerCours($userId = null, $search = '', $categoryId = 0) {
        $search = trim($search);
        $categoryId = (int)$categoryId;

        return $this->coursModel->getCourses($userId, $search, $categoryId);
    }

    public function detailsCours($coursId, $userId = null) {
        $coursId = (int)$coursId;
        $cours = $this->coursModel->getCoursById($coursId, $userId);

        return $cours;
    }

  
    public function inscrireCours($coursId, $userId) {
        $coursId = (int)$coursId;
        $userId = (int)$userId;

        return $this->coursModel->inscrireUtilisateur($coursId, $userId);
    }

    public function coursRecommandes($userId = null, $limit = 3) {
        return $this->coursModel->getCoursRecommandes($userId, $limit);
    }

    private function validateFilters($filters) {
        $validatedFilters = [
            'search' => isset($filters['search']) ? trim($filters['search']) : '',
            'category' => isset($filters['category']) ? (int)$filters['category'] : 0,
            'page' => isset($filters['page']) ? max(1, (int)$filters['page']) : 1
        ];

        return $validatedFilters;
    }
}