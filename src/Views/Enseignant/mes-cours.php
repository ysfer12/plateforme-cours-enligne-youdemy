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

// Récupérer les cours de l'enseignant
try {
    $requeteCours = $connexion->prepare("
        SELECT c.*, COUNT(i.id) as total_inscriptions
        FROM Cours c
        LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id
        WHERE c.enseignat_id = :id
        GROUP BY c.cours_id
        ORDER BY c.dateAjout DESC
    ");
    $requeteCours->execute([':id' => $_SESSION['user_id']]);
    $mesCours = $requeteCours->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $erreur = "Erreur de récupération des cours : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours - Plateforme Enseignant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
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
                        <a href="mes-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out active:bg-primary-500">
                            <i class="fas fa-book w-5"></i>
                            <span>Mes Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="ajouter-cours.php" class="flex items-center space-x-3 py-2 px-4 hover:bg-primary-500 rounded-lg transition duration-300 ease-in-out">
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
        <main class="flex-1 p-6 overflow-y-auto">
            <!-- Header -->
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Mes Cours</h1>
                <a href="ajouter-cours.php" class="bg-primary-500 text-red px-4 py-2 rounded-lg hover:bg-primary-600 transition duration-300 ease-in-out">
                    Ajouter un Cours
                </a>
            </header>

            <?php if (isset($erreur)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <!-- Cours List -->
            <div class="space-y-4">
                <?php if (empty($mesCours)): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md text-center text-red-500">
                        Vous n'avez pas encore ajouté de cours.
                    </div>
                <?php else: ?>
                    <?php foreach ($mesCours as $cours): ?>
                        <!-- Course Item -->
                        <div class="bg-white p-4 rounded-lg shadow-md flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <?= htmlspecialchars($cours['titre']) ?>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    <?= htmlspecialchars($cours['description']) ?>
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Inscrits : <?= htmlspecialchars($cours['total_inscriptions'] ?? 0) ?>
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="voir-cours.php?id=<?= htmlspecialchars($cours['cours_id']) ?>" 
                                   class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition duration-300 ease-in-out">
                                    Voir
                                </a>
                                <a href="modifier-cours.php?id=<?= htmlspecialchars($cours['cours_id']) ?>" 
                                   class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-primary-600 transition duration-300 ease-in-out">
                                    Éditer
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>