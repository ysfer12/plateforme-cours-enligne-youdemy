<?php
session_start();

require_once '../../../vendor/autoload.php';
use App\Config\Database;
use App\Controllers\EtudiantController;
use App\Controllers\Auth\AuthController;

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Auth/login.php');
    exit();
}

if(isset($_POST['submit'])) {
$logout = new AuthController();
$logout->logout();
}

try {
    $database = new Database();
    $db = $database->connection();
    
    $etudiantController = new EtudiantController($db);
    $dashboardData = $etudiantController->getDashboardData($_SESSION['user_id']);

    $user = $dashboardData['user'];
    $courses = $dashboardData['courses'];
    $statistics = $dashboardData['statistics'];
    $error = $dashboardData['error'];

    $totalCourses = $statistics['totalCourses'] ?? 0;
    $totalCategories = $statistics['totalCategories'] ?? 0; 
    $lastInscription = $statistics['lastInscription'] ?? 'Aucune';

} catch(Exception $e) {
    $error = "Une erreur est survenue : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Étudiant - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo et navigation -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="index.php" class="text-xl font-bold text-blue-600">Youdemy</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="dashboard.php" class="border-b-2 border-blue-600 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="cours.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Catalogue
                        </a>
                    </div>
                </div>

                <!-- Menu utilisateur -->
                <div class="flex items-center">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-400 hover:text-gray-500 mr-4">
                        <i class="fas fa-bell"></i>
                    </button>

                    <!-- Menu profil -->
                    <div class="ml-3 relative">
                        <button type="button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button">
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                            </div>
                        </button>

                        <!-- Menu déroulant -->
                        <div id="user-menu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1">
                                <a href="dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-columns mr-2"></i>Dashboard
                                </a>
                                <a href="profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Mon profil
                                </a>
                                <a href="parametres.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Paramètres
                                </a>
                                <hr class="my-1">
                                <form action="" method="POST">
                                    <button type="submit" name="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- En-tête du dashboard -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Bonjour, <?= htmlspecialchars($user['prenom']) ?> 👋
            </h1>
            <p class="mt-2 text-gray-600">
                Voici un aperçu de votre apprentissage
            </p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total des cours -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Cours inscrits</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?= $totalCourses ?></p>
                    </div>
                </div>
            </div>

            <!-- Catégories -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-folder text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Catégories suivies</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?= $totalCategories ?></p>
                    </div>
                </div>
            </div>

            <!-- Dernière inscription -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Dernière inscription</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?= $lastInscription ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des cours -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Mes cours</h2>
            
            <?php if (empty($courses)): ?>
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-book-open text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Vous n'êtes inscrit à aucun cours
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Explorez notre catalogue pour commencer votre apprentissage
                    </p>
                    <a href="catalogue.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Voir le catalogue
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($courses as $course): ?>
                        <div class="border rounded-xl p-6 hover:shadow-md transition duration-300">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-blue-600"></i>
                                </div>
                                <span class="text-sm text-gray-500">
                                    <?= $course['date_inscription'] ?>
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <?= htmlspecialchars($course['titre']) ?>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-4">
                                <?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    <?= htmlspecialchars($course['category_name']) ?>
                                </span>
                                <a href="cours-details.php?id=<?= $course['cours_id'] ?>" class="text-blue-600 hover:text-blue-800">
                                    Continuer →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section recherche -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    Découvrez de nouveaux cours
                </h2>
                <p class="text-gray-600 mb-6">
                    Explorez notre catalogue complet de cours et développez vos compétences
                </p>
                <form action="catalogue.php" method="GET" class="flex gap-4 max-w-lg mx-auto">
                    <input type="text" name="search" placeholder="Rechercher un cours..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                        Rechercher
                    </button>
                </form>
                <div class="mt-4">
                    <a href="catalogue.php" class="text-blue-600 hover:text-blue-800">
                        Voir tous les cours disponibles →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        userMenuButton.addEventListener('click', () => {
            const isHidden = userMenu.classList.contains('hidden');
            userMenu.classList.toggle('hidden', !isHidden);
        });

        document.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>