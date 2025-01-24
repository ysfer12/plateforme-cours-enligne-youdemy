<?php
namespace App\Controllers\Admin;

use App\Models\Admin\DashboardModel;

class DashboardController {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
    }

    public function getActiveStudentsCount() {
        return $this->dashboardModel->getActiveStudentsCount();
    }

    public function getActiveTeachersCount() {
        return $this->dashboardModel->getActiveTeachersCount();
    }

    public function getPublishedCoursesCount() {
        return $this->dashboardModel->getPublishedCoursesCount();
    }

    public function getGrowthPercentages() {
        return $this->dashboardModel->getGrowthPercentages();
    }

    public function getRecentActivities($limit = 5) {
        return $this->dashboardModel->getRecentActivities($limit);
    }

    public function getGrowthClass($percentage) {
        return $this->dashboardModel->getGrowthClass($percentage);
    }

    public function formatTimeAgo($datetime) {
        return $this->dashboardModel->formatTimeAgo($datetime);
    }

    public function getTopCourses($limit = 3) {
        return $this->dashboardModel->getTopCourses($limit);
    }

    public function getTopTeachers($limit = 3) {
        return $this->dashboardModel->getTopTeachers($limit);
    }
}