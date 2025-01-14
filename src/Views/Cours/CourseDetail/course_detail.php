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

if (!isset($_GET['id'])) {
    echo "Course ID is missing.";
    exit();
}

$course_id = $_GET['id'];

// Fetch course details
$query = "SELECT c.*, u.firstName, u.lastName, cat.name as category_name
          FROM Courses c
          LEFT JOIN user u ON c.teacher_id = u.id
          LEFT JOIN Category cat ON c.category_id = cat.category_id
          WHERE c.id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $course_id);
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    echo "Course not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($course['title']); ?></h1>
        <p class="text-gray-700 mb-2">Teacher: <?= htmlspecialchars($course['firstName'] . ' ' . $course['lastName']); ?></p>
        <p class="text-gray-700 mb-2">Category: <?= htmlspecialchars($course['category_name']); ?></p>
        <p class="text-gray-700 mb-4"><?= htmlspecialchars($course['description']); ?></p>
    </div>
</body>
</html>