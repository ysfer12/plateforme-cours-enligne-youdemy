<?php
require_once '../../../vendor/autoload.php';

use App\Config\AuthMiddleware;
use App\Controllers\Enseignant\CoursController;

// Vérifier si l'utilisateur est connecté et a le rôle "enseignant"
AuthMiddleware::checkUserRole('Enseignant');

try {
    // Initialiser le contrôleur
    $courseController = new CoursController();

    // Récupérer l'ID de l'utilisateur connecté
    $userId = AuthMiddleware::getUserId();

    // Récupérer les informations de l'utilisateur connecté
    $utilisateur = $courseController->getUserInfo($userId);
    if (!$utilisateur) {
        $_SESSION['error'] = "Utilisateur non trouvé.";
        header('Location: mes-cours.php');
        exit();
    }

    // Récupérer l'ID du cours à modifier
    if (!isset($_GET['id'])) {
        $_SESSION['error'] = "ID du cours manquant.";
        header('Location: mes-cours.php');
        exit();
    }
    $cours_id = (int)$_GET['id'];

    // Récupérer les détails du cours
    $cours = $courseController->getCourseById($cours_id);
    if (!$cours || $cours['enseignat_id'] != $userId) {
        $_SESSION['error'] = "Cours non trouvé ou accès non autorisé.";
        header('Location: mes-cours.php');
        exit();
    }

    // Récupérer les catégories et les tags disponibles
    $categories = $courseController->getCategories();
    $tags = $courseController->getTags();

    // Récupérer les tags actuels du cours
    $courseTags = $courseController->getCourseTags($cours_id);

    // Gérer la soumission du formulaire
    $erreurs = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $typeContenu = $_POST['type_contenu'] ?? '';
        $lienContenu = trim($_POST['lien_contenu'] ?? '');
        $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
        $selectedTags = $_POST['tags'] ?? [];

        // Validation des champs
        if (empty($titre)) $erreurs[] = "Le titre est obligatoire.";
        if (empty($typeContenu)) $erreurs[] = "Le type de contenu est obligatoire.";
        if (empty($lienContenu)) $erreurs[] = "Le lien du contenu est obligatoire.";
        if ($category_id <= 0) $erreurs[] = "La catégorie est obligatoire.";

        // Valider l'URL du contenu
        if (!filter_var($lienContenu, FILTER_VALIDATE_URL)) {
            $erreurs[] = "Le lien du contenu n'est pas valide.";
        }

        // Mettre à jour le cours si aucune erreur
        if (empty($erreurs)) {
            if ($courseController->updateCourse(
                $cours_id,
                $titre,
                $description,
                $lienContenu,
                $userId,
                $category_id,
                $typeContenu
            )) {
                // Supprimer tous les tags actuels du cours
                $courseController->removeAllTagsFromCourse($cours_id);
                // Ajouter les nouveaux tags sélectionnés
                if (!empty($selectedTags)) {
                    foreach ($selectedTags as $tag_id) {
                        $courseController->addTagToCourse($cours_id, $tag_id);
                    }
                }

                $_SESSION['success'] = "Le cours a été mis à jour avec succès.";
                header('Location: mes-cours.php');
                exit();
            } else {
                $erreurs = array_merge($erreurs, $courseController->getErrorMessages());
            }
        }
    }
} catch (Exception $e) {
    $erreurs[] = "Une erreur est survenue : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">
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
        <main class="flex-1 p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier le Cours</h1>

                    <?php if (!empty($erreurs)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <?php foreach ($erreurs as $erreur): ?>
                                <p><?= htmlspecialchars($erreur) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <!-- Titre -->
                        <div>
                            <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre du Cours *
                            </label>
                            <input type="text" 
                                   id="titre" 
                                   name="titre" 
                                   value="<?= htmlspecialchars($cours['titre'] ?? '') ?>" 
                                   required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($cours['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Type de Contenu -->
                        <div>
                            <label for="type_contenu" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de Contenu *
                            </label>
                            <select id="type_contenu" 
                                    name="type_contenu" 
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                <option value="video" <?= ($cours['typeContenu'] ?? '') === 'video' ? 'selected' : '' ?>>
                                    Vidéo
                                </option>
                                <option value="document" <?= ($cours['typeContenu'] ?? '') === 'document' ? 'selected' : '' ?>>
                                    Document
                                </option>
                            </select>
                        </div>

                        <!-- Lien du Contenu -->
                        <div>
                            <label for="lien_contenu" class="block text-sm font-medium text-gray-700 mb-2">
                                Lien du Contenu *
                            </label>
                            <input type="url" 
                                   id="lien_contenu" 
                                   name="lien_contenu" 
                                   value="<?= htmlspecialchars($cours['lienContenu'] ?? '') ?>" 
                                   required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie *
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionnez une catégorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['category_id'] ?>"
                                            <?= ($cours['category_id'] ?? '') == $category['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

<!-- Tags -->
<div>
    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
        Tags
    </label>
    <select id="tags" 
            name="tags[]" 
            multiple
            class="select2 w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
        <?php foreach ($tags as $tag): ?>
            <option value="<?= $tag['tag_id'] ?>"
                    <?= in_array($tag['tag_id'], $courseTags) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>                        <!-- Boutons -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="mes-cours.php" 
                               class="px-6 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition duration-300">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                Mettre à jour le cours
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Sélectionnez des tags",
                allowClear: true
            });
        });
    </script>
</body>
</html>