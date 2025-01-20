<?php
require_once '../../../vendor/autoload.php';

use App\Config\AuthMiddleware;
use App\Controllers\Enseignant\CoursController;


// Check if the user is authenticated and has the required role (e.g., 'enseignant')
AuthMiddleware::checkUserRole('Enseignant');

// Initialize the controller
$courseController = new CoursController();

// Fetch user information
$utilisateur = $courseController->getUserInfo(AuthMiddleware::getUserId());

if (!$utilisateur) {
    die("Erreur: Impossible de récupérer les informations de l'utilisateur.");
}

// Check if course ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID du cours manquant";
    header('Location: mes-cours.php');
    exit();
}

$cours_id = (int)$_GET['id'];
echo "Debug: Course ID = $cours_id<br>"; // Debugging statement

// Fetch course details
$cours = $courseController->getCourseById($cours_id);

if (!$cours) {
    echo "Debug: Course not found<br>"; // Debugging statement
    $_SESSION['error'] = "Cours non trouvé";
    header('Location: mes-cours.php');
    exit();
}

if ($cours['enseignat_id'] != AuthMiddleware::getUserId()) {
    echo "Debug: Unauthorized access<br>"; // Debugging statement
    $_SESSION['error'] = "Accès non autorisé";
    header('Location: mes-cours.php');
    exit();
}

// Fetch categories and tags
$categories = $courseController->getCategories();
$tags = $courseController->getTags();
$courseTags = $courseController->getCourseTags($cours_id);

$erreurs = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $typeContenu = $_POST['type_contenu'] ?? '';
    $lienContenu = trim($_POST['lien_contenu'] ?? '');
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $selectedTags = $_POST['tags'] ?? [];

    // Validation
    if (empty($titre)) $erreurs[] = "Le titre est obligatoire";
    if (empty($typeContenu)) $erreurs[] = "Le type de contenu est obligatoire";
    if (empty($lienContenu)) $erreurs[] = "Le lien du contenu est obligatoire";
    if ($category_id <= 0) $erreurs[] = "La catégorie est obligatoire";

    if (empty($erreurs)) {
        if ($courseController->updateCourse(
            $cours_id,
            $titre,
            $description,
            $lienContenu,
            AuthMiddleware::getUserId(),
            $category_id,
            $typeContenu
        )) {
            // Update tags
            $courseController->updateCourseTags($cours_id, $selectedTags);

            $_SESSION['success'] = "Le cours a été mis à jour avec succès";
            header('Location: mes-cours.php');
            exit();
        } else {
            $erreurs[] = "Erreur lors de la mise à jour du cours";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-600 text-white p-6 space-y-6">
            <!-- Profile Section -->
            <div class="flex items-center space-x-4">
                <img src="../../../public/assets/depositphotos_747828354-stock-illustration-blue-circular-user-profile-icon.jpg" alt="Profile" class="w-12 h-12 rounded-full">
                <div>
                    <h2 class="text-lg font-semibold">
                        <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>
                    </h2>
                    <p class="text-sm text-gray-200">
                        <?= htmlspecialchars($utilisateur['role_titre']) ?>
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-blue-700 rounded-lg transition duration-300">
                            <i class="fas fa-home w-5"></i>
                            <span>Tableau de Bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="mes-cours.php" class="flex items-center space-x-3 py-2 px-4 bg-blue-700 rounded-lg">
                            <i class="fas fa-book w-5"></i>
                            <span>Mes Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="ajouter-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-blue-700 rounded-lg transition duration-300">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Ajouter un Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Auth/logout.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-red-500 rounded-lg transition duration-300 text-red-100">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Mes Cours</h1>
                <a href="ajouter-cours.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-plus mr-2"></i>Ajouter un Cours
                </a>
            </header>

            <?php if (isset($erreur)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Liste des cours -->
            <div class="space-y-4">
                <?php if (empty($mesCours)): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md text-center text-gray-500">
                        <p>Vous n'avez pas encore de cours.</p>
                        <a href="ajouter-cours.php" class="text-blue-600 hover:text-blue-700 mt-2 inline-block">
                            Créer votre premier cours
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($mesCours as $cours): ?>
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800">
                                        <?= htmlspecialchars($cours['titre']) ?>
                                    </h3>
                                    <p class="text-gray-600 mt-2">
                                        <?= htmlspecialchars($cours['description']) ?>
                                    </p>
                                    <div class="mt-4 flex items-center space-x-6 text-sm text-gray-500">
                                        <span>
                                            <i class="fas fa-users mr-2"></i>
                                            <?= $cours['total_inscriptions'] ?? 0 ?> inscrit(s)
                                        </span>
                                        <span>
                                            <i class="fas fa-file mr-2"></i>
                                            <?= htmlspecialchars($cours['typeContenu']) ?>
                                        </span>
                                        <span>
                                            <i class="far fa-calendar mr-2"></i>
                                            <?= date('d/m/Y', strtotime($cours['dateAjout'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="voir-cours.php?id=<?= $cours['cours_id'] ?>" 
                                       class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-300">
                                        <i class="fas fa-eye mr-2"></i>Voir
                                    </a>
                                    <a href="modifier-cours.php?id=<?= $cours['cours_id'] ?>" 
                                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">
                                        <i class="fas fa-edit mr-2"></i>Éditer
                                    </a>
                                    <button onclick="confirmDelete(<?= $cours['cours_id'] ?>)" 
                                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-300">
                                        <i class="fas fa-trash mr-2"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Modal de Suppression -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <h3 class="text-xl font-bold mb-4">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer ce cours ?</p>
            <form id="deleteForm" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="cours_id" id="deleteCoursId">
                <div class="flex justify-end space-x-4">
                    <button type="button" 
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition duration-300" 
                            onclick="closeDeleteModal()">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300">
                        Confirmer la suppression
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(coursId) {
            document.getElementById('deleteCoursId').value = coursId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Fermer le modal si on clique en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>
</body>
</html>