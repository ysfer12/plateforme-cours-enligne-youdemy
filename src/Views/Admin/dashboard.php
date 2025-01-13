<?php
require_once("../../../vendor/autoload.php");
use App\Config\Database;
$db = new Database();
$pdo = $db->connection();

// Détection de la page active
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Fetch dashboard statistics
$stats = [
    'total_students' => $pdo->query("SELECT COUNT(*) FROM user u JOIN role r ON u.role_id = r.role_id WHERE r.title = 'student'")->fetchColumn(),
    'total_courses' => $pdo->query("SELECT COUNT(*) FROM Courses")->fetchColumn(),
    'total_teachers' => $pdo->query("SELECT COUNT(*) FROM user u JOIN role r ON u.role_id = r.role_id WHERE r.title = 'teacher'")->fetchColumn(),
    'total_enrollments' => $pdo->query("SELECT COUNT(*) FROM Enrollments")->fetchColumn()
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouDemy Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white">
        <div class="p-6">
            <h1 class="text-2xl font-bold">YouDemy Admin</h1>
        </div>
        <nav class="mt-6">
            <a href="?page=dashboard" class="flex items-center px-6 py-3 text-gray-100 <?= $page === 'dashboard' ? 'bg-gray-800' : 'hover:bg-gray-800'; ?>">
                <i class="fas fa-home mr-3"></i>
                Dashboard
            </a>
            <a href="?page=courses" class="flex items-center px-6 py-3 text-gray-300 <?= $page === 'courses' ? 'bg-gray-800' : 'hover:bg-gray-800'; ?>">
                <i class="fas fa-book mr-3"></i>
                Courses
            </a>
            <a href="?page=users" class="flex items-center px-6 py-3 text-gray-300 <?= $page === 'users' ? 'bg-gray-800' : 'hover:bg-gray-800'; ?>">
                <i class="fas fa-users mr-3"></i>
                Users
            </a>
            <a href="?page=categories" class="flex items-center px-6 py-3 text-gray-300 <?= $page === 'categories' ? 'bg-gray-800' : 'hover:bg-gray-800'; ?>">
                <i class="fas fa-tags mr-3"></i>
                Categories
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <?php if ($page === 'dashboard'): ?>
        <!-- Dashboard Section -->
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-gray-500 text-sm">Total Students</h4>
                        <h3 class="text-2xl font-bold"><?= number_format($stats['total_students']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-gray-500 text-sm">Total Courses</h4>
                        <h3 class="text-2xl font-bold"><?= number_format($stats['total_courses']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-gray-500 text-sm">Total Teachers</h4>
                        <h3 class="text-2xl font-bold"><?= number_format($stats['total_teachers']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-gray-500 text-sm">Total Enrollments</h4>
                        <h3 class="text-2xl font-bold"><?= number_format($stats['total_enrollments']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <?php elseif ($page === 'courses'): ?>
        <!-- Courses Section -->
        <h2 class="text-2xl font-bold mb-6">Courses Management</h2>
        <?php
        $stmt = $pdo->query("
            SELECT c.*, u.firstName, u.lastName, cat.name as category_name
            FROM Courses c
            LEFT JOIN user u ON c.teacher_id = u.id
            LEFT JOIN Category cat ON c.category_id = cat.category_id
            ORDER BY c.created_at DESC
        ");
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teacher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($courses as $course): ?>
                    <tr>
                        <td class="px-6 py-4"><?= htmlspecialchars($course['title']); ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($course['firstName'] . ' ' . $course['lastName']); ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($course['category_name']); ?></td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-900">Edit</button>
                            <button class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($page === 'users'): ?>
        <!-- Users Section -->
        <h2 class="text-2xl font-bold mb-6">Users Management</h2>
        <?php
        $stmt = $pdo->query("
            SELECT u.*, r.title as role_title
            FROM user u
            LEFT JOIN role r ON u.role_id = r.role_id
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['email']); ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['role_title']); ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['statut']); ?></td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-900">Edit</button>
                            <button class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
