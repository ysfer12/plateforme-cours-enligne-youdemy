<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\Auth\AuthController;
use App\Models\Admin\UserModel;
use App\Controllers\Admin\UserController;

if(isset($_POST['submit'])) {
    $logout = new UserController();
    $logout->logout();
    exit();
}


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

// Get users with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$users = $userModel->getActiveUsers($page, $perPage);
$totalUsers = $userModel->getTotalActiveUsers();
$totalPages = ceil($totalUsers / $perPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Youdemy Admin</title>
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

        /* Responsive table styles */
        @media (max-width: 768px) {
            .responsive-table {
                display: block;
                width: 100%;
                overflow-x: auto;
            }

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

        /* Custom scrollbar for sidebar */
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

        /* Table styles */
        .table-container {
            position: relative;
            overflow: auto;
            background:
                linear-gradient(to right, white 30%, rgba(255,255,255,0)),
                linear-gradient(to right, rgba(255,255,255,0), white 70%) 100% 0,
                linear-gradient(to right, rgba(0,0,0,0.2), rgba(0,0,0,0) 20%),
                linear-gradient(to left, rgba(0,0,0,0.2), rgba(0,0,0,0) 20%) 100% 0;
            background-repeat: no-repeat;
            background-size: 40px 100%, 40px 100%, 20px 100%, 20px 100%;
            background-attachment: local, local, scroll, scroll;
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
                <a href="Utilisateurs.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <i class="fas fa-users w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Utilisateurs</span>
                </a>
                <a href="Tags.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
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
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h2>
                    <p class="text-gray-600 mt-1">Gestion des comptes étudiants et enseignants</p>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="table-container">
                    <table class="w-full min-w-[800px]">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left p-4 font-medium text-gray-600">Utilisateur</th>
                                <th class="text-left p-4 font-medium text-gray-600 mobile-hidden">Email</th>
                                <th class="text-left p-4 font-medium text-gray-600">Rôle</th>
                                <th class="text-left p-4 font-medium text-gray-600">Statut</th>
                                <th class="text-left p-4 font-medium text-gray-600 mobile-hidden">Date d'ajout</th>
                                <th class="text-left p-4 font-medium text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="border-t hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">
                                                <?php echo htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()); ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 mobile-hidden"><?php echo htmlspecialchars($user->getEmail()); ?></td>
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
                                <td class="p-4 mobile-hidden">
                                    <?php echo $user->getDateAjout() ? date('d/m/Y', strtotime($user->getDateAjout())) : 'N/A'; ?>
                                </td>
                                <td class="p-4">
                                    <div class="flex space-x-2">
                                        <?php if ($user->getRole()->getTitre() !== 'Admin'): ?>
                                            <button onclick="toggleUserStatus(<?php echo $user->getId(); ?>, '<?php echo $user->getStatut(); ?>')"
                                                    class="p-2 <?php echo $user->getStatut() === 'Actif' ? 'text-yellow-500 hover:bg-yellow-50' : 'text-green-500 hover:bg-green-50'; ?> rounded-lg transition-colors">
                                                <i class="fas <?php echo $user->getStatut() === 'Actif' ? 'fa-pause' : 'fa-play'; ?>"></i>
                                            </button>
                                            <button onclick="confirmDelete(<?php echo $user->getId(); ?>, '<?php echo htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()); ?>')"
                                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                        <p class="text-gray-500 text-sm text-center md:text-left">
                            Affichage de <?php echo ($page - 1) * $perPage + 1; ?>-<?php echo min($page * $perPage, $totalUsers); ?> 
                            sur <?php echo $totalUsers; ?> utilisateurs
                        </p>
                        <div class="flex flex-wrap justify-center gap-2">
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
                    <i class="fas fa-times"></i>
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
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
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
            sidebarToggle.addEventListener('click', function() {
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
                // Prevent body scroll when modal is open
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                // Restore body scroll
                document.body.style.overflow = '';
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.fixed.flex');
                modals.forEach(modal => closeModal(modal.id));
            }
        });

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modals = document.querySelectorAll('.fixed.flex');
            modals.forEach(modal => {
                if (e.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        // Success and Error message auto-hide
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.bg-green-100, .bg-red-100');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>