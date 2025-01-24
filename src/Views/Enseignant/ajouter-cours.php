<?php
require_once '../../../vendor/autoload.php';

use App\Config\AuthMiddleware;
use App\Controllers\Enseignant\CoursController;
use App\Controllers\Enseignant\UserController;
AuthMiddleware::checkUserRole('Enseignant');

$userController = new UserController();
if (isset($_POST['submit'])) {
    $userController->logout();
}

$courseController = new CoursController();

$utilisateur = $courseController->getUserInfo(AuthMiddleware::getUserId());
$categories = $courseController->getCategories();
$tags = $courseController->getTags();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $typeContenu = $_POST['type_contenu'] ?? '';
    $lienContenu = trim($_POST['lien_contenu'] ?? '');
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $selectedTags = $_POST['tags'] ?? [];

    $cours_id = $courseController->createCourse(
        $titre,
        $description,
        $lienContenu,
        AuthMiddleware::getUserId(), 
        $category_id,
        $typeContenu
    );

    if ($cours_id) {
        foreach ($selectedTags as $tag_id) {
            $courseController->addTagToCourse($cours_id, $tag_id);
        }
        $success = "Le cours a été créé avec succès!";
        $_POST = [];
    } else {
        $errors = $courseController->getErrorMessages();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <a href="mes-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-blue-700 rounded-lg transition duration-300">
                            <i class="fas fa-book w-5"></i>
                            <span>Mes Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="ajouter-cours.php" class="flex items-center space-x-3 py-2 px-4 bg-blue-700 rounded-lg">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Ajouter un Cours</span>
                        </a>
                    </li>
                    <li>
                    <form action="" method="post">
                            <button type="submit" name ="submit" class="flex items-center space-x-3 py-2 px-4 hover:bg-red-500 rounded-lg transition duration-300 text-red-100">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-2xl font-bold mb-6 text-gray-800">Ajouter un Nouveau Cours</h1>

                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="space-y-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre du Cours *</label>
                        <input type="text" id="titre" name="titre" required
                               value="<?= isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '' ?>"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                    </div>

                    <!-- Type de Contenu -->
                    <div>
                        <label for="type_contenu" class="block text-sm font-medium text-gray-700 mb-2">Type de Contenu *</label>
                        <select id="type_contenu" name="type_contenu" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionnez un type</option>
                            <option value="video" <?= isset($_POST['type_contenu']) && $_POST['type_contenu'] === 'video' ? 'selected' : '' ?>>Vidéo</option>
                            <option value="document" <?= isset($_POST['type_contenu']) && $_POST['type_contenu'] === 'document' ? 'selected' : '' ?>>Document</option>
                        </select>
                    </div>

                    <!-- Lien du Contenu -->
                    <div>
                        <label for="lien_contenu" class="block text-sm font-medium text-gray-700 mb-2">Lien du Contenu *</label>
                        <input type="url" id="lien_contenu" name="lien_contenu" required
                               value="<?= isset($_POST['lien_contenu']) ? htmlspecialchars($_POST['lien_contenu']) : '' ?>"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>" 
                                    <?= isset($_POST['category_id']) && $_POST['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tags -->
                    <div>
    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
    <select id="tags" name="tags[]" multiple
            class="select2 w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <?php foreach ($tags as $tag): ?>
            <option value="<?= $tag['tag_id'] ?>"
                <?= isset($_POST['tags']) && in_array($tag['tag_id'], $_POST['tags']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Ajouter le Cours
                        </button>
                    </div>
                </form>
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