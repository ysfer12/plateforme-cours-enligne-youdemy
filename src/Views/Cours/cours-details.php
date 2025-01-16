<?php
session_start();

// Configuration de la base de données
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

    // Requête principale
    $query = "
        SELECT c.*, 
               cat.nom as category_name,
               GROUP_CONCAT(DISTINCT t.tag_id) as tag_ids,
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

    // Vérifier l'inscription
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <a href="index.php" class="inline-flex items-center text-gray-700 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au catalogue
            </a>
        </div>
    </nav>

    <!-- En-tête du cours avec image de fond -->
    <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-purple-900 text-white overflow-hidden">
        <!-- Motif de fond -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%23ffffff\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');"></div>
        </div>

        <!-- Contenu de l'en-tête -->
        <div class="container mx-auto px-4 py-16 relative">
            <div class="max-w-4xl">
                <!-- Catégorie et tags -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="px-4 py-1 bg-white/20 rounded-full text-sm font-medium">
                        <i class="fas fa-folder mr-2"></i>
                        <?= htmlspecialchars($course['category_name']) ?>
                    </span>
                    <?php foreach ($tags as $tag): ?>
                        <span class="px-4 py-1 bg-white/10 rounded-full text-sm">
                            #<?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>

                <h1 class="text-5xl font-bold mb-6"><?= htmlspecialchars($course['titre']) ?></h1>
                <p class="text-xl opacity-90 mb-8"><?= htmlspecialchars($course['description']) ?></p>

                <!-- Métriques du cours -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white/10 rounded-xl p-4">
                        <div class="text-2xl font-bold"><?= $course['nombre_inscrits'] ?></div>
                        <div class="text-sm opacity-75">Étudiants inscrits</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4">
                        <div class="text-2xl font-bold">
                            <?= $course['months_since_creation'] ?> mois
                        </div>
                        <div class="text-sm opacity-75">Depuis la création</div>
                    </div>
                    <?php if (!empty($course['lienVideo'])): ?>
                    <div class="bg-white/10 rounded-xl p-4">
                        <div class="text-2xl font-bold"><i class="fas fa-video"></i></div>
                        <div class="text-sm opacity-75">Vidéo incluse</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="md:col-span-2 space-y-8">
                <?php if ($isEnrolled): ?>
                    <!-- Contenu du cours -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold mb-6">Contenu du cours</h2>
                        <?php if (!empty($course['content'])): ?>
                            <div class="prose max-w-none">
                                <?= nl2br(htmlspecialchars($course['content'])) ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">Aucun contenu disponible pour le moment.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Informations sur l'enseignant -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">À propos de l'enseignant</h2>
                    <div class="flex items-start">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-xl font-semibold">
                                <?= htmlspecialchars($course['prenom'] . ' ' . $course['nom_enseignant']) ?>
                            </h3>
                            <p class="text-gray-600 mb-4">
                                <?= htmlspecialchars($course['email_enseignant']) ?>
                            </p>
                            <a href="mailto:<?= htmlspecialchars($course['email_enseignant']) ?>" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-700">
                                <i class="fas fa-envelope mr-2"></i>
                                Contacter l'enseignant
                            </a>
                        </div>
                    </div>
                </div>
            </div>

<!-- Barre latérale -->
<div class="space-y-6">
                <!-- Carte d'inscription -->
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-20">
                    <?php if ($isEnrolled): ?>
                        <div class="bg-green-50 rounded-xl p-4 mb-4">
                            <div class="flex items-center text-green-700">
                                <i class="fas fa-check-circle text-2xl mr-3"></i>
                                <div>
                                    <p class="font-semibold">Vous êtes inscrit</p>
                                    <p class="text-sm">Accédez au contenu du cours</p>
                                </div>
                            </div>
                        </div>
                        <a href="#contenu" 
                           class="block w-full bg-blue-600 text-white text-center py-4 rounded-xl hover:bg-blue-700 transition duration-300">
                            Continuer le cours
                        </a>
                    <?php else: ?>
                        <?php if ($userId): ?>
                            <form action="inscription_cours.php" method="POST">
                                <input type="hidden" name="cours_id" value="<?= $course['cours_id'] ?>">
                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition duration-300">
                                    S'inscrire au cours
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="text-center">
                                <p class="text-gray-600 mb-4">Connectez-vous pour vous inscrire à ce cours</p>
                                <a href="../Auth/login.php" 
                                   class="block w-full bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition duration-300">
                                    Se connecter
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Ressources du cours -->
                    <?php if (!empty($course['lienVideo'])): ?>
                        <div class="mt-6 p-6 bg-gray-50 rounded-xl">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-play-circle mr-2 text-blue-600"></i>
                                Vidéo du cours
                            </h3>
                            <a href="<?= htmlspecialchars($course['lienVideo']) ?>" 
                               target="_blank"
                               class="flex items-center justify-center gap-2 text-blue-600 hover:text-blue-800 bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 transition duration-300">
                                <i class="fas fa-video"></i>
                                <span>Regarder la vidéo</span>
                                <i class="fas fa-external-link-alt text-sm"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Informations supplémentaires -->
                    <div class="mt-6 p-6 bg-gray-50 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Informations du cours
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-gray-600">
                                <i class="fas fa-calendar-alt w-5"></i>
                                <span>Créé le <?= date('d/m/Y', strtotime($course['dateAjout'])) ?></span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-600">
                                <i class="fas fa-users w-5"></i>
                                <span><?= $course['nombre_inscrits'] ?> étudiants inscrits</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-600">
                                <i class="fas fa-folder w-5"></i>
                                <span>Catégorie: <?= htmlspecialchars($course['category_name']) ?></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Support et aide -->
                    <div class="mt-6 p-6 bg-gray-50 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4">
                            <i class="fas fa-life-ring mr-2 text-blue-600"></i>
                            Support et aide
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Des questions sur le cours ? Contactez l'enseignant directement.
                        </p>
                        <a href="mailto:<?= htmlspecialchars($course['email_enseignant']) ?>" 
                           class="flex items-center gap-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-envelope"></i>
                            <span>Contacter l'enseignant</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des tags -->
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

        <!-- Autres cours suggérés -->
        <div class="mt-12 bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Vous pourriez aussi aimer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                // Requête pour les cours similaires (même catégorie)
                $similarQuery = "
                    SELECT c.cours_id, c.titre, c.description, cat.nom as category_name
                    FROM Cours c
                    LEFT JOIN Category cat ON c.category_id = cat.category_id
                    WHERE c.category_id = :category_id 
                    AND c.cours_id != :cours_id
                    LIMIT 3";
                
                $similarStmt = $pdo->prepare($similarQuery);
                $similarStmt->bindParam(':category_id', $course['category_id']);
                $similarStmt->bindParam(':cours_id', $courseId);
                $similarStmt->execute();
                $similarCourses = $similarStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($similarCourses as $similarCourse):
                ?>
                    <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition duration-300">
                        <h3 class="font-semibold text-lg mb-2">
                            <a href="cours-details.php?id=<?= $similarCourse['cours_id'] ?>" 
                               class="hover:text-blue-600 transition duration-300">
                                <?= htmlspecialchars($similarCourse['titre']) ?>
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            <?= htmlspecialchars(substr($similarCourse['description'], 0, 100)) ?>...
                        </p>
                        <span class="text-xs text-gray-500">
                            <?= htmlspecialchars($similarCourse['category_name']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

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
    </div>

    <!-- Scripts -->
    <script>
        // Animation de défilement fluide
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animation de la barre de navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 0) {
                nav.classList.add('shadow');
            } else {
                nav.classList.remove('shadow');
            }
        });
    </script>
</body>
</html>