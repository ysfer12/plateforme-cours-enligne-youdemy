<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\TagsController;

// Handle tag addition, update, and deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tagsController = new TagsController();
    
    // Add new tag
    if (isset($_POST['add_tags'])) {
        $tagName = trim($_POST['tag_name']);
        
        if (!empty($tagName)) {
            try {
                $addedTag = $tagsController->addTag($tagName);
                $message = 'Tag ajouté avec succès.';
            } catch (\Exception $e) {
                $message = 'Erreur : ' . $e->getMessage();
            }
        }
    }

    // Update existing tag
    if (isset($_POST['update_tag']) && isset($_POST['tag_id']) && isset($_POST['tag_name'])) {
        try {
            $tagId = $_POST['tag_id'];
            $newTagName = trim($_POST['tag_name']);
            
            if (!empty($newTagName)) {
                $tagsController->updateTag($tagId, $newTagName);
                $message = 'Tag mis à jour avec succès.';
            }
        } catch (\Exception $e) {
            $message = 'Erreur : ' . $e->getMessage();
        }
    }

    // Delete tag
    if (isset($_POST['delete_tag']) && isset($_POST['tag_id'])) {
        try {
            $tagsController->deleteTag($_POST['tag_id']);
            $message = 'Tag supprimé avec succès.';
        } catch (\Exception $e) {
            $message = 'Erreur : ' . $e->getMessage();
        }
    }
}

// Fetch tags
$tagsController = new TagsController();
$tags = $tagsController->getTags();
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
        .section {
            display: none;
        }
        .section.active {
            display: block;
        }
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
                        <p class="font-medium">Admin User</p>
                        <p class="text-sm text-gray-400">Super Admin</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-2">
                <button onclick="switchSection('stats')" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors active">
                    <i data-lucide="bar-chart-2" class="mr-3 w-5 h-5"></i>
                    <span>Statistiques</span>
                </button>
                <button onclick="switchSection('users')" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="users" class="mr-3 w-5 h-5"></i>
                    <span>Utilisateurs</span>
                </button>
                <button onclick="switchSection('tags')" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="tag" class="mr-3 w-5 h-5"></i>
                    <span>Tags</span>
                </button>
                <button onclick="switchSection('categories')" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="folder-tree" class="mr-3 w-5 h-5"></i>
                    <span>Catégories</span>
                </button>
            </nav>

            <!-- Bouton Déconnexion -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <button class="w-full flex items-center justify-center space-x-2 text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span>Déconnexion</span>
                </button>
            </div>
        </aside>
        <!-- Main Content -->
<main class="ml-64 flex-1 p-8">
    <!-- Statistics Section -->
    <section id="stats" class="section active">
        <!-- En-tête de section -->
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

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Carte Étudiants -->
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

            <!-- Carte Enseignants -->
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

            <!-- Carte Cours -->
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

            <!-- Carte Revenus -->
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

        <!-- Activité récente -->
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
    </section>
    <!-- Users Management Section -->
<section id="users" class="section">
    <!-- En-tête de section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h2>
            <p class="text-gray-600 mt-1">Administration des comptes étudiants et enseignants</p>
        </div>
        <button class="flex items-center space-x-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Ajouter un utilisateur</span>
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Search and Filter Bar -->
        <div class="p-6 border-b">
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        <input type="text" placeholder="Rechercher un utilisateur..." 
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <select class="border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    <option>Tous les rôles</option>
                    <option>Étudiants</option>
                    <option>Enseignants</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-4 font-medium text-gray-600">Utilisateur</th>
                    <th class="text-left p-4 font-medium text-gray-600">Rôle</th>
                    <th class="text-left p-4 font-medium text-gray-600">Inscription</th>
                    <th class="text-left p-4 font-medium text-gray-600">Statut</th>
                    <th class="text-left p-4 font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- User Row 1 -->
                <tr class="border-t hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <i data-lucide="user" class="w-6 h-6 text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">Marie Dubois</p>
                                <p class="text-sm text-gray-500">marie@example.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">Étudiant</span>
                    </td>
                    <td class="p-4">
                        <span class="text-gray-600">14 Jan, 2024</span>
                    </td>
                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Actif</span>
                    </td>
                    <td class="p-4">
                        <div class="flex space-x-2">
                            <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                <i data-lucide="edit-2" class="w-5 h-5"></i>
                            </button>
                            <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Pagination -->
                <div class="p-4 border-t">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-500 text-sm">Affichage de 1-10 sur 45 utilisateurs</p>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors">Précédent</button>
                            <button class="px-3 py-1 border rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors">1</button>
                            <button class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors">2</button>
                            <button class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors">3</button>
                            <button class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors">Suivant</button>
                        </div>
                    </div>
                </div>
            </tbody>
        </table>
    </div>
</section>

<!-- Tags Management Section -->
<section id="tags" class="section">
    <!-- En-tête de section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Gestion des Tags</h2>
            <p class="text-gray-600 mt-1">Gérez les tags des cours</p>
        </div>
        <button onclick="openAddTagModal()" class="flex items-center space-x-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Ajouter un tag</span>
        </button>
    </div>

    <!-- Affichage des messages -->
    <?php if (isset($message)): ?>
        <div class="mb-4 p-4 <?= strpos($message, 'Erreur') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?> rounded">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Tags Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($tags as $index => $tag): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-<?= ['blue', 'green', 'purple', 'yellow', 'red'][$index % 5] ?>-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="hash" class="w-6 h-6 text-<?= ['blue', 'green', 'purple', 'yellow', 'red'][$index % 5] ?>-500"></i>
                        </div>
                        <div>
                            <h3 class="font-medium"><?= htmlspecialchars($tag->getNom()) ?></h3>
                            <p class="text-sm text-gray-500"><?= rand(10, 50) ?> cours associés</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="openEditTagModal(<?= $tag->getId() ?>, '<?= htmlspecialchars($tag->getNom()) ?>')" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                            <i data-lucide="edit-2" class="w-5 h-5"></i>
                        </button>
                        <button onclick="openDeleteTagModal(<?= $tag->getId() ?>, '<?= htmlspecialchars($tag->getNom()) ?>')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <i data-lucide="trending-up" class="w-4 h-4 mr-1 text-green-500"></i>
                    <span>+<?= rand(5, 15) ?>% ce mois</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal for Adding Tags -->
    <div id="addTagModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Ajouter un tag</h2>
                <button onclick="closeAddTagModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form method="POST">
                <input 
                    type="text" 
                    name="tag_name" 
                    placeholder="Nom du tag" 
                    class="w-full border rounded p-2 mb-4"
                    required
                >
                <div class="flex justify-end space-x-2">
                    <button 
                        type="button" 
                        onclick="closeAddTagModal()" 
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        name="add_tags"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    >
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Editing Tags -->
    <div id="editTagModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Modifier un tag</h2>
                <button onclick="closeEditTagModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form method="POST">
                <input type="hidden" id="editTagId" name="tag_id">
                <input 
                    type="text" 
                    id="editTagName"
                    name="tag_name" 
                    placeholder="Nom du tag" 
                    class="w-full border rounded p-2 mb-4"
                    required
                >
                <div class="flex justify-end space-x-2">
                    <button 
                        type="button" 
                        onclick="closeEditTagModal()" 
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        name="update_tag"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    >
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Deleting Tags -->
    <div id="deleteTagModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Supprimer un tag</h2>
                <button onclick="closeDeleteTagModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form method="POST">
                <input type="hidden" id="deleteTagId" name="tag_id">
                <p class="mb-4 text-gray-600">Êtes-vous sûr de vouloir supprimer le tag <span id="deleteTagName" class="font-bold"></span> ?</p>
                <div class="flex justify-end space-x-2">
                    <button 
                        type="button" 
                        onclick="closeDeleteTagModal()" 
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        name="delete_tag"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                    >
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Categories Management Section -->
<section id="categories" class="section">
    <!-- En-tête de section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Gestion des Catégories</h2>
            <p class="text-gray-600 mt-1">Gérez les catégories de cours</p>
        </div>
        <button class="flex items-center space-x-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Ajouter une catégorie</span>
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Category Card 1 -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="code" class="w-6 h-6 text-indigo-500"></i>
                    </div>
                    <div>
                        <h3 class="font-medium">Développement Web</h3>
                        <p class="text-sm text-gray-500">56 cours</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i data-lucide="edit-2" class="w-5 h-5"></i>
                    </button>
                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Progression</span>
                    <span class="font-medium">85%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-500 h-2 rounded-full" style="width: 85%"></div>
                </div>
            </div>
        </div>

        <!-- Category Card 2 -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="pen-tool" class="w-6 h-6 text-pink-500"></i>
                    </div>
                    <div>
                        <h3 class="font-medium">Design UI/UX</h3>
                        <p class="text-sm text-gray-500">34 cours</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i data-lucide="edit-2" class="w-5 h-5"></i>
                    </button>
                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Progression</span>
                    <span class="font-medium">65%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-pink-500 h-2 rounded-full" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <!-- Category Card 3 -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-6 h-6 text-orange-500"></i>
                    </div>
                    <div>
                        <h3 class="font-medium">Marketing Digital</h3>
                        <p class="text-sm text-gray-500">28 cours</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i data-lucide="edit-2" class="w-5 h-5"></i>
                    </button>
                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Progression</span>
                    <span class="font-medium">45%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-orange-500 h-2 rounded-full" style="width: 45%"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fin du main -->
</main>
</div>

<!-- Script pour les icônes et la navigation -->
<script>
function openAddTagModal() {
    document.getElementById('addTagModal').classList.remove('hidden');
    document.getElementById('addTagModal').classList.add('flex');
}

function closeAddTagModal() {
    document.getElementById('addTagModal').classList.remove('flex');
    document.getElementById('addTagModal').classList.add('hidden');
}

function openEditTagModal(tagId, tagName) {
    document.getElementById('editTagId').value = tagId;
    document.getElementById('editTagName').value = tagName;
    document.getElementById('editTagModal').classList.remove('hidden');
    document.getElementById('editTagModal').classList.add('flex');
}

function closeEditTagModal() {
    document.getElementById('editTagModal').classList.remove('flex');
    document.getElementById('editTagModal').classList.add('hidden');
}

function openDeleteTagModal(tagId, tagName) {
    document.getElementById('deleteTagId').value = tagId;
    document.getElementById('deleteTagName').textContent = tagName;
    document.getElementById('deleteTagModal').classList.remove('hidden');
    document.getElementById('deleteTagModal').classList.add('flex');
}

function closeDeleteTagModal() {
    document.getElementById('deleteTagModal').classList.remove('flex');
    document.getElementById('deleteTagModal').classList.add('hidden');
}

// Initialize Lucide icons
    lucide.createIcons();
    // Fonction pour changer de section
    function switchSection(sectionId) {
        // Cacher toutes les sections
        document.querySelectorAll('.section').forEach(section => {
            section.classList.remove('active');
        });
        
        // Afficher la section sélectionnée
        document.getElementById(sectionId).classList.add('active');
        
        // Mettre à jour les classes actives des onglets
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Ajouter la classe active à l'onglet sélectionné
        event.currentTarget.classList.add('active');
    }
</script>

</body>
</html>