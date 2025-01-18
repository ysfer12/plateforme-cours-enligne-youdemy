<?php
require_once '../../../vendor/autoload.php';

use App\Controllers\Enseignant\DashboardController;

$controller = new DashboardController();
$viewData = $controller->index();

// Initialize default values
$utilisateur = $viewData['utilisateur'] ?? null;
$statCours = $viewData['statCours'] ?? ['total_cours' => 0];
$statEtudiants = $viewData['statEtudiants'] ?? ['total_etudiants' => 0];
$statPopulaires = $viewData['statPopulaires'] ?? ['populaires' => 0];
$coursRecents = $viewData['coursRecents'] ?? [];
$erreur = $viewData['erreur'] ?? null;


// View content starts here
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Enseignant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-blue-600 text-white p-6 space-y-6">
            <!-- Profile Section -->
            <div class="flex items-center space-x-4">
                <img src="/api/placeholder/48/48" alt="Profile" class="w-12 h-12 rounded-full">
                <div>
                    <h2 class="text-lg font-semibold">
                        <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>
                    </h2>
                    <p class="text-sm text-primary-200">
                        <?= htmlspecialchars($utilisateur['role_titre']) ?>
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out active:bg-primary-500">
                            <i class="fas fa-home w-5"></i>
                            <span>Tableau de Bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="mes-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out">
                            <i class="fas fa-book w-5"></i>
                            <span>Mes Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="ajouter-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Ajouter un Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="parametres.php" class="flex items -center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out">
                            <i class="fas fa-cog w-5"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li>
                        <a href="deconnexion.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-red-500 rounded-lg transition duration-300 ease-in-out text-red-100">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="flex-1 p-6 overflow-y-auto">
            <!-- Header -->
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord</h1>
                <a href="ajouter-cours.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out">
                    Ajouter un Cours
                </a>
            </header>

            <?php if (isset($erreur)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

<!-- Stats Grid Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-4xl text-blue-500 mb-4">
            <i class="fas fa-book-open"></i>
        </div>
        <h3 class="text-lg font-semibold mb-2">Cours Total</h3>
        <p class="text-3xl font-bold text-blue-600">
            <?= htmlspecialchars($statCours['total_cours']) ?>
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-4xl text-green-500 mb-4">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="text-lg font-semibold mb-2">Étudiants Inscrits</h3>
        <p class="text-3xl font-bold text-green-600">
            <?= htmlspecialchars($statEtudiants['total_etudiants']) ?>
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-4xl text-purple-500 mb-4">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="text-lg font-semibold mb-2">Cours Populaires</h3>
        <p class="text-3xl font-bold text-purple-600">
            <?= htmlspecialchars($statPopulaires['populaires']) ?>
        </p>
    </div>
</div>
            <!-- Recent Courses -->
            <section>
                <h2 class="text-2xl font-bold mb-6">Cours Récents</h2>
                <div class="space-y-4">
                    <?php if (empty($coursRecents)): ?>
                        <div class="bg-white p-4 rounded-lg shadow-md text-center text-gray-500">
                            Aucun cours n'a été ajouté récemment.
                        </div>
                    <?php else: ?>
                        <?php foreach ($coursRecents as $cours): ?>
                            <div class="bg-white p-4 rounded-lg shadow-md flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        <?= htmlspecialchars($cours['titre']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        <?= htmlspecialchars($cours['description']) ?>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Inscrits : <?= htmlspecialchars($cours['total_inscriptions'] ?? 0) ?>
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="voir-cours.php?id=<?= htmlspecialchars($cours['cours_id']) ?>" 
                                       class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition duration-300 ease-in-out">
                                        Voir
                                    </a>
                                    <a href="modifier-cours.php?id=<?= htmlspecialchars($cours['cours_id']) ?>" 
                                       class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">
                                        Éditer
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>