<?php
namespace App\Models\Inscription;

use App\Config\Database;
use PDO;

class InscriptionModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }
    public function isUserEnrolled($userId, $courseId) {
        $checkQuery = "SELECT COUNT(*) as count FROM Inscriptions WHERE cours_id = :cours_id AND etudiant_id = :etudiant_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':cours_id', $courseId, PDO::PARAM_INT);
        $checkStmt->bindParam(':etudiant_id', $userId, PDO::PARAM_INT);
        $checkStmt->execute();
        return $checkStmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }

    public function enrollUserToCourse($userId, $courseId) {
        try {
            $enrollQuery = "INSERT INTO Inscriptions (cours_id, etudiant_id, dateInscription) VALUES (:cours_id, :etudiant_id, NOW())";
            $enrollStmt = $this->conn->prepare($enrollQuery);
            $enrollStmt->bindParam(':cours_id', $courseId, PDO::PARAM_INT);
            $enrollStmt->bindParam(':etudiant_id', $userId, PDO::PARAM_INT);
            return $enrollStmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>