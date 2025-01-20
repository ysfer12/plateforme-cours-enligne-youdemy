<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\Admin\TagsController;
use App\Controllers\Admin\UserController;

if(isset($_POST['submit'])) {
    $logout = new UserController();
    $logout->logout();
    exit();
}


$tagsController = new TagsController();

// Handle tag operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_tags'])) {
        $tagInput = trim($_POST['tag_names']);
        
        if (!empty($tagInput)) {
            try {
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

    if (isset($_POST['delete_tag']) && isset($_POST['tag_id'])) {
        try {
            $tagsController->deleteTag($_POST['tag_id']);
            $message = 'Tag supprimé avec succès.';
        } catch (\Exception $e) {
            $message = 'Erreur : ' . $e->getMessage();
        }
    }
}

$tags = $tagsController->getTags();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tags - Youdemy Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-transition {
            transition: all 0.3s ease;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 768px) {
            .sidebar-mini .sidebar-text {
                display: none;
            }
            .sidebar-mini {
                width: 5rem !important;
            }
            .main-content-shifted {
                margin-left: 5rem !important;
            }
            .mobile-hidden {
                display: none;
            }
            .mobile-stack {
                flex-direction: column;
            }
            .mobile-full {
                width: 100%;
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1F2937;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4B5563;
            border-radius: 2px;
        }

        .tag-card {
            transition: all 0.3s ease;
        }

        .tag-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Responsive Sidebar -->
        <aside id="sidebar" class="fixed h-full bg-gray-800 text-white shadow-xl z-20 sidebar-transition" style="width: 16rem;">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-graduation-cap text-2xl text-blue-500"></i>
                        <span class="text-2xl font-bold sidebar-text">Youdemy</span>
                    </div>
                    <!-- Mobile Toggle -->
                    <button id="sidebar-toggle" class="md:hidden text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                <p class="text-gray-400 text-sm mt-1 sidebar-text">Interface Administrateur</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6 custom-scrollbar" style="height: calc(100% - 200px); overflow-y: auto;">
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                    <i class="fas fa-th-large w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Dashboard</span>
                </a>
                <a href="Utilisateurs.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                    <i class="fas fa-users w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Utilisateurs</span>
                </a>
                <a href="Tags.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <i class="fas fa-tags w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Tags</span>
                </a>
                <a href="Categories.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                    <i class="fas fa-folder w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Catégories</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <form action="" method="POST" class="flex items-center text-gray-300 hover:text-white">
                    <button type="submit" name="submit" class="flex items-center w-full">
                        <i class="fas fa-sign-out-alt w-5 h-5"></i>
                        <span class="ml-3 sidebar-text">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="flex-1 p-4 md:p-8 transition-all duration-300" style="margin-left: 16rem;">
            <?php if (isset($message)): ?>
                <div class="mb-4 p-4 <?= strpos($message, 'Erreur') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?> rounded">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Gestion des Tags</h2>
                    <p class="text-gray-600 mt-1">Gérez les tags des cours</p>
                </div>
                <button onclick="openAddTagModal()" 
                        class="w-full md:w-auto mt-4 md:mt-0 flex items-center justify-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter des tags</span>
                </button>
            </div>

            <!-- Tags Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <?php foreach ($tags as $tag): ?>
                    <div class="tag-card bg-white rounded-xl shadow-sm p-4 md:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-hashtag text-blue-500"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium"><?= htmlspecialchars($tag->getNom()) ?></h3>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="openEditTagModal(<?= $tag->getId() ?>, '<?= htmlspecialchars($tag->getNom()) ?>')" 
                                        class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDeleteTagModal(<?= $tag->getId() ?>, '<?= htmlspecialchars($tag->getNom()) ?>')" 
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
    <!-- Add Tags Modal -->
    <div id="addTagModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl md:text-2xl font-bold">Ajouter des tags</h2>
                <button onclick="closeAddTagModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" onsubmit="return validateTagForm(this)">
                <div class="mb-4">
                    <input type="text" 
                           name="tag_names" 
                           placeholder="Entrez les tags séparés par des virgules" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="text-sm text-gray-500 mt-2">Exemple : JavaScript, Python, React, Node.js</p>
                </div>
                <div class="flex flex-col-reverse md:flex-row justify-end space-y-reverse space-y-3 md:space-y-0 md:space-x-3">
                    <button type="button" 
                            onclick="closeAddTagModal()" 
                            class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" 
                            name="add_tags"
                            class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Tag Modal -->
    <div id="editTagModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl md:text-2xl font-bold">Modifier un tag</h2>
                <button onclick="closeEditTagModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" onsubmit="return validateTagForm(this)">
                <input type="hidden" id="editTagId" name="tag_id">
                <div class="mb-4">
                    <input type="text" 
                           id="editTagName"
                           name="tag_name" 
                           placeholder="Nom du tag" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>
                <div class="flex flex-col-reverse md:flex-row justify-end space-y-reverse space-y-3 md:space-y-0 md:space-x-3">
                    <button type="button" 
                            onclick="closeEditTagModal()" 
                            class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" 
                            name="update_tag"
                            class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Tag Modal -->
    <div id="deleteTagModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl md:text-2xl font-bold">Supprimer un tag</h2>
                <button onclick="closeDeleteTagModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST">
                <input type="hidden" id="deleteTagId" name="tag_id">
                <p class="mb-4 text-gray-600">
                    Êtes-vous sûr de vouloir supprimer le tag <span id="deleteTagName" class="font-bold"></span> ?
                </p>
                <div class="flex flex-col-reverse md:flex-row justify-end space-y-reverse space-y-3 md:space-y-0 md:space-x-3">
                    <button type="button" 
                            onclick="closeDeleteTagModal()" 
                            class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" 
                            name="delete_tag"
                            class="w-full md:w-auto px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            let isSidebarExpanded = true;

            // Function to handle sidebar state
            function updateSidebarState() {
                const isMobile = window.innerWidth <= 768;
                
                if (isMobile && isSidebarExpanded) {
                    // Collapse sidebar on mobile
                    sidebar.style.width = '5rem';
                    mainContent.style.marginLeft = '5rem';
                    sidebar.classList.add('sidebar-mini');
                    isSidebarExpanded = false;
                } else if (!isMobile && !isSidebarExpanded) {
                    // Expand sidebar on desktop
                    sidebar.style.width = '16rem';
                    mainContent.style.marginLeft = '16rem';
                    sidebar.classList.remove('sidebar-mini');
                    isSidebarExpanded = true;
                }
            }

            // Toggle sidebar on mobile
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (window.innerWidth <= 768) {
                    isSidebarExpanded = !isSidebarExpanded;
                    if (!isSidebarExpanded) {
                        sidebar.style.width = '5rem';
                        mainContent.style.marginLeft = '5rem';
                        sidebar.classList.add('sidebar-mini');
                    } else {
                        sidebar.style.width = '16rem';
                        mainContent.style.marginLeft = '16rem';
                        sidebar.classList.remove('sidebar-mini');
                    }
                }
            });

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(updateSidebarState, 100);
            });

            // Initial setup
            updateSidebarState();

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && isSidebarExpanded) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        isSidebarExpanded = false;
                        sidebar.style.width = '5rem';
                        mainContent.style.marginLeft = '5rem';
                        sidebar.classList.add('sidebar-mini');
                    }
                }
            });

            // Add keyboard event listeners
            document.addEventListener('keydown', handleKeyboardNavigation);
            
            // Add click outside modal listeners
            document.addEventListener('click', handleOutsideClick);

            // Auto-hide messages
            const messages = document.querySelectorAll('.bg-green-100, .bg-red-100');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                }, 5000);
            });
        });

        // Modal Management Functions
        function openAddTagModal() {
            const modal = document.getElementById('addTagModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modal.querySelector('input[name="tag_names"]').focus();
            }, 100);
        }

        function closeAddTagModal() {
            const modal = document.getElementById('addTagModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openEditTagModal(tagId, tagName) {
            const modal = document.getElementById('editTagModal');
            document.getElementById('editTagId').value = tagId;
            document.getElementById('editTagName').value = tagName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modal.querySelector('#editTagName').focus();
            }, 100);
        }

        function closeEditTagModal() {
            const modal = document.getElementById('editTagModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openDeleteTagModal(tagId, tagName) {
            const modal = document.getElementById('deleteTagModal');
            document.getElementById('deleteTagId').value = tagId;
            document.getElementById('deleteTagName').textContent = tagName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteTagModal() {
            const modal = document.getElementById('deleteTagModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Keyboard Navigation
        function handleKeyboardNavigation(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed.flex');
                openModals.forEach(modal => {
                    const modalId = modal.id;
                    switch(modalId) {
                        case 'addTagModal':
                            closeAddTagModal();
                            break;
                        case 'editTagModal':
                            closeEditTagModal();
                            break;
                        case 'deleteTagModal':
                            closeDeleteTagModal();
                            break;
                    }
                });
            }
        }

        // Handle Click Outside Modal
        function handleOutsideClick(e) {
            const openModals = document.querySelectorAll('.fixed.flex');
            openModals.forEach(modal => {
                if (e.target === modal) {
                    const modalId = modal.id;
                    switch(modalId) {
                        case 'addTagModal':
                            closeAddTagModal();
                            break;
                        case 'editTagModal':
                            closeEditTagModal();
                            break;
                        case 'deleteTagModal':
                            closeDeleteTagModal();
                            break;
                    }
                }
            });
        }

        // Form Validation
        function validateTagForm(form) {
            const tagInput = form.querySelector('input[name="tag_names"], input[name="tag_name"]');
            const value = tagInput.value.trim();
            
            if (!value) {
                showFieldError(tagInput, 'Ce champ est requis');
                return false;
            }
            
            clearFieldError(tagInput);
            return true;
        }

        function showFieldError(field, message) {
            clearFieldError(field);
            field.classList.add('border-red-500');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-500 text-sm mt-1';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        function clearFieldError(field) {
            field.classList.remove('border-red-500');
            const errorDiv = field.parentNode.querySelector('.text-red-500');
            if (errorDiv) errorDiv.remove();
        }
    </script>
</body>
</html>