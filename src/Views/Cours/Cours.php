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

// Pagination settings
$courses_per_page = 9;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $courses_per_page;

// Get total number of courses
$count_query = "SELECT COUNT(*) FROM Courses";
$total_courses = $pdo->query($count_query)->fetchColumn();
$total_pages = ceil($total_courses / $courses_per_page);

// Fetch courses with pagination
$query = "SELECT c.*, u.firstName, u.lastName, cat.name as category_name,
                 (SELECT COUNT(*) FROM Enrollments WHERE course_id = c.course_id) as student_count
          FROM Courses c
          LEFT JOIN user u ON c.teacher_id = u.id
          LEFT JOIN Category cat ON c.category_id = cat.category_id
          ORDER BY c.created_at DESC
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':limit', $courses_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories for the filter
$category_query = "SELECT * FROM Category ORDER BY name";
$categories = $pdo->query($category_query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Catalogue des cours</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(to right, #3B82F6, #2563EB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="fixed w-full z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Home Button -->
                <div class="flex items-center space-x-8">
                    <a href="../../../index.php" class="flex items-center space-x-3 hover:opacity-90 transition">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-2xl text-white"></i>
                        </div>
                        <span class="text-2xl font-bold gradient-text">Youdemy</span>
                    </a>
                    <a href="../../../index.php" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-home"></i>
                        <span>Accueil</span>
                    </a>
                </div>

                <!-- User Navigation -->
                <div class="flex items-center space-x-6">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition">
                                <i class="fas fa-user-circle text-xl"></i>
                                <span>Mon compte</span>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    Mon profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    Mes cours
                                </a>
                                <a href="../Auth/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Déconnexion
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="../Auth/login.php" class="px-4 py-2 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                            Connexion
                        </a>
                        <a href="../Auth/register.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Inscription
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search and Filters Section -->
    <section class="pt-24 pb-12 bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-4">Catalogue des cours</h1>
                <p class="text-blue-100 text-lg">Trouvez le cours parfait pour développer vos compétences</p>
            </div>
            
            <!-- Search Form -->
            <form action="" method="GET" class="max-w-3xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           name="search"
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                           placeholder="Rechercher un cours..." 
                           class="w-full px-6 py-4 rounded-full border-2 border-transparent focus:border-blue-300 focus:outline-none shadow-lg text-lg pl-14">
                    <div class="absolute left-5 top-5 text-gray-400">
                        <i class="fas fa-search text-xl"></i>
                    </div>
                    <button type="submit" class="absolute right-4 top-3 px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Course Grid Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Category Filters -->
            <div class="flex overflow-x-auto pb-4 mb-8 scrollbar-hide">
                <a href="?category=all" class="px-6 py-2 <?= (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' ?> rounded-full mr-4 whitespace-nowrap hover:bg-blue-600 hover:text-white transition">
                    Tous les cours
                </a>
                <?php foreach ($categories as $category): ?>
                <a href="?category=<?= $category['category_id'] ?>" 
                   class="px-6 py-2 <?= (isset($_GET['category']) && $_GET['category'] == $category['category_id']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' ?> rounded-full mr-4 hover:bg-blue-600 hover:text-white transition whitespace-nowrap">
                    <?= htmlspecialchars($category['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Courses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 card-hover">
                    <div class="relative">
                        <img src="<?= $course['image_url'] ?? '/api/placeholder/400/200' ?>" 
                             alt="<?= htmlspecialchars($course['title']) ?>" 
                             class="w-full h-48 object-cover">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <img src="/api/placeholder/32/32" 
                                 alt="<?= htmlspecialchars($course['firstName'] . ' ' . $course['lastName']) ?>" 
                                 class="w-8 h-8 rounded-full">
                            <span class="text-sm text-gray-600">
                                <?= htmlspecialchars($course['firstName'] . ' ' . $course['lastName']) ?>
                            </span>
                        </div>
                        <h3 class="font-semibold text-xl mb-2"><?= htmlspecialchars($course['title']) ?></h3>
                        <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
                        <div class="flex items-center mb-4">
                            <span class="text-sm text-gray-600">
                                <?= $course['student_count'] ?? 0 ?> étudiants inscrits
                            </span>
                        </div>
                        <!-- <div class="flex justify-between items-center">
                            <span class="font-bold text-lg"><?= number_format($course['price'], 2) ?> €</span>
                            <a href="course-details.php?id=<?= $course['id'] ?>" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Voir le cours
                            </a>
                        </div> -->
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="flex justify-center mt-12 space-x-2">
                <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 hover:border-blue-600 hover:text-blue-600 transition">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-lg <?= $i === $current_page ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:border-blue-600 hover:text-blue-600' ?> transition">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 hover:border-blue-600 hover:text-blue-600 transition">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>