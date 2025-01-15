<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=Youdemy", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les termes de recherche
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

    $coursParPage = 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $coursParPage;

    // Construction de la clause WHERE pour la recherche
    $params = [];

    if (!empty($search)) {
        $params[':search'] = "%$search%";
    }

    if ($category > 0) {
        $params[':category'] = $category;
    }

    // Requête pour compter le total des cours
    $countQuery = "
        SELECT COUNT(DISTINCT c.cours_id) as total 
        FROM Cours c
        LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
        LEFT JOIN Tag t ON ct.tag_id = t.tag_id
      ";

    $countStmt = $pdo->prepare($countQuery);
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalCours = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalCours / $coursParPage);

    // Récupérer les catégories pour le filtre
    $categoryQuery = "SELECT category_id, nom FROM Category ORDER BY nom";
    $categories = $pdo->query($categoryQuery)->fetchAll(PDO::FETCH_ASSOC);

    // Requête principale avec recherche
    $query = "
        SELECT DISTINCT c.*, cat.nom as category_name, 
               GROUP_CONCAT(DISTINCT t.tag_id) as tag_ids,
               GROUP_CONCAT(DISTINCT t.nom) as tag_names,
               u.prenom, u.nom as nom_enseignant
        FROM Cours c
        LEFT JOIN Category cat ON c.category_id = cat.category_id
        LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
        LEFT JOIN Tag t ON ct.tag_id = t.tag_id
        LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
        
        GROUP BY c.cours_id
        ORDER BY c.dateAjout DESC
        LIMIT :offset, :limit
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $coursParPage, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();

    $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Découvrez nos Cours</h1>

        <!-- Barre de recherche et filtres -->
        <div class="mb-8 bg-white p-6 rounded-xl shadow-md">
            <form action="" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <!-- Barre de recherche -->
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

                <!-- Filtre par catégorie -->
                <div class="md:w-1/4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="0">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" 
                                    <?= $category == $cat['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Bouton de recherche -->
                <div>
                    <button type="submit" 
                            class="w-full md:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- Message si aucun résultat -->
        <?php if (empty($cours)): ?>
            <div class="text-center py-12">
                <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-600">Aucun cours trouvé</h2>
                <p class="text-gray-500 mt-2">Essayez de modifier vos critères de recherche</p>
                <a href="?" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                    Réinitialiser la recherche
                </a>
            </div>
        <?php else: ?>

        <!-- Grille des cours -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($cours as $course): 
                $tags = [];
                if (!empty($course['tag_names'])) {
                    $tags = explode(',', $course['tag_names']);
                }
            ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
                    <!-- Image de couverture par défaut avec dégradé -->
                    <div class="relative h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-4xl"></i>
                        <div class="absolute bottom-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-medium text-blue-600">
                            <?= htmlspecialchars($course['category_name']) ?>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-3"><?= htmlspecialchars($course['titre']) ?></h2>
                        
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            <span class="ml-2 text-sm text-gray-600">
                                <?= htmlspecialchars($course['prenom'] . ' ' . $course['nom_enseignant']) ?>
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($course['description']) ?></p>

                        <?php if (!empty($tags)): ?>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach ($tags as $tag): ?>
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium">
                                    #<?= htmlspecialchars($tag) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="mt-4 flex justify-between items-center">
                            <a href="cours-details.php?id=<?= $course['cours_id'] ?>" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                <span>Voir le cours</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            <?php if (!empty($course['lienVideo'])): ?>
                                <div class="text-gray-400">
                                    <i class="fas fa-video"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center items-center space-x-4 mt-12">
            <?php 
            $queryParams = http_build_query(array_merge($_GET, ['page' => $page - 1]));
            if ($page > 1): 
            ?>
                <a href="?<?= $queryParams ?>" 
                   class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Précédent
                </a>
            <?php endif; ?>

            <div class="hidden sm:flex space-x-2">
                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                if ($start > 1) {
                    $queryParams = http_build_query(array_merge($_GET, ['page' => 1]));
                    echo "<a href='?$queryParams' class='px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50'>1</a>";
                    if ($start > 2) {
                        echo '<span class="px-3 py-2">...</span>';
                    }
                }

                for ($i = $start; $i <= $end; $i++) {
                    $queryParams = http_build_query(array_merge($_GET, ['page' => $i]));
                    if ($i == $page) {
                        echo "<span class='px-4 py-2 bg-blue-600 text-white rounded-lg'>$i</span>";
                    } else {
                        echo "<a href='?$queryParams' class='px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50'>$i</a>";
                    }
                }

                if ($end < $totalPages) {
                    if ($end < $totalPages - 1) {
                        echo '<span class="px-3 py-2">...</span>';
                    }
                    $queryParams = http_build_query(array_merge($_GET, ['page' => $totalPages]));
                    echo "<a href='?$queryParams' class='px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50'>$totalPages</a>";
                }
                ?>
            </div>

            <?php 
            $queryParams = http_build_query(array_merge($_GET, ['page' => $page + 1]));
            if ($page < $totalPages): 
            ?>
                <a href="?<?= $queryParams ?>" 
                   class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Suivant
                    <i class="fas fa-chevron-right ml-2"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>