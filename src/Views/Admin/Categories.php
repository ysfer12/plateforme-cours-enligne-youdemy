<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\Admin\CategoryController;

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

// Get all categories
$categories = $categoryController->getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories - Youdemy Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed w-64 h-full bg-blue-700 text-white shadow-xl z-10">
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    <h1 class="text-2xl font-bold">Youdemy</h1>
                </div>
                <p class="text-gray-400 text-sm mt-1">Interface Administrateur</p>
            </div>

            <nav class="mt-6">
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
                <a href="Categories.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                        <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/>
                    </svg>
                    Catégories
                </a>
            </nav>

            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <a href="logout.php" class="flex items-center text-gray-300 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Déconnexion
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
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
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Gestion des Catégories</h2>
                    <p class="text-gray-600 mt-1">Gérez les catégories de cours</p>
                </div>
                <button onclick="openAddModal()" 
                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter Catégorie
                </button>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($categories as $category): ?>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                                    <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/>
                                </svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                </svg>
                            </button>
                            <button onclick="confirmDelete(<?php echo $category->getCategoryId(); ?>, '<?php echo htmlspecialchars($category->getNom()); ?>')"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                </svg>
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
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Ajouter une Catégorie</h2>
                <button onclick="closeModal('addModal')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
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
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeModal('addModal')"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- Edit Category Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Modifier la Catégorie</h2>
                <button onclick="closeModal('editModal')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
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
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeModal('editModal')"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Sauvegarder
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Confirmer la suppression</h2>
                <button onclick="closeModal('deleteModal')" class="text-gray-500 hover:text-gray-700">
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
                    Cette action est irréversible.
                </p>
                <div class="flex justify-end space-x-2">
                    <button type="button" 
                            onclick="closeModal('deleteModal')" 
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
    document.addEventListener('DOMContentLoaded', () => {
        // Add keyboard event listeners
        document.addEventListener('keydown', handleKeyboardNavigation);
        
        // Add click outside modal listeners
        document.addEventListener('click', handleOutsideClick);
    });

    // Modal Management Functions
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
            // Return focus to the trigger element
            const trigger = document.activeElement;
            if (trigger) {
                trigger.focus();
            }
        }
    }

    // Add/Edit Category Functions
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

    // Delete Category Functions
    function confirmDelete(categoryId, categoryName) {
        document.getElementById('deleteCategoryId').value = categoryId;
        document.getElementById('deleteCategoryName').textContent = categoryName;
        openModal('deleteModal');
    }

    // Keyboard Navigation
    function handleKeyboardNavigation(e) {
        // Close modal on Escape
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.fixed.flex');
            openModals.forEach(modal => {
                closeModal(modal.id);
            });
        }

        // Handle Tab key for focus trapping in modal
        if (e.key === 'Tab') {
            const openModal = document.querySelector('.fixed.flex');
            if (openModal) {
                trapFocus(openModal, e);
            }
        }
    }

    // Focus Trap
    function trapFocus(element, event) {
        const focusableEls = element.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        const firstFocusableEl = focusableEls[0];
        const lastFocusableEl = focusableEls[focusableEls.length - 1];

        if (event) {
            if (event.shiftKey && event.key === 'Tab') {
                if (document.activeElement === firstFocusableEl) {
                    event.preventDefault();
                    lastFocusableEl.focus();
                }
            } else if (event.key === 'Tab') {
                if (document.activeElement === lastFocusableEl) {
                    event.preventDefault();
                    firstFocusableEl.focus();
                }
            }
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
        errorDiv.className = 'text-red-500 text-sm mt-1 field-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function clearFieldError(field) {
        field.classList.remove('border-red-500');
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }
    </script>
</body>
</html>