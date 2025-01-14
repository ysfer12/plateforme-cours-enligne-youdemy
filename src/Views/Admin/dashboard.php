<?php
require_once '../../../vendor/autoload.php';

// Check admin session/authentication here
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy Admin Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .section { display: none; }
        .section.active { display: block; }
        .tab.active {
            background-color: rgb(55 65 81);
            border-left: 4px solid rgb(59 130 246);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed w-64 h-full bg-gray-800 text-white shadow-xl z-10">
            <!-- En-tête Sidebar -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <i data-lucide="book-open" class="w-8 h-8 text-blue-500"></i>
                    <h1 class="text-2xl font-bold">Youdemy</h1>
                </div>
                <p class="text-gray-400 text-sm mt-1">Interface Administrateur</p>
            </div>

            <!-- Profil Admin -->
            <div class="p-4">
                <div class="flex items-center space-x-3 bg-gray-700/50 rounded-lg p-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center">
                        <i data-lucide="user" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="font-medium"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></p>
                        <p class="text-sm text-gray-400">Super Admin</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-2">
                <a href="dashboard.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors active">
                    <i data-lucide="bar-chart-2" class="mr-3 w-5 h-5"></i>
                    <span>Statistiques</span>
                </a>
                <a href="Utilisateurs.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="users" class="mr-3 w-5 h-5"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="Tags.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="tag" class="mr-3 w-5 h-5"></i>
                    <span>Tags</span>
                </a>
                <a href="Categories.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="folder-tree" class="mr-3 w-5 h-5"></i>
                    <span>Catégories</span>
                </a>
            </nav>

            <!-- Bouton Déconnexion -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <a href="logout.php" class="w-full flex items-center justify-center space-x-2 text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
            <!-- Statistics Dashboard -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Tableau de bord</h2>
                    <p class="text-gray-600 mt-1">Vue d'ensemble des statistiques</p>
                </div>
                <div class="flex space-x-3">
                    <button class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg shadow hover:shadow-md transition-all">
                        <i data-lucide="download" class="w-5 h-5 text-gray-600"></i>
                        <span>Exporter</span>
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Students Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i data-lucide="graduation-cap" class="w-6 h-6 text-blue-500"></i>
                        </div>
                        <span class="text-sm font-medium text-green-500 bg-green-100 px-2 py-1 rounded">+12%</span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4">1,250</h3>
                    <p class="text-gray-600">Étudiants actifs</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <i data-lucide="trending-up" class="w-4 h-4 mr-1 text-green-500"></i>
                        <span>+8% ce mois</span>
                    </div>
                </div>

                <!-- Teachers Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i data-lucide="users" class="w-6 h-6 text-purple-500"></i>
                        </div>
                        <span class="text-sm font-medium text-green-500 bg-green-100 px-2 py-1 rounded">+5%</span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4">48</h3>
                    <p class="text-gray-600">Enseignants</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <i data-lucide="trending-up" class="w-4 h-4 mr-1 text-green-500"></i>
                        <span>+3% ce mois</span>
                    </div>
                </div>

                <!-- Courses Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i data-lucide="book-open" class="w-6 h-6 text-yellow-500"></i>
                        </div>
                        <span class="text-sm font-medium text-green-500 bg-green-100 px-2 py-1 rounded">+15%</span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4">156</h3>
                    <p class="text-gray-600">Cours publiés</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <i data-lucide="trending-up" class="w-4 h-4 mr-1 text-green-500"></i>
                        <span>+12% ce mois</span>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i data-lucide="euro" class="w-6 h-6 text-green-500"></i>
                        </div>
                        <span class="text-sm font-medium text-green-500 bg-green-100 px-2 py-1 rounded">+20%</span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4">45,000€</h3>
                    <p class="text-gray-600">Revenu mensuel</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <i data-lucide="trending-up" class="w-4 h-4 mr-1 text-green-500"></i>
                        <span>+18% ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold mb-4">Activité récente</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i data-lucide="user-plus" class="w-5 h-5 text-blue-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Nouvel étudiant inscrit</p>
                            <p class="text-sm text-gray-500">Marie Dubois s'est inscrite au cours de JavaScript</p>
                        </div>
                        <span class="text-sm text-gray-500">Il y a 2h</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i data-lucide="book" class="w-5 h-5 text-green-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Nouveau cours publié</p>
                            <p class="text-sm text-gray-500">React.js Avancé par Pierre Martin</p>
                        </div>
                        <span class="text-sm text-gray-500">Il y a 4h</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>