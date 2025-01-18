<?php
// Database and Controller initialization
require_once '../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\Catalogue\CoursController;
use App\Models\Catalogue\CoursModel;

$database = new Database();
$pdo = $database->connection();

try {
    $coursModel = new CoursModel($pdo);
    $coursController = new CoursController($coursModel);

    $viewData = $coursController->index();

    extract($viewData);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Découvrez nos Formations - LearnHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50 font-inter">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold text-gray-800">LearnHub</h1>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">Formations</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">Catégories</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">À propos</a>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-blue-600 transition">
                    <i data-feather="search" class="w-5 h-5"></i>
                </button>
                <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Connexion
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-2xl p-12 mb-12 relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-4xl font-bold mb-4">Explorez de Nouvelles Compétences</h2>
                <p class="text-xl mb-8 max-w-2xl">Apprenez auprès des meilleurs experts, développez vos compétences et progressez dans votre carrière.</p>
                
                <!-- Advanced Search -->
                <form class="grid grid-cols-1 md:grid-cols-3 gap-4" method="GET">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Rechercher une formation..." 
                        class="px-4 py-3 rounded-lg bg-white/20 placeholder-white/70 text-white focus:outline-none focus:ring-2 focus:ring-white/30"
                    >
                    <select 
                        name="category" 
                        class="px-4 py-3 rounded-lg bg-white/20 text-white focus:outline-none focus:ring-2 focus:ring-white/30"
                    >
                        <option value="0">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
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
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-500 transform skew-y-12 scale-150"></div>
            </div>
        </section>

        <!-- Course Grid -->
        <section>
            <?php if (empty($cours)): ?>
                <div class="text-center py-12 bg-white rounded-2xl shadow-md">
                    <i data-feather="search" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-2">Aucune formation trouvée</h3>
                    <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($cours as $course): 
                        $tags = !empty($course['tag_names']) ? explode(',', $course['tag_names']) : [];
                    ?>
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
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
                                        href="cours-details.php?id=<?= $course['cours_id'] ?>" 
                                        class="flex items-center text-blue-600 hover:text-blue-800 transition font-semibold"
                                    >
                                        Détails du cours
                                        <i data-feather="arrow-right" class="w-4 h-4 ml-2"></i>
                                    </a>
                                    <?php if (!empty($course['lienContenu'])): ?>
                                        <div class="text-gray-400">
                                            <i data-feather="video" class="w-5 h-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center space-x-4 mt-12">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i data-feather="chevron-left" class="w-5 h-5 inline-block mr-2"></i>
                        Précédent
                    </a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                for ($i = $start; $i <= $end; $i++):
                    $activeClass = ($i == $page) 
                        ? 'bg-blue-600 text-white' 
                        : 'bg-white text-gray-700 hover:bg-gray-50';
                ?>
                    <a href="?page=<?= $i ?>" class="px-4 py-2 border border-gray-300 rounded-lg <?= $activeClass ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Suivant
                        <i data-feather="chevron-right" class="w-5 h-5 inline-block ml-2"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white py-12 mt-12">
        <div class="container mx-auto px-4 grid md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-bold text-xl mb-4 text-gray-800">LearnHub</h4>
                <p class="text-gray-600">Apprenez, grandissez, réussissez.</p>
            </div>
            <div>
                <h5 class="font-semibold mb-4 text-gray-700">Formations</h5>
                <nav class="space-y-2">
                    <a href="#" class="text-gray-600 hover:text-blue-600">Développement</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Design</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Marketing</a>
                </nav>
            </div>
            <div>
                <h5 class="font-semibold mb-4 text-gray-700">À propos</h5>
                <nav class="space-y-2">
                    <a href="#" class="text-gray-600 hover:text-blue-600">Notre mission</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Nos instructeurs</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Contact</a>
                </nav>
            </div>
            <div>
                <h5 class="font-semibold mb-4 text-gray-700">Légal</h5>
                <nav class="space-y-2">
                    <a href="#" class="text-gray-600 hover:text-blue-600">CGV</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Politique de confidentialité</a>
                </nav>
            </div>
        </div>
        <div class="container mx-auto px-4 mt-8 pt-8 border-t text-center text-gray-600">
            © <?= date('Y') ?> LearnHub. Tous droits réservés.
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>
</html>