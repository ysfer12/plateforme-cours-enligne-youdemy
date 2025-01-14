<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\Auth\AuthController;
use App\Models\UserModel;

// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }

$userModel = new UserModel();
$authController = new AuthController();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $userId = $_POST['user_id'];
        
        try {
            switch($_POST['action']) {
                case 'delete':
                    // Soft delete - update dateSuppression
                    $currentDate = date('Y-m-d H:i:s');
                    $userModel->softDelete($userId, $currentDate);
                    $message = "Utilisateur supprimé avec succès.";
                    break;
                    
                case 'toggleStatus':
                    $newStatus = $_POST['current_status'] === 'Actif' ? 'Inactif' : 'Actif';
                    $userModel->updateStatus($userId, $newStatus);
                    $message = "Statut mis à jour avec succès.";
                    break;
            }
        } catch (\Exception $e) {
            $error = "Erreur: " . $e->getMessage();
        }
    }
}

// Get users with pagination (excluding soft deleted users)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$users = $userModel->getActiveUsers($page, $perPage); // Only get non-deleted users
$totalUsers = $userModel->getTotalActiveUsers(); // Count of non-deleted users
$totalPages = ceil($totalUsers / $perPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Youdemy Admin</title>
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

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h2>
                    <p class="text-gray-600 mt-1">Gestion des comptes étudiants et enseignants</p>
                </div>
            </div>

            <!-- Users Table -->

                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left p-4 font-medium text-gray-600">Utilisateur</th>
                            <th class="text-left p-4 font-medium text-gray-600">Email</th>
                            <th class="text-left p-4 font-medium text-gray-600">Rôle</th>
                            <th class="text-left p-4 font-medium text-gray-600">Statut</th>
                            <th class="text-left p-4 font-medium text-gray-600">Date d'ajout</th>
                            <th class="text-left p-4 font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="border-t hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i data-lucide="user" class="w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">
                                            <?php echo htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()); ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4"><?php echo htmlspecialchars($user->getEmail()); ?></td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                    <?php echo htmlspecialchars($user->getRole()->getTitre()); ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="<?php echo $user->getStatut() === 'Actif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> px-3 py-1 rounded-full text-sm">
                                    <?php echo htmlspecialchars($user->getStatut()); ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <?php echo $user->getDateAjout() ? date('d/m/Y', strtotime($user->getDateAjout())) : 'N/A'; ?>
                            </td>
                            <td class="p-4">
    <div class="flex space-x-2">
        <?php if ($user->getRole()->getTitre() !== 'Admin'): ?>
            <button onclick="toggleUserStatus(<?php echo $user->getId(); ?>, '<?php echo $user->getStatut(); ?>')"
                    class="p-2 <?php echo $user->getStatut() === 'Actif' ? 'text-yellow-500 hover:bg-yellow-50' : 'text-green-500 hover:bg-green-50'; ?> rounded-lg transition-colors">
                <?php if($user->getStatut() === 'Actif'): ?>
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
            <button onclick="confirmDelete(<?php echo $user->getId(); ?>, '<?php echo htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()); ?>')"
                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
            </button>
        <?php endif; ?>
    </div>
</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-4 border-t">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-500 text-sm">
                            Affichage de <?php echo ($page - 1) * $perPage + 1; ?>-<?php echo min($page * $perPage, $totalUsers); ?> 
                            sur <?php echo $totalUsers; ?> utilisateurs
                        </p>
                        <div class="flex space-x-2">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>" 
                                   class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors">
                                    Précédent
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>" 
                                   class="px-3 py-1 border rounded <?php echo $i === $page ? 'bg-blue-500 text-white' : 'hover:bg-gray-50'; ?> transition-colors">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?php echo $page + 1; ?>" 
                                   class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors">
                                    Suivant
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Confirmer la suppression</h2>
                <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" id="deleteUserId" name="user_id">
                <p class="mb-4 text-gray-600">
                    Êtes-vous sûr de vouloir supprimer l'utilisateur <span id="deleteUserName" class="font-bold"></span> ?
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

            // Add event listeners for keyboard navigation
            document.addEventListener('keydown', handleKeyboardNavigation);

            // Add event listeners for click outside modals
            document.addEventListener('click', handleOutsideClick);
        });

        // User Status Toggle
        function toggleUserStatus(userId, currentStatus) {
            if (confirm(`Êtes-vous sûr de vouloir ${currentStatus === 'Actif' ? 'désactiver' : 'activer'} cet utilisateur ?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="toggleStatus">
                    <input type="hidden" name="user_id" value="${userId}">
                    <input type="hidden" name="current_status" value="${currentStatus}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Delete User Functions
        function confirmDelete(userId, userName) {
            document.getElementById('deleteUserId').value = userId;
            document.getElementById('deleteUserName').textContent = userName;
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

        // Search and Filter Functions
        function initializeSearch() {
            const searchInput = document.querySelector('#searchUser');
            const roleFilter = document.querySelector('#roleFilter');

            if (searchInput) {
                searchInput.addEventListener('input', debounce(filterUsers, 300));
            }

            if (roleFilter) {
                roleFilter.addEventListener('change', filterUsers);
            }
        }

        function filterUsers() {
            const searchTerm = document.querySelector('#searchUser').value.toLowerCase();
            const roleFilter = document.querySelector('#roleFilter').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const userName = row.querySelector('td:first-child').textContent.toLowerCase();
                const userEmail = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const userRole = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
                const matchesRole = !roleFilter || userRole.includes(roleFilter);

                row.style.display = matchesSearch && matchesRole ? '' : 'none';
            });

            updateRowStripes();
        }

        // Update row stripes after filtering
        function updateRowStripes() {
            const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])');
            visibleRows.forEach((row, index) => {
                row.classList.toggle('bg-gray-50', index % 2 === 0);
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

        // Form Validation
        function validateForm(formElement) {
            const requiredFields = formElement.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            return isValid;
        }

        // Event listener for form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                    showError('Veuillez remplir tous les champs requis.');
                }
            });
        });

        // Handle network errors
        window.addEventListener('offline', () => {
            showError('La connexion au réseau a été perdue. Veuillez vérifier votre connexion internet.');
        });

        document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    </script>
</body>
</html>