<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;

class DashboardController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function getActiveStudentsCount() {
        $query = "SELECT COUNT(*) as count FROM Utilisateurs u 
                 JOIN Role r ON r.role_id = u.role_id 
                 WHERE r.titre = 'Etudiant' AND u.statut = 'Actif'";
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getActiveTeachersCount() {
        $query = "SELECT COUNT(*) as count FROM Utilisateurs u 
                 JOIN Role r ON r.role_id = u.role_id 
                 WHERE r.titre = 'Enseignant' AND u.statut = 'Actif'";
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getPublishedCoursesCount() {
        $query = "SELECT COUNT(*) as count FROM Cours WHERE status = 'Publié'";
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getMonthlyRevenue() {
        $query = "SELECT COALESCE(SUM(montant), 0) as total 
                 FROM Paiements 
                 WHERE MONTH(date_paiement) = MONTH(CURRENT_DATE()) 
                 AND YEAR(date_paiement) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getGrowthPercentages() {
        return [
            'students' => $this->calculateGrowthPercentage('Etudiant'),
            'teachers' => $this->calculateGrowthPercentage('Enseignant'),
            // 'courses' => $this->calculateCourseGrowthPercentage(),
            // 'revenue' => $this->calculateRevenueGrowthPercentage()
        ];
    }

    private function calculateGrowthPercentage($role) {
        $query = "SELECT 
            (SELECT COUNT(*) FROM Utilisateurs u1 
             JOIN Role r1 ON r1.role_id = u1.role_id 
             WHERE r1.titre = :role 
             AND MONTH(u1.dateAjout) = MONTH(CURRENT_DATE())
             AND YEAR(u1.dateAjout) = YEAR(CURRENT_DATE())) as current_month,
            (SELECT COUNT(*) FROM Utilisateurs u2 
             JOIN Role r2 ON r2.role_id = u2.role_id 
             WHERE r2.titre = :role 
             AND MONTH(u2.dateAjout) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
             AND YEAR(u2.dateAjout) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))) as last_month";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['role' => $role]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['last_month'] == 0) return 0;
        return round((($result['current_month'] - $result['last_month']) / $result['last_month']) * 100, 1);
    }

    // private function calculateCourseGrowthPercentage() {
    //     $query = "SELECT 
    //         (SELECT COUNT(*) FROM Cours 
    //          WHERE MONTH(date_creation) = MONTH(CURRENT_DATE())
    //          AND YEAR(date_creation) = YEAR(CURRENT_DATE())) as current_month,
    //         (SELECT COUNT(*) FROM Cours 
    //          WHERE MONTH(date_creation) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
    //          AND YEAR(date_creation) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))) as last_month";
        
    //     $stmt = $this->conn->query($query);
    //     $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
    //     if ($result['last_month'] == 0) return 0;
    //     return round((($result['current_month'] - $result['last_month']) / $result['last_month']) * 100, 1);
    // }

    private function calculateRevenueGrowthPercentage() {
        $query = "SELECT 
            (SELECT COALESCE(SUM(montant), 0) FROM Paiements 
             WHERE MONTH(date_paiement) = MONTH(CURRENT_DATE())
             AND YEAR(date_paiement) = YEAR(CURRENT_DATE())) as current_month,
            (SELECT COALESCE(SUM(montant), 0) FROM Paiements 
             WHERE MONTH(date_paiement) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
             AND YEAR(date_paiement) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))) as last_month";
        
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['last_month'] == 0) return 0;
        return round((($result['current_month'] - $result['last_month']) / $result['last_month']) * 100, 1);
    }

    public function getRecentActivities($limit = 5) {
        $query = "SELECT 'inscription' as type, 
                        u.prenom, u.nom, 
                        u.dateAjout as date,
                        r.titre as role
                 FROM Utilisateurs u
                 JOIN Role r ON r.role_id = u.role_id
                 WHERE u.dateAjout >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)
                 UNION
                 SELECT 'cours' as type,
                        c.titre as prenom,
                        e.prenom as nom,
                        c.dateAjout as date,
                        'cours' as role
                 FROM Cours c
                 JOIN Utilisateurs e ON e.id = c.enseignat_id
                 WHERE c.dateAjout >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)
                 ORDER BY date DESC
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGrowthClass($percentage) {
        return $percentage >= 0 ? 'text-green-500 bg-green-100' : 'text-red-500 bg-red-100';
    }
    public function formatTimeAgo($datetime) {
        $timestamp = strtotime($datetime);
        $now = time();
        $diff = $now - $timestamp;

        if ($diff < 60) {
            return "Il y a quelques secondes";
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return "Il y a " . $minutes . " minute" . ($minutes > 1 ? 's' : '');
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return "Il y a " . $hours . " heure" . ($hours > 1 ? 's' : '');
        } else {
            $days = floor($diff / 86400);
            return "Il y a " . $days . " jour" . ($days > 1 ? 's' : '');
        }
    }
}