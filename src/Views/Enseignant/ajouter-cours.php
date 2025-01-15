<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'Youdemy';
$username = 'root';
$password = '';

try {
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier la connexion de l'utilisateur (enseignant)
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Auth/login.php');
    exit();
}

// Récupérer les informations de l'utilisateur
try {
    $requete = $connexion->prepare("
        SELECT u.*, r.titre as role_titre 
        FROM Utilisateurs u 
        JOIN Role r ON u.role_id = r.role_id
        WHERE u.id = :id
    ");
    $requete->execute([':id' => $_SESSION['user_id']]);
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $erreur = "Erreur de récupération des informations : " . $e->getMessage();
}

// Récupérer les catégories
try {
    $requeteCategories = $connexion->query("SELECT category_id, nom FROM Category ORDER BY nom");
    $categories = $requeteCategories->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $erreur = "Erreur de récupération des catégories : " . $e->getMessage();
}

// Traitement du formulaire d'ajout de cours
$erreurs = [];
$succes = '';
$titre = '';
$description = '';
$type_contenu = '';
$lien_contenu = '';
$category_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et valider les données
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type_contenu = $_POST['type_contenu'] ?? '';
    $lien_contenu = trim($_POST['lien_contenu'] ?? '');
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

    // Validation
    if (empty($titre)) {
        $erreurs[] = "Le titre est obligatoire";
    }

    if (empty($type_contenu)) {
        $erreurs[] = "Le type de contenu est obligatoire";
    }

    if ($category_id <= 0) {
        $erreurs[] = "Veuillez sélectionner une catégorie";
    }

    // Si pas d'erreurs, ajouter le cours
    if (empty($erreurs)) {
        try {
            $requete = $connexion->prepare(
                "INSERT INTO Cours 
                (titre, description, typeContenu, lienContenu, enseignat_id, category_id) 
                VALUES 
                (:titre, :description, :type_contenu, :lien_contenu, :enseignat_id, :category_id)"
            );

            $resultat = $requete->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':type_contenu' => $type_contenu,
                ':lien_contenu' => $lien_contenu,
                ':enseignat_id' => $_SESSION['user_id'],
                ':category_id' => $category_id
            ]);

            if ($resultat) {
                $succes = "Le cours a été ajouté avec succès";
                // Réinitialiser les variables
                $titre = $description = $type_contenu = $lien_contenu = $category_id = '';
            }
        } catch(PDOException $e) {
            $erreurs[] = "Erreur d'ajout de cours : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <p class="text-sm text-primary-200">
                        <?= htmlspecialchars($utilisateur['role_titre']) ?>
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out">
                            <i class="fas fa-home w-5"></i>
                            <span>Tableau de Bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="mes-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out">
                            <i class="fas fa-book w-5"></i>
                            <span>Mes Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="ajouter-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out active:bg-primary-500">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Ajouter un Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="parametres.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out">
                            <i class="fas fa-cog w-5"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li>
                        <a href="deconnexion.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-red-500 rounded-lg transition duration-300 ease-in-out text-red-100">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6 text-center">Ajouter un Nouveau Cours</h1>

                <?php if (!empty($erreurs)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <?php foreach ($erreurs as $erreur): ?>
                            <p><?php echo htmlspecialchars($erreur); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($succes)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <?php echo htmlspecialchars($succes); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="" class="space-y-4">
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700">Titre du Cours *</label>
                        <input type="text" name="titre" id="titre" 
                               value="<?php echo htmlspecialchars($titre); ?>" 
                               required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo htmlspecialchars($description); ?></textarea>
                    </div>

                    <div>
                        <label for="type_contenu" class="block text-sm font-medium text-gray-700">Type de Contenu *</label>
                        <select name="type_contenu" id="type_contenu" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Sélectionnez le type de contenu</option>
                            <option value="video" <?= $type_contenu === 'video' ? 'selected' : '' ?>>Vidéo</option>
                            <option value="document" <?= $type_contenu === 'document' ? 'selected' : '' ?>>Document</option>
                            <option value="presentation" <?= $type_contenu === 'presentation' ? 'selected' : '' ?>>Présentation</option>
                            <option value="autre" <?= $type_contenu === 'autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="lien_contenu" class="block text-sm font-medium text-gray-700">Lien du Contenu</label>
                        <input type="url" name="lien_contenu" id="lien_contenu" 
                               value="<?php echo htmlspecialchars($lien_contenu); ?>" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                               placeholder="URL du contenu (optionnel)">
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                        <select name="category_id" id="category_id" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?php echo $categorie['category_id']; ?>" <?= $category_id == $categorie['category_id'] ? 'selected' : '' ?>>
                                    <?php echo htmlspecialchars($categorie['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <button type="submit" 
                                class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            Ajouter le Cours
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html> 