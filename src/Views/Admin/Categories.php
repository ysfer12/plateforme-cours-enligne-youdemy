<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\UserController;

if(isset($_POST['submit'])) {
    $logout = new UserController();
    $logout->logout();
    exit();
}


$categoryController = new CategoryController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch($_POST['action']) {
                case 'delete':
                    if (isset($_POST['category_id'])) {
                        $categoryController->deleteCategory($_POST['category_id']);
                        $message = "Catégorie supprimée avec succès.";
                    }
                    break;
                case 'add':
                    $nom = trim($_POST['nom'] ?? '');
                    $description = trim($_POST['description'] ?? '');
                    $categoryController->addCategory(['nom' => $nom, 'description' => $description]);
                    $message = "Catégorie ajoutée avec succès.";
                    break;
                case 'edit':
                    if (isset($_POST['category_id'])) {
                        $categoryController->updateCategory($_POST['category_id'], [
                            'nom' => trim($_POST['nom'] ?? ''),
                            'description' => trim($_POST['description'] ?? '')
                        ]);
                        $message = "Catégorie mise à jour avec succès.";
                    }
                    break;
            }
        } catch (\Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

$categories = $categoryController->getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories - Youdemy Admin</title>
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

        .category-card {
            transition: all 0.3s ease;
        }

        .category-card:hover {
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
                <a href="Categories.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <i class="fas fa-folder w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Catégories</span>
                </a>
                <a href="Tags.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                    <i class="fas fa-tags w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Tags</span>
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
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Header with Add Button -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 space-y-4 md:space-y-0">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Gestion des Catégories</h2>
                    <p class="text-gray-600 mt-1">Gérez les catégories de cours</p>
                </div>
                <button onclick="openAddModal()" 
                        class="w-full md:w-auto flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter Catégorie
                </button>
            </div>

            <!-- Categories Grid - Responsive -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <?php foreach ($categories as $category): ?>
                <div class="category-card bg-white rounded-xl shadow-sm p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-blue-500"></i>
                            </div>
                            <div>
                                <h3 class="font-medium"><?php echo htmlspecialchars($category->getNom()); ?></h3>
                                <p class="text-sm text-gray-500">
                                    <?php 
                                    $courseCount = $categoryController->getCourseCount($category->getCategoryId());
                                    echo $courseCount . ' cours'; 
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="openEditModal(<?php echo $category->getCategoryId(); ?>, '<?php echo htmlspecialchars($category->getNom()); ?>', '<?php echo htmlspecialchars($category->getDescription()); ?>')"
                                    class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete(<?php echo $category->getCategoryId(); ?>, '<?php echo htmlspecialchars($category->getNom()); ?>')"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($category->getDescription()); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
    <!-- Add Category Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl md:text-2xl font-bold">Ajouter une Catégorie</h2>
                <button onclick="closeModal('addModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" onsubmit="return validateForm(this)">
                <input type="hidden" name="action" value="add">
                <div class="space-y-4">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom de la catégorie*
                        </label>
                        <input type="text" 
                               id="nom" 
                               name="nom" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Entrez le nom de la catégorie">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Entrez une description de la catégorie"></textarea>
                    </div>
                    <div class="flex flex-col-reverse md:flex-row justify-end space-y-reverse space-y-3 md:space-y-0 md:space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeModal('addModal')"
                                class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl md:text-2xl font-bold">Modifier la Catégorie</h2>
                <button onclick="closeModal('editModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" onsubmit="return validateForm(this)">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="category_id" id="editCategoryId">
                <div class="space-y-4">
                    <div>
                        <label for="editNom" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom de la catégorie*
                        </label>
                        <input type="text" 
                               id="editNom" 
                               name="nom" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Entrez le nom de la catégorie">
                    </div>
                    <div>
                        <label for="editDescription" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="editDescription" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Entrez une description de la catégorie"></textarea>
                    </div>
                    <div class="flex flex-col-reverse md:flex-row justify-end space-y-reverse space-y-3 md:space-y-0 md:space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeModal('editModal')"
                                class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Sauvegarder
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl md:text-2xl font-bold">Confirmer la suppression</h2>
                <button onclick="closeModal('deleteModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" id="deleteCategoryId" name="category_id">
                <p class="mb-4 text-gray-600">
                    Êtes-vous sûr de vouloir supprimer la catégorie <span id="deleteCategoryName" class="font-bold"></span> ?
                    Cette action est irréversible.
                </p>
                <div class="flex flex-col-reverse md:flex-row justify-end space-y-reverse space-y-3 md:space-y-0 md:space-x-3">
                    <button type="button" 
                            onclick="closeModal('deleteModal')" 
                            class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" 
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
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                
                // Focus first input
                const firstInput = modal.querySelector('input:not([type="hidden"])');
                if (firstInput) firstInput.focus();
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Category Management Functions
        function openAddModal() {
            document.querySelector('#nom').value = '';
            document.querySelector('#description').value = '';
            openModal('addModal');
        }

        function openEditModal(categoryId, categoryName, categoryDescription) {
            document.getElementById('editCategoryId').value = categoryId;
            document.getElementById('editNom').value = categoryName;
            document.getElementById('editDescription').value = categoryDescription;
            openModal('editModal');
        }

        function confirmDelete(categoryId, categoryName) {
            document.getElementById('deleteCategoryId').value = categoryId;
            document.getElementById('deleteCategoryName').textContent = categoryName;
            openModal('deleteModal');
        }

        // Keyboard Navigation
        function handleKeyboardNavigation(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed.flex');
                openModals.forEach(modal => closeModal(modal.id));
            }
        }

        // Handle Click Outside Modal
        function handleOutsideClick(e) {
            const openModals = document.querySelectorAll('.fixed.flex');
            openModals.forEach(modal => {
                if (e.target === modal) {
                    closeModal(modal.id);
                }
            });
        }

        // Form Validation
        function validateForm(form) {
            const nomField = form.querySelector('[name="nom"]');
            if (!nomField.value.trim()) {
                showFieldError(nomField, 'Le nom de la catégorie est requis');
                return false;
            }
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