<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\CategoryController;

// Check admin session
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }

$categoryController = new CategoryController();

// Handle category operations
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
                    
                case 'update_status':
                    if (isset($_POST['category_id']) && isset($_POST['status'])) {
                        $categoryController->updateCategoryStatus($_POST['category_id'], $_POST['status']);
                        $message = "Statut de la catégorie mis à jour avec succès.";
                    }
                    break;
            }
        } catch (\Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

// Get all active categories
$categories = $categoryController->getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories - Youdemy Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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

            <!-- Navigation -->
            <nav class="mt-2">
                <a href="dashboard.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="20" x2="12" y2="10"/>
                        <line x1="18" y1="20" x2="18" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="16"/>
                    </svg>
                    <span>Statistiques</span>
                </a>
                <a href="Utilisateurs.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Utilisateurs</span>
                </a>
                <a href="Tags.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    <span>Tags</span>
                </a>
                <a href="Categories.php" class="tab w-full flex items-center p-4 hover:bg-gray-700 transition-colors active">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3h18v18H3zM12 8v8"/>
                        <path d="M8 12h8"/>
                    </svg>
                    <span>Catégories</span>
                </a>
            </nav>

            <!-- Déconnexion -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <a href="logout.php" class="w-full flex items-center justify-center space-x-2 text-gray-400 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
            <!-- En-tête -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Gestion des Catégories</h2>
                    <p class="text-gray-600 mt-1">Gérez les catégories de cours</p>
                </div>
            </div>

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

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($categories as $category): ?>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                                    <path d="M3 3h18v18H3zM12 8v8"/>
                                    <path d="M8 12h8"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium"><?php echo htmlspecialchars($category->getNom()); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo $category->getCoursCount(); ?> cours</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="toggleCategoryStatus(<?php echo $category->getId(); ?>, '<?php echo $category->getStatut(); ?>')"
                                    class="p-2 <?php echo $category->getStatut() === 'Actif' ? 'text-yellow-500 hover:bg-yellow-50' : 'text-green-500 hover:bg-green-50'; ?> rounded-lg transition-colors">
                                <?php if($category->getStatut() === 'Actif'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="10" y1="15" x2="10" y2="9"/>
                                        <line x1="14" y1="15" x2="14" y2="9"/>
                                    </svg>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polygon points="10 8 16 12 10 16 10 8"/>
                                    </svg>
                                <?php endif; ?>
                            </button>
                            <button onclick="confirmDelete(<?php echo $category->getId(); ?>, '<?php echo htmlspecialchars($category->getNom()); ?>')"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500">Progression</span>
                            <span class="font-medium">85%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Confirmer la suppression</h2>
                <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" id="deleteCategoryId" name="category_id">
                <p class="mb-4 text-gray-600">
                    Êtes-vous sûr de vouloir supprimer la catégorie <span id="deleteCategoryName" class="font-bold"></span> ?
                </p>
                <div class="flex justify-end space-x-2">
                    <button type="button" 
                            onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Supprimer
                    </button>
                    </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            
            // Initialize search functionality
            initializeSearch();

            // Add keyboard event listeners
            document.addEventListener('keydown', handleKeyboardNavigation);
            
            // Add click outside modal listeners
            document.addEventListener('click', handleOutsideClick);
        });

        // Category Status Toggle
        function toggleCategoryStatus(categoryId, currentStatus) {
            if (confirm(`Êtes-vous sûr de vouloir ${currentStatus === 'Actif' ? 'désactiver' : 'activer'} cette catégorie ?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="category_id" value="${categoryId}">
                    <input type="hidden" name="status" value="${currentStatus === 'Actif' ? 'Inactif' : 'Actif'}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Delete Category Functions
        function confirmDelete(categoryId, categoryName) {
            document.getElementById('deleteCategoryId').value = categoryId;
            document.getElementById('deleteCategoryName').textContent = categoryName;
            openModal('deleteModal');
        }

        function closeDeleteModal() {
            closeModal('deleteModal');
        }

        // Modal Management
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Focus first focusable element
                const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (focusable.length) focusable[0].focus();
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        // Keyboard Navigation
        function handleKeyboardNavigation(e) {
            // Close modal on Escape
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed.flex');
                openModals.forEach(modal => {
                    const modalId = modal.id;
                    closeModal(modalId);
                });
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

        // Search functionality
        function initializeSearch() {
            const searchInput = document.querySelector('#searchCategory');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(filterCategories, 300));
            }
        }

        function filterCategories() {
            const searchTerm = document.querySelector('#searchCategory').value.toLowerCase();
            const categoryCards = document.querySelectorAll('.category-card');

            categoryCards.forEach(card => {
                const categoryName = card.querySelector('.category-name').textContent.toLowerCase();
                const shouldShow = categoryName.includes(searchTerm);
                card.style.display = shouldShow ? '' : 'none';
            });
        }

        // Utility Functions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Error Handling
        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mb-4 p-4 bg-red-100 text-red-700 rounded';
            errorDiv.textContent = message;
            
            const main = document.querySelector('main');
            main.insertBefore(errorDiv, main.firstChild);

            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }

        // Success Message
        function showSuccess(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'mb-4 p-4 bg-green-100 text-green-700 rounded';
            successDiv.textContent = message;
            
            const main = document.querySelector('main');
            main.insertBefore(successDiv, main.firstChild);

            setTimeout(() => {
                successDiv.remove();
            }, 5000);
        }

        // Network error handling
        window.addEventListener('offline', () => {
            showError('La connexion au réseau a été perdue. Veuillez vérifier votre connexion internet.');
        });

        // Handle form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    showError('Veuillez remplir tous les champs requis.');
                }
            });
        });
    </script>
</body>
</html>