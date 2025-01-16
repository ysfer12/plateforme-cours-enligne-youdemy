<?php
session_start();

// Vérifier si l'utilisateur est connecté
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Configuration de la base de données
$host = "localhost";
$dbname = "Youdemy";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les paramètres de filtrage
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $coursesPerPage = 9;
    $offset = ($page - 1) * $coursesPerPage;

    // Construire la requête de base
    $params = [];

    if (!empty($search)) {
        $params[':search'] = "%$search%";
    }

    if ($categoryId > 0) {
        $whereClause .= " AND c.category_id = :category_id";
        $params[':category_id'] = $categoryId;
    }

    // Récupérer le total des cours
    $countQuery = "
        SELECT COUNT(DISTINCT c.cours_id) as total
        FROM Cours c
        LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
        LEFT JOIN Tag t ON ct.tag_id = t.tag_id
      ";

    $stmtCount = $pdo->prepare($countQuery);
    foreach ($params as $key => $value) {
        $stmtCount->bindValue($key, $value);
    }
    $stmtCount->execute();
    $totalCourses = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalCourses / $coursesPerPage);

    // Récupérer les catégories
    $queryCategories = "SELECT * FROM Category ORDER BY nom";
    $categories = $pdo->query($queryCategories)->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les cours
    $query = "
        SELECT c.*, cat.nom as category_name,
               GROUP_CONCAT(DISTINCT t.nom) as tag_names,
               u.prenom, u.nom as nom_enseignant,
               COUNT(DISTINCT i.etudiant_id) as nombre_inscrits,
               CASE WHEN ui.cours_id IS NOT NULL THEN 1 ELSE 0 END as is_inscrit
        FROM Cours c
        LEFT JOIN Category cat ON c.category_id = cat.category_id
        LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
        LEFT JOIN Tag t ON ct.tag_id = t.tag_id
        LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
        LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id
        LEFT JOIN Inscriptions ui ON c.cours_id = ui.cours_id AND ui.etudiant_id = :user_id
        GROUP BY c.cours_id
        ORDER BY c.dateAjout DESC
        LIMIT :offset, :limit";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $coursesPerPage, PDO::PARAM_INT);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="index.php" class="text-xl font-bold text-blue-600">Youdemy</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md">
                            Dashboard
                        </a>
                        <a href="catalogue.php" class="text-blue-600 px-3 py-2 rounded-md border-b-2 border-blue-600">
                            Catalogue
                        </a>
                    </div>
                </div>
                <?php if ($userId): ?>
                <div class="flex items-center">
                    <a href="dashboard.php" class="p-2 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
                <?php else: ?>
                <div class="flex items-center">
                    <a href="login.php" class="text-gray-600 hover:text-gray-800">Se connecter</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Explorer les cours</h1>
            <p class="text-gray-600">Découvrez notre sélection de cours et commencez votre apprentissage</p>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form action="" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <!-- Recherche -->
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Rechercher un cours
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="<?= htmlspecialchars($search) ?>"
                               placeholder="Rechercher par titre, description ou tag..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute right-3 top-2.5 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>

                <!-- Catégories -->
                <div class="md:w-1/4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="0">Toutes les catégories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>" 
                                    <?= $categoryId == $category['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Bouton de recherche -->
                <button type="submit" 
                        class="w-full md:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    Rechercher
                </button>

                <?php if (!empty($search) || $categoryId > 0): ?>
                    <a href="catalogue.php" 
                       class="inline-flex items-center text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times mr-2"></i>
                        Réinitialiser
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Résultats -->
        <?php if (empty($courses)): ?>
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-search text-5xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    Aucun cours trouvé
                </h2>
                <p class="text-gray-600">
                    Essayez de modifier vos critères de recherche
                </p>
            </div>
        <?php else: ?>
            <!-- Grille des cours -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($courses as $course): 
                    $tags = !empty($course['tag_names']) ? explode(',', $course['tag_names']) : [];
                ?>
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition duration-300">
                        <!-- Image/Bannière du cours -->
                        <div class="h-48 bg-gradient-to-r from-blue-600 to-purple-600 relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center text-white text-4xl opacity-25">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <?php if ($course['is_inscrit']): ?>
                                <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                                    Inscrit
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-6">
                            <!-- Catégorie -->
                            <div class="flex items-center mb-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    <?= htmlspecialchars($course['category_name']) ?>
                                </span>
                            </div>

                            <!-- Titre et description -->
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                <?= htmlspecialchars($course['titre']) ?>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4">
                                <?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...
                            </p>

                            <!-- Tags -->
                            <?php if (!empty($tags)): ?>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                        #<?= htmlspecialchars($tag) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Infos supplémentaires -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    <?= $course['nombre_inscrits'] ?> inscrits
                                </div>
                                <?php if (!empty($course['lienVideo'])): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-video mr-2"></i>
                                        Vidéo incluse
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Enseignant -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">
                                        <?= htmlspecialchars($course['prenom'] . ' ' . $course['nom_enseignant']) ?>
                                    </span>
                                </div>
                                <a href="cours-details.php?id=<?= $course['cours_id'] ?>" 
                                   class="text-blue-600 hover:text-blue-800">
                                    En savoir plus →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center space-x-4 mt-8">
                <?php
                $queryParams = $_GET;
                if ($page > 1): 
                    $queryParams['page'] = $page - 1;
                ?>
                    <a href="?<?= http_build_query($queryParams) ?>" 
                       class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-left mr-2"></i>
                        Précédent
                    </a>
                <?php endif; ?>

                <!-- Numéros de page -->
                <div class="hidden sm:flex space-x-2">
                    <?php
                    // Calculer la plage de pages à afficher
                    $start = max(1, $page - 2);
                    $end = min($totalPages, $page + 2);

                    // Première page
                    if ($start > 1):
                        $queryParams['page'] = 1;
                    ?>
                        <a href="?<?= http_build_query($queryParams) ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            1
                        </a>
                        <?php if ($start > 2): ?>
                            <span class="px-3 py-2">...</span>
                        <?php endif;
                    endif;

                    // Pages numérotes
                    for ($i = $start; $i <= $end; $i++):
                        $queryParams['page'] = $i;
                        if ($i == $page):
                    ?>
                        <span class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a href="?<?= http_build_query($queryParams) ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?= $i ?>
                        </a>
                    <?php 
                        endif;
                    endfor;

                    // Dernière page
                    if ($end < $totalPages):
                        if ($end < $totalPages - 1): ?>
                            <span class="px-3 py-2">...</span>
                        <?php endif;
                        $queryParams['page'] = $totalPages;
                    ?>
                        <a href="?<?= http_build_query($queryParams) ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?= $totalPages ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php 
                if ($page < $totalPages):
                    $queryParams['page'] = $page + 1;
                ?>
                    <a href="?<?= http_build_query($queryParams) ?>" 
                       class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Suivant
                        <i class="fas fa-chevron-right ml-2"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Section d'aide -->
        <div class="mt-12 bg-white rounded-xl shadow-sm p-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Besoin d'aide pour choisir ?
                </h2>
                <p class="text-gray-600 mb-6">
                    Notre équipe est là pour vous guider dans le choix de vos cours 
                    et répondre à toutes vos questions.
                </p>
                <a href="contact.php" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-envelope mr-2"></i>
                    Nous contacter
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Logo et description -->
                <div>
                    <h3 class="text-lg font-bold text-blue-600 mb-4">Youdemy</h3>
                    <p class="text-gray-600">
                        Plateforme d'apprentissage en ligne pour développer vos compétences.
                    </p>
                </div>

                <!-- Liens rapides -->
                <div>
                    <h4 class="text-gray-900 font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="catalogue.php" class="text-gray-600 hover:text-gray-900">
                                Tous les cours
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-gray-900">
                                Catégories
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-gray-900">
                                Enseignants
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-gray-900">
                                Blog
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-gray-900 font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-envelope w-5"></i>
                            support@youdemy.com
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-5"></i>
                            +33 1 23 45 67 89
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t mt-8 pt-8 text-center text-gray-600">
                <p>&copy; <?= date('Y') ?> Youdemy. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Script pour la soumission automatique du formulaire lors du changement de catégorie -->
    <script>
        document.getElementById('category').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>
</html>