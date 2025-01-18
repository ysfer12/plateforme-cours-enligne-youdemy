<?php
session_start();

// Database Configuration
$host = "localhost";
$dbname = "Youdemy";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$courseId) {
        header('Location: index.php');
        exit;
    }

    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Main Course Query
    $query = "
        SELECT c.*, 
               cat.nom as category_name,
               GROUP_CONCAT(DISTINCT t.nom) as tag_names,
               u.prenom, u.nom as nom_enseignant, u.email as email_enseignant,
               COUNT(DISTINCT i.etudiant_id) as nombre_inscrits,
               TIMESTAMPDIFF(MONTH, c.dateAjout, CURRENT_TIMESTAMP) as months_since_creation
        FROM Cours c
        LEFT JOIN Category cat ON c.category_id = cat.category_id
        LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
        LEFT JOIN Tag t ON ct.tag_id = t.tag_id
        LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
        LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id
        WHERE c.cours_id = :cours_id 
        GROUP BY c.cours_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cours_id', $courseId);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        header('Location: index.php');
        exit;
    }

    $tags = !empty($course['tag_names']) ? explode(',', $course['tag_names']) : [];

    // Check Enrollment
    $isEnrolled = false;
    if ($userId) {
        $enrollQuery = "SELECT COUNT(*) as enrolled FROM Inscriptions 
                       WHERE cours_id = :cours_id AND etudiant_id = :etudiant_id";
        $enrollStmt = $pdo->prepare($enrollQuery);
        $enrollStmt->bindParam(':cours_id', $courseId);
        $enrollStmt->bindParam(':etudiant_id', $userId);
        $enrollStmt->execute();
        $isEnrolled = $enrollStmt->fetch(PDO::FETCH_ASSOC)['enrolled'] > 0;
    }

    // Fetch Similar Courses
    $similarQuery = "
        SELECT c.cours_id, c.titre, c.description, cat.nom as category_name,
               u.prenom, u.nom as nom_enseignant
        FROM Cours c
        LEFT JOIN Category cat ON c.category_id = cat.category_id
        LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
        WHERE c.category_id = :category_id 
        AND c.cours_id != :cours_id
        LIMIT 3";
    
    $similarStmt = $pdo->prepare($similarQuery);
    $similarStmt->bindParam(':category_id', $course['category_id']);
    $similarStmt->bindParam(':cours_id', $courseId);
    $similarStmt->execute();
    $similarCourses = $similarStmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['titre']) ?> - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50 font-inter">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="index.php" class="text-2xl font-bold text-gray-800">Youdemy</a>
                <nav class="hidden md:flex space-x-6">
                    <a href="index.php" class="text-gray-600 hover:text-blue-600 transition">Formations</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">Catégories</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">À propos</a>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-blue-600 transition">
                    <i data-feather="search" class="w-5 h-5"></i>
                </button>
                <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Connexion
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Course Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-2xl p-12 mb-12 grid md:grid-cols-2 gap-8 items-center">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                        <?= htmlspecialchars($course['category_name']) ?>
                    </span>
                    <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-sm">
                            #<?= htmlspecialchars(trim($tag)) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($course['titre']) ?></h1>
                <p class="text-xl opacity-90 mb-6"><?= htmlspecialchars($course['description']) ?></p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white/10 rounded-xl p-4">
                        <div class="text-2xl font-bold"><?= $course['nombre_inscrits'] ?></div>
                        <div class="text-sm opacity-75">Étudiants</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4">
                        <div class="text-2xl font-bold"><?= $course['months_since_creation'] ?> mois</div>
                        <div class="text-sm opacity-75">Durée</div>
                    </div>
                    <?php if (!empty($course['lienVideo'])): ?>
                    <div class="bg-white/10 rounded-xl p-4">
                        <div class="text-2xl font-bold"><i data-feather="video"></i></div>
                        <div class="text-sm opacity-75">Vidéo incluse</div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (!$isEnrolled): ?>
                    <div class="flex space-x-4">
                        <form action="inscription_cours.php" method="POST" class="flex-grow">
                            <input type="hidden" name="cours_id" value="<?= $course['cours_id'] ?>">
                            <button type="submit" class="w-full bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition font-semibold">
                                S'inscrire maintenant
                            </button>
                        </form>
                        <?php if (!empty($course['lienVideo'])): ?>
                            <a href="<?= htmlspecialchars($course['lienVideo']) ?>" 
                               target="_blank" 
                               class="bg-transparent border border-white text-white px-6 py-3 rounded-lg hover:bg-white/10 transition font-semibold flex items-center">
                                <i data-feather="play-circle" class="mr-2"></i>
                                Aperçu
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-green-500/20 text-green-300 px-6 py-3 rounded-lg flex items-center">
                        <i data-feather="check-circle" class="mr-3"></i>
                        Vous êtes inscrit à cette formation
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="hidden md:block">
                <div class="bg-white/10 rounded-2xl p-8 space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                            <i data-feather="user" class="w-8 h-8 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold"><?= htmlspecialchars($course['prenom'] . ' ' . $course['nom_enseignant']) ?></h3>
                            <p class="text-sm opacity-75"><?= htmlspecialchars($course['email_enseignant']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Course Content -->
        <section class="grid md:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-8">
                <?php if ($isEnrolled): ?>
                    <!-- Course Curriculum -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold mb-6">Programme de Formation</h2>
                        <?php if (!empty($course['content'])): ?>
                            <div class="prose max-w-none text-gray-700">
                                <?= nl2br(htmlspecialchars($course['content'])) ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">Le programme détaillé sera bientôt disponible.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Similar Courses -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Formations similaires</h2>
                    <div class="grid md:grid-cols-3 gap-4">
                        <?php foreach ($similarCourses as $similarCourse): ?>
                            <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition">
                                <h3 class="font-semibold text-lg mb-2">
                                    <a href="cours-details.php?id=<?= $similarCourse['cours_id'] ?>" 
                                       class="hover:text-blue-600 transition">
                                        <?= htmlspecialchars($similarCourse['titre']) ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    <?= htmlspecialchars(substr($similarCourse['description'], 0, 100)) ?>...
                                </p>
                                <div class="flex items-center">
                                    <i data-feather="user" class="w-4 h-4 mr-2 text-gray-500"></i>
                                    <span class="text-sm text-gray-600">
                                        <?= htmlspecialchars($similarCourse['prenom'] . ' ' . $similarCourse['nom_enseignant']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6 sticky top-20 self-start">
                <!-- Course Details Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">Détails de la Formation</h3>
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <i data-feather="calendar" class="w-5 h-5 mr-3 text-blue-600"></i>
                            <span>Créé le <?= date('d/m/Y', strtotime($course['dateAjout'])) ?></span>
                        </li>
                        <li class="flex items-center">
                            <i data-feather="users" class="w-5 h-5 mr-3 text-blue-600"></i>
                            <span><?= $course['nombre_inscrits'] ?> étudiants inscrits</span>
                        </li>
                        <li class="flex items-center">
                            <i data-feather="folder" class="w-5 h-5 mr-3 text-blue-600"></i>
                            <span>Catégorie: <?= htmlspecialchars($course['category_name']) ?></span>
                        </li>
                    </ul>
                </div>

                <!-- Support and Help -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">
                        <i data-feather="life-buoy" class="w-5 h-5 mr-3 text-blue-600"></i>
                        Support et aide
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Des questions sur le cours ? Contactez l'enseignant directement.
                    </p>
                    <a href="mailto:<?= htmlspecialchars($course['email_enseignant']) ?>" 
                       class="flex items-center gap-2 text-blue-600 hover:text-blue-800">
                        <i data-feather="mail"></i>
                        <span>Contacter l'enseignant</span>
                    </a>
                </div>
            </div>
        </section>
        
<!-- Tags Section -->
<?php if (!empty($tags)): ?>
<div class="mt-12 bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6">Tags associés</h2>
    <div class="flex flex-wrap gap-3">
        <?php foreach ($tags as $tag): ?>
            <a href="catalogue.php?tag=<?= urlencode($tag) ?>" 
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full transition duration-300">
                #<?= htmlspecialchars($tag) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<!-- Footer -->
<footer class="mt-12 text-center text-gray-600 py-8 border-t">
    <p class="mb-4">© <?= date('Y') ?> Youdemy - Tous droits réservés</p>
    <div class="flex justify-center gap-6">
        <a href="#" class="text-gray-600 hover:text-gray-800 transition duration-300">
            Conditions d'utilisation
        </a>
        <a href="#" class="text-gray-600 hover:text-gray-800 transition duration-300">
            Politique de confidentialité
        </a>
        <a href="#" class="text-gray-600 hover:text-gray-800 transition duration-300">
            Contact
        </a>
    </div>
</footer>
</body>
</html>