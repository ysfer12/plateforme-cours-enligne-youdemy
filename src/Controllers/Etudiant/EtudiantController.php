<?php
namespace App\Controllers\Etudiant;

use App\Models\Etudiant\EtudiantModel;

class EtudiantController{
    private $db;
    private $etudiantModel;

    public function __construct($db) {
        $this->db = $db;
        $this->etudiantModel = new EtudiantModel($db);
    }

    public function getDashboardData($userId) {
        try {
            $studentInfo = $this->etudiantModel->getStudentInfo($userId);
            $courses = $this->etudiantModel->getStudentCourses($userId);
            $statistics = $this->etudiantModel->getStatistics($userId);

            return [
                'user' => $studentInfo,
                'courses' => $courses,
                'statistics' => $statistics,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'user' => null,
                'courses' => [],
                'statistics' => [],
                'error' => "Erreur : " . $e->getMessage()
            ];
        }
    }
}