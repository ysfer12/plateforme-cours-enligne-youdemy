<?php
namespace App\Controllers\Enseignant;

use App\Models\Enseignant\VideoCours;
use App\Models\Enseignant\DocumentCours;
use App\Models\Enseignant\CourseModel;
use App\Models\Enseignant\UserModel;

class CoursController
{
    private $courseModel;
    private $userModel;
    private $error_messages = [];

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->userModel = new UserModel();
    }

    public function createCourse($titre, $description, $lienContenu, $enseignant_id, $category_id, $typeContenu)
    {
        try {
            if ($typeContenu === 'video') {
                $course = new VideoCours($this->courseModel->getDb(), $titre, $description, $lienContenu, $enseignant_id, $category_id);
            } elseif ($typeContenu === 'document') {
                $course = new DocumentCours($this->courseModel->getDb(), $titre, $description, $lienContenu, $enseignant_id, $category_id);
            } else {
                throw new \Exception("Type de contenu invalide");
            }
    
            $cours_id = $course->createCourse();
    
            if ($cours_id) {
                return $cours_id; 
            } else {
                throw new \Exception("Failed to create course");
            }
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return false;
        }
    }
    public function updateCourse($cours_id, $titre, $description, $lienContenu, $enseignant_id, $category_id, $typeContenu)
    {
        return $this->courseModel->updateCourse($cours_id, $titre, $description, $lienContenu, $enseignant_id, $category_id, $typeContenu);
    }

    public function deleteCourse($cours_id)
    {
        return $this->courseModel->deleteCourse($cours_id);
    }

    public function fetchAllCourses($typeContenu)
    {
        return $this->courseModel->fetchAllCourses($typeContenu);
    }

    public function getCourseById($cours_id, $typeContenu = null)
    {
        return $this->courseModel->getCourseById($cours_id, $typeContenu);
    }

    public function addTagToCourse($cours_id, $tag_id)
    {
        return $this->courseModel->addTagToCourse($cours_id, $tag_id);
    }

    public function removeAllTagsFromCourse($cours_id)
    {
        return $this->courseModel->removeAllTagsFromCourse($cours_id);
    }
    public function getCourseTags($cours_id)
    {
        return $this->courseModel->getCourseTags($cours_id);
    }

    public function getCategories()
    {
        return $this->courseModel->getCategories();
    }

    public function getTags()
    {
        return $this->courseModel->getTags();
    }

    public function getUserInfo($user_id)
    {
        return $this->userModel->getUserInfo($user_id);
    }

    public function getCoursesByTeacher($enseignant_id)
    {
        return $this->courseModel->getCoursesByTeacher($enseignant_id);
    }

    public function getCoursesByCategory($category_id)
    {
        return $this->courseModel->getCoursesByCategory($category_id);
    }

    public function searchCourses($searchTerm)
    {
        return $this->courseModel->searchCourses($searchTerm);
    }

    public function getTeacherCoursesCount($enseignant_id)
    {
        return $this->courseModel->getTeacherCoursesCount($enseignant_id);
    }

    public function getMostPopularCourse($enseignant_id)
    {
        return $this->courseModel->getMostPopularCourse($enseignant_id);
    }

    public function getRecentCourses($enseignant_id, $limit)
    {
        return $this->courseModel->getRecentCourses($enseignant_id, $limit);
    }

    public function getErrorMessages()
    {
        return $this->error_messages;
    }

    public function clearErrorMessages()
    {
        $this->error_messages = [];
    }

    public function validateCourseExists($cours_id)
    {
        return $this->courseModel->validateCourseExists($cours_id);
    }
}