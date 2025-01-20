<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\UserController;

if(isset($_POST['submit'])) {
    $logout = new UserController();
    $logout->logout();
    exit();
}

// Initialize controller
$dashboardController = new DashboardController();

// Get statistics
$studentsCount = $dashboardController->getActiveStudentsCount();
$teachersCount = $dashboardController->getActiveTeachersCount();

// Get growth percentages
$growth = $dashboardController->getGrowthPercentages();

// Get recent activities
$recentActivities = $dashboardController->getRecentActivities();

// Helper function for growth class
function getGrowthClass($percentage) {
    return $percentage >= 0 ? 'text-green-500 bg-green-100' : 'text-red-500 bg-red-100';
}

function formatTimeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 60) {
        return "Il y a quelques secondes";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return "Il y a " . $minutes . " minute" . ($minutes > 1 ? 's' : '');
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "Il y a " . $hours . " heure" . ($hours > 1 ? 's' : '');
    } else {
        $days = floor($diff / 86400);
        return "Il y a " . $days . " jour" . ($days > 1 ? 's' : '');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Youdemy Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
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

        /* Sidebar responsive styles */
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
            .sidebar-mini .profile-info {
                display: none;
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
                    <button id="sidebar-toggle" class="md:hidden text-white">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <p class="text-gray-400 text-sm mt-1 sidebar-text">Interface Administrateur</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6 custom-scrollbar" style="height: calc(100% - 200px); overflow-y: auto;">
                <a href="dashboard.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <i class="fas fa-th-large w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Dashboard</span>
                </a>
                <a href="Utilisateurs.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-users w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Utilisateurs</span>
                </a>
                <a href="Tags.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-tags w-5 h-5"></i>
                    <span class="ml-3 sidebar-text">Tags</span>
                </a>
                <a href="Categories.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
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

        <!-- Main Content Area -->
        <main id="main-content" class="flex-1 p-8 transition-all duration-300" style="margin-left: 16rem;">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Tableau de bord</h2>
                    <p class="text-gray-600 mt-1">Vue d'ensemble des statistiques</p>
                </div>
            </div>

            <!-- Statistics Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Students Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-graduate text-xl text-blue-500"></i>
                        </div>
                        <span class="text-sm font-medium <?php echo getGrowthClass($growth['students']); ?> px-2 py-1 rounded">
                            <?php echo ($growth['students'] >= 0 ? '+' : '') . $growth['students']; ?>%
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4"><?php echo number_format($studentsCount); ?></h3>
                    <p class="text-gray-600">Étudiants actifs</p>
                </div>

                <!-- Teachers Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-xl text-purple-500"></i>
                        </div>
                        <span class="text-sm font-medium <?php echo getGrowthClass($growth['teachers']); ?> px-2 py-1 rounded">
                            <?php echo ($growth['teachers'] >= 0 ? '+' : '') . $growth['teachers']; ?>%
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4"><?php echo number_format($teachersCount); ?></h3>
                    <p class="text-gray-600">Enseignants</p>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold mb-4">Activité récente</h3>
                <div class="space-y-4">
                    <?php foreach ($recentActivities as $activity): ?>
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                <?php echo $activity['type'] === 'inscription' ? 'bg-blue-100' : 'bg-purple-100'; ?>">
                                <i class="fas fa-user-plus text-<?php echo $activity['type'] === 'inscription' ? 'blue' : 'purple'; ?>-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium">
                                    <?php echo htmlspecialchars("{$activity['prenom']} {$activity['nom']}"); ?> 
                                    s'est inscrit(e) en tant que <?php echo strtolower(htmlspecialchars($activity['role'])); ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <?php echo formatTimeAgo($activity['date']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
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

            // Add hover effects for stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

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
    </script>
</body>
</html>