<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\DashboardController;

// Improved session check
// session_start();

// // Check both user_role and admin_id
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
//     // Redirect to login
//     header('Location: ../Auth/login.php');
//     exit();
// }

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
    <style>
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
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    <h1 class="text-2xl font-bold">Youdemy</h1>
                </div>
                <p class="text-gray-400 text-sm mt-1">Interface Administrateur</p>
            </div>

            <nav class="mt-6">
                <a href="dashboard.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
                <a href="Utilisateurs.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Utilisateurs
                </a>
                <a href="Tags.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    Tags
                </a>
                <a href="Categories.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
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
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Tableau de bord</h2>
                    <p class="text-gray-600 mt-1">Vue d'ensemble des statistiques</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Students Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium <?php echo getGrowthClass($growth['students']); ?> px-2 py-1 rounded">
                            <?php echo ($growth['students'] >= 0 ? '+' : '') . $growth['students']; ?>%
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4"><?php echo number_format($studentsCount); ?></h3>
                    <p class="text-gray-600">Étudiants actifs</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 <?php echo getGrowthClass($growth['students']); ?>">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                            <polyline points="17 6 23 6 23 12"/>
                        </svg>
                        <span><?php echo ($growth['students'] >= 0 ? '+' : '') . $growth['students']; ?>% ce mois</span>
                    </div>
                </div>

                <!-- Teachers Card -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium <?php echo getGrowthClass($growth['teachers']); ?> px-2 py-1 rounded">
                            <?php echo ($growth['teachers'] >= 0 ? '+' : '') . $growth['teachers']; ?>%
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4"><?php echo number_format($teachersCount); ?></h3>
                    <p class="text-gray-600">Enseignants</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 <?php echo getGrowthClass($growth['teachers']); ?>">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                            <polyline points="17 6 23 6 23 12"/>
                        </svg>
                        <span><?php echo ($growth['teachers'] >= 0 ? '+' : '') . $growth['teachers']; ?>% ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold mb-4">Activité récente</h3>
                <div class="space-y-4">
                    <?php foreach ($recentActivities as $activity): ?>
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                <?php echo $activity['type'] === 'inscription' ? 'bg-blue-100' : 'bg-purple-100'; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo $activity['type'] === 'inscription' ? 'text-blue-500' : 'text-purple-500'; ?>">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <?php if ($activity['type'] === 'inscription'): ?>
                                        <line x1="19" y1="8" x2="19" y2="14"/>
                                        <line x1="22" y1="11" x2="16" y2="11"/>
                                    <?php endif; ?>
                                </svg>
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
        // Add any JavaScript functionality here
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any interactive features
            
            // Example: Add click handler for export button
            const exportButton = document.querySelector('button');
            if (exportButton) {
                exportButton.addEventListener('click', function() {
                    // Add export functionality here
                    alert('Fonctionnalité d\'export en cours de développement');
                });
            }

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
        });
    </script>
</body>
</html>