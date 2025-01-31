<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\TagsController;

// Check admin session
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }

$tagsController = new TagsController();

// Handle tag operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add multiple tags
    if (isset($_POST['add_tags'])) {
        $tagInput = trim($_POST['tag_names']);
        
        if (!empty($tagInput)) {
            try {
                // Split tags by comma, trim whitespace, and remove empty entries
                $tagNames = array_filter(array_map('trim', explode(',', $tagInput)));
                
                $addedTags = 0;
                foreach ($tagNames as $tagName) {
                    if (!empty($tagName)) {
                        $tagsController->addTag($tagName);
                        $addedTags++;
                    }
                }
                
                $message = $addedTags . ' tag(s) ajouté(s) avec succès.';
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

// Fetch all tags
$tags = $tagsController->getTags();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tags - Youdemy Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tab.active {
            background-color: rgb(55 65 81);
            border-left: 4px solid rgb(59 130 246);
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
                <a href="dashboard.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="bar-chart-2" class="mr-3 w-5 h-5"></i>
                    <span>Statistiques</span>
                </a>
                <a href="Utilisateurs.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <i data-lucide="users" class="mr-3 w-5 h-5"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="Tags.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors active">
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
            <!-- En-tête de section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Gestion des Tags</h2>
                    <p class="text-gray-600 mt-1">Gérez les tags des cours</p>
                </div>
                <button onclick="openAddTagModal()" class="flex items-center space-x-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Ajouter des tags</span>
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
                <?php foreach ($tags as $tag): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="hash" class="w-6 h-6 text-blue-500"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium"><?= htmlspecialchars($tag->getNom()) ?></h3>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="openEditTagModal(<?= $tag->getId() ?>, '<?= htmlspecialchars($tag->getNom()) ?>')" 
                                        class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i data-lucide="edit-2" class="w-5 h-5"></i>
                                    Modifier
                                </button>
                                <button onclick="openDeleteTagModal(<?= $tag->getId() ?>, '<?= htmlspecialchars($tag->getNom()) ?>')" 
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Modal for Adding Tags -->
            <div id="addTagModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
                <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Ajouter des tags</h2>
                        <button onclick="closeAddTagModal()" class="text-gray-500 hover:text-gray-700">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                    <form method="POST">
                        <input type="text" 
                               name="tag_names" 
                               placeholder="Entrez les tags séparés par des virgules" 
                               class="w-full border rounded p-2 mb-2"
                               required>
                        <p class="text-sm text-gray-500 mb-4">Exemple : JavaScript, Python, React, Node.js</p>
                        <div class="flex justify-end space-x-2">
                            <button type="button" 
                                    onclick="closeAddTagModal()" 
                                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                Annuler
                            </button>
                            <button type="submit" 
                                    name="add_tags"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
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
                        <input type="text" 
                               id="editTagName"
                               name="tag_name" 
                               placeholder="Nom du tag" 
                               class="w-full border rounded p-2 mb-4"
                               required>
                        <div class="flex justify-end space-x-2">
                            <button type="button" 
                                    onclick="closeEditTagModal()" 
                                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                Annuler
                            </button>
                            <button type="submit" 
                                    name="update_tag"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
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
                        <p class="mb-4 text-gray-600">
                            Êtes-vous sûr de vouloir supprimer le tag <span id="deleteTagName" class="font-bold"></span> ?
                        </p>
                        <div class="flex justify-end space-x-2">
                            <button type="button" 
                                    onclick="closeDeleteTagModal()" 
                                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                Annuler
                            </button>
                            <button type="submit" 
                                    name="delete_tag"
                                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                Supprimer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            
            // Initialize event listeners for search and filter
            initializeSearch();
        });

        // Modal management functions
        function openAddTagModal() {
            document.getElementById('addTagModal').classList.remove('hidden');
            document.getElementById('addTagModal').classList.add('flex');
            // Clear the input field
            document.querySelector('input[name="tag_names"]').value = '';
            // Focus on input
            setTimeout(() => {
                document.querySelector('input[name="tag_names"]').focus();
            }, 100);
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
            // Focus on input
            setTimeout(() => {
                document.getElementById('editTagName').focus();
            }, 100);
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

    </script>
</body>
</html>