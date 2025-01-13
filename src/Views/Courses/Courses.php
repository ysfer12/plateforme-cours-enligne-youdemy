<?php
require_once("../../../vendor/autoload.php");
use App\Config\Database;

$db = new Database();
$pdo = $db->connection();

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Auth/login.php");
    exit();
}

// Fetch all courses
$query = "SELECT c.*, u.firstName, u.lastName, cat.name as category_name
          FROM Courses c
          LEFT JOIN user u ON c.teacher_id = u.id
          LEFT JOIN Category cat ON c.category_id = cat.category_id
          ORDER BY c.created_at DESC";
$stmt = $pdo->query($query);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Courses</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($course['title']); ?></h2>
                <p class="text-gray-700 mb-2">Teacher: <?= htmlspecialchars($course['firstName'] . ' ' . $course['lastName']); ?></p>
                <p class="text-gray-700 mb-2">Category: <?= htmlspecialchars($course['category_name']); ?></p>
                <p class="text-gray-700 mb-4"><?= htmlspecialchars($course['description']); ?></p>
                <a href="" class="text-blue-500 hover:underline">View Details</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>