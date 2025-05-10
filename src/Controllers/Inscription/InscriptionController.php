<?php
namespace App\Controllers\Inscription;

use App\Models\Inscription\InscriptionModel;

class InscriptionController {
    private $model;

    public function __construct() {
        $this->model = new InscriptionModel();
    }

    public function handleCourseEnrollment($userId, $courseId) {
        if ($this->model->isUserEnrolled($userId, $courseId)) {
            header('Location: cours-details.php?id=' . $courseId);
            exit();
        }

        if ($this->model->enrollUserToCourse($userId, $courseId)) {
            header('Location: cours-details.php?id=' . $courseId);
            exit();
        } else {
            header('Location: erreur-inscription.php');
            exit();
        }
    }
}
?>