<?php
namespace App\Controllers\Inscription;

use App\Models\Inscription\InscriptionModel;

class InscriptionController {
    private $model;

    public function __construct() {
        $this->model = new InscriptionModel();
    }

    public function handleCourseEnrollment($userId, $courseId) {
        // Vérifier si l'utilisateur est déjà inscrit
        if ($this->model->isUserEnrolled($userId, $courseId)) {
            header('Location: cours-details.php?id=' . $courseId);
            exit();
        }

        // Tenter l'inscription
        if ($this->model->enrollUserToCourse($userId, $courseId)) {
            header('Location: cours-details.php?id=' . $courseId);
            exit();
        } else {
            // Gérer l'erreur d'inscription (peut-être rediriger vers une page d'erreur)
            header('Location: erreur-inscription.php');
            exit();
        }
    }
}
?>