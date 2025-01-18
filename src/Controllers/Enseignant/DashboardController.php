<?php
namespace App\Controllers\Enseignant;

use App\Models\Enseignant\UserModel;
use App\Models\Enseignant\CourseModel;
class DashboardController {
    private $userModel;
    private $courseModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
    }

    public function index() {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit();
        }

        try {
            $userId = $_SESSION['user_id'];
            $totalCourses = $this->courseModel->getTotalCourses($userId);
            $totalStudents = $this->courseModel->getTotalStudents($userId);
            $popularCourse = $this->courseModel->getPopularCourse($userId);
            
            return [
                'utilisateur' => $this->userModel->getUserInfo($userId),
                'statCours' => $totalCourses,
                'statEtudiants' => $totalStudents,
                'statPopulaires' => $popularCourse,
                'coursRecents' => $this->courseModel->getRecentCourses($userId)
            ];
        } catch (\Exception $e) {
            return [
                'erreur' => $e->getMessage(),
                'statCours' => ['total_cours' => 0],
                'statEtudiants' => ['total_etudiants' => 0],
                'statPopulaires' => ['populaires' => 0],
                'coursRecents' => []
            ];
        }
    }
}
