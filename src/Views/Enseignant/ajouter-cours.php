<?php
session_start();

require_once '../../../vendor/autoload.php';

use App\Controllers\CoursController;
use App\Config\Database;

// Vérifier la connexion de l'utilisateur (enseignant)
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Auth/login.php');
    exit();
}

try {
    $database = new Database();
    $db = $database->connection();
    $courseController = new CoursController($db);

    // Récupérer les informations de l'utilisateur
    $requete = $db->prepare("
        SELECT u.*, r.titre as role_titre 
        FROM Utilisateurs u 
        JOIN Role r ON u.role_id = r.role_id
        WHERE u.id = :id
    ");
    $requete->execute([':id' => $_SESSION['user_id']]);
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

    // Récupérer les catégories
    $requeteCategories = $db->query("SELECT category_id, nom FROM Category ORDER BY nom");
    $categories = $requeteCategories->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les tags disponibles
    $requeteTags = $db->query("SELECT tag_id, nom FROM Tag ORDER BY nom");
    $tags = $requeteTags->fetchAll(PDO::FETCH_ASSOC);

    $errors = [];
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $typeContenu = $_POST['type_contenu'] ?? '';
        $lienContenu = trim($_POST['lien_contenu'] ?? '');
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
        $selectedTags = $_POST['tags'] ?? [];

        // Création du cours
        $cours_id = $courseController->createCourse(
            $titre,
            $description,
            $lienContenu,
            $_SESSION['user_id'],
            $category_id,
            $typeContenu
        );

        if ($cours_id) {
            // Ajouter les tags sélectionnés
            foreach ($selectedTags as $tag_id) {
                $courseController->addTagToCourse($cours_id, $tag_id);
            }
            $success = "Le cours a été créé avec succès!";
            // Réinitialiser le formulaire
            $_POST = [];
        } else {
            $errors = $courseController->getErrorMessages();
        }
    }

} catch (PDOException $e) {
    $errors[] = "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-600 text-white p-6 space-y-6">
            <!-- Profile Section -->
            <div class="flex items-center space-x-4">
                <img src="/api/placeholder/48/48" alt="Profile" class="w-12 h-12 rounded-full">
                <div>
                    <h2 class="text-lg font-semibold">
                        <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>
                    </h2>
                    <p class="text-sm text-gray-300">
                        <?= htmlspecialchars($utilisateur['role_titre']) ?>
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-blue-700 rounded-lg transition duration-300">
                            <span>Tableau de Bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="mes-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-blue-700 rounded-lg transition duration-300">
                            <span>Mes Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="ajouter-cours.php" class="flex items-center space-x-3 py-2 px-4 bg-blue-700 rounded-lg">
                            <span>Ajouter un Cours</span>
                        </a>
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