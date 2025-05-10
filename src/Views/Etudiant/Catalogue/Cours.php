<?php
// Autoload classes
require_once '../../../../vendor/autoload.php';

use App\Controllers\Etudiant\Catalogue\CoursController;
use App\Config\AuthMiddleware;
use App\Controllers\Etudiant\UserController;
try {
    $userId = AuthMiddleware::getUserId();

    if (isset($_POST['logout'])) {
        $userController = new UserController();
        $userController->logout();
        exit();
    }

    $coursController = new CoursController();

    $filters = [
        'search' => $_GET['search'] ?? '',
        'category' => $_GET['category'] ?? 0,
        'page' => $_GET['page'] ?? 1
    ];

    // Get course data
    $viewData = $coursController->index($userId, $filters);

    // Extract view data
    extract($viewData);

} catch (Exception $e) {
    // Log error or handle appropriately
    error_log('Catalogue Error: ' . $e->getMessage());
    die("Une erreur est survenue. Veuillez réessayer plus tard.");
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Formations - Youdemy</title>
    
    <!-- Critical CSS and Font Loading -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <!-- Inline Styles -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .content-overlay {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(8px);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
        }
    </style>
    </head>
<body class="bg-gray-50 min-h-screen flex flex-col">
<!-- Navigation Bar -->
<nav class="fixed w-full z-50 glass-effect border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo with Gradient -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                </div>
                <span class="text-xl md:text-2xl font-bold gradient-text">Youdemy</span>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-blue-600 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Enhanced Search Bar - Hidden on Mobile -->
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <div class="relative w-full">
                    <input type="text" 
                           placeholder="Que souhaitez-vous apprendre aujourd'hui ?" 
                           class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pl-12">
                    <div class="absolute left-4 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="absolute right-2 top-1 px-4 py-1 bg-blue-600 text-white rounded-full text-sm hover:bg-blue-700 transition">
                        Rechercher
                    </button>
                </div>
            </div>

            <!-- Navigation Links - Hidden on Mobile -->
            <div class="hidden md:flex items-center space-x-8">
                <div class="hidden md:flex items-center space-x-6">
                    <a href="../Admin/Catalogue/Cours.php" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
                        <i class="fas fa-book-open text-sm"></i>
                        <span>Catalogue</span>
                    </a>
                    <div class="relative group">
                        <a href="#" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
                            <i class="fas fa-th-large text-sm"></i>
                            <span>Qui sommes nous?</span>
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 focus:outline-none">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <i class="fas fa-chevron-down text-xs group-hover:rotate-180 transition-transform duration-200"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg py-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200">
                        <a href="../home.php" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-book-reader mr-2"></i>
                            Mes cours
                        </a>
                        <hr class="my-2 border-gray-100">
                        <form method="POST">
                            <button type="submit" name="submit" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </form> 
                        </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu - Hidden by Default -->
        <div id="mobile-menu" class="hidden md:hidden bg-white pb-4 absolute top-16 left-0 right-0 border-b border-gray-200 shadow-lg">
            <!-- Mobile Search -->
            <div class="px-4 pt-2 pb-3">
                <div class="relative">
                    <input type="text" 
                           placeholder="Rechercher..." 
                           class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pl-12">
                    <div class="absolute left-4 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Links -->
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="../Catalogue/Cours.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                    <i class="fas fa-book-open mr-2"></i>
                    Catalogue
                </a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                    <i class="fas fa-th-large mr-2"></i>
                    Qui sommes nous?
                </a>
                <!-- Mobile Profile Section -->
                <div class="pt-4 space-y-2">
                    <div class="px-3 py-2 text-sm text-gray-500">Mon compte</div>
                    <a href="../Admin/dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-book-reader mr-2"></i>
                        Mes cours
                    </a>
                    <form method="POST">
                        <button type="submit" name="submit" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-24 space-y-12">
        <!-- Hero Search Section -->
        <section class="bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-2xl p-12 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-4">Explorez de Nouvelles Compétences</h1>
                <p class="text-xl mb-8 max-w-2xl">
                    Apprenez auprès des meilleurs experts, développez vos compétences et progressez dans votre carrière.
                </p>
                
                <!-- Advanced Search Form -->
                <form class="grid grid-cols-1 md:grid-cols-3 gap-4" method="GET">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Rechercher une formation..." 
                        value="<?= htmlspecialchars($search ?? '') ?>"
                        class="px-4 py-3 rounded-lg bg-white/20 placeholder-white/70 text-white focus:outline-none focus:ring-2 focus:ring-white/30"
                    >
                    <select 
                        name="category" 
                        class="px-4 py-3 rounded-lg bg-white/20 text-white focus:outline-none focus:ring-2 focus:ring-white/30"
                    >
                        <option value="0">Toutes les catégories</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                            <option 
                                value="<?= $cat['category_id'] ?>" 
                                <?= ($categoryId == $cat['category_id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($cat['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button 
                        type="submit" 
                        class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition font-semibold"
                    >
                        Rechercher
                    </button>
                </form>
            </div>
        </section>

        <!-- Courses Grid -->
        <section>
            <?php if (empty($courses)): ?>
                <div class="text-center py-12 bg-white rounded-2xl shadow-md">
                    <i data-feather="search" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                    <h2 class="text-2xl font-semibold text-gray-600 mb-2">
                        Aucune formation trouvée
                    </h2>
                    <p class="text-gray-500">
                        Essayez de modifier vos critères de recherche
                    </p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($courses as $course): 
                        $tags = !empty($course['tag_names']) ? explode(',', $course['tag_names']) : [];
                    ?>
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                            <!-- Course Card Content -->
                            <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                                <i data-feather="book" class="w-16 h-16 text-white opacity-70"></i>
                                <div class="absolute bottom-4 left-4 bg-white/90 px-3 py-1 rounded-full text-sm font-medium text-blue-600">
                                    <?= htmlspecialchars($course['category_name']) ?>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition">
                                    <?= htmlspecialchars($course['titre']) ?>
                                </h3>
                                
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i data-feather="user" class="w-4 h-4 text-gray-600"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">
                                        <?= htmlspecialchars($course['prenom'] . ' ' . $course['nom_enseignant']) ?>
                                    </span>
                                </div>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    <?= htmlspecialchars($course['description']) ?>
                                </p>

                                <?php if (!empty($tags)): ?>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                                            <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-xs">
                                                #<?= htmlspecialchars(trim($tag)) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="flex justify-between items-center mt-4">
                                <a 
    href="../Catalogue/Cours-details.php?id=<?= $course['cours_id'] ?>" 
    class="flex items-center text-blue-600 hover:text-blue-800 transition font-semibold"
>
    Détails du cours
    <i data-feather="arrow-right" class="w-4 h-4 ml-2"></i>
</a>                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center space-x-4 mt-12">
                <?php if ($currentPage > 1): ?>
                    <a 
                        href="?page=<?= $currentPage - 1 ?>&search=<?= urlencode($search ?? '') ?>&category=<?= $categoryId ?>" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                    >
                        Précédent
                    </a>
                <?php endif; ?>

                <?php
                $start = max(1, $currentPage - 2);
                $end = min($totalPages, $currentPage + 2);
                
                for ($i = $start; $i <= $end; $i++):
                    $activeClass = ($i == $currentPage) 
                        ? 'bg-blue-600 text-white' 
                        : 'bg-white text-gray-700 hover:bg-gray-50';
                ?>
                    <a 
                        href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&category=<?= $categoryId ?>" 
                        class="px-4 py-2 border border-gray-300 rounded-lg <?= $activeClass ?>"
                    >
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a 
                        href="?page=<?= $currentPage + 1 ?>&search=<?= urlencode($search ?? '') ?>&category=<?= $categoryId ?>" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                    >
                        Suivant
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer (truncated for brevity) -->
    <footer class="bg-gray-800 text-gray-300">
        <!-- Footer content remains the same as in the previous version -->
    </footer>

    <script>
        feather.replace();
        // Mobile menu toggle functionality can be added if needed
    </script>
</body>
</html>