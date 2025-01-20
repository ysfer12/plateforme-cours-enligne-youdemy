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
               TIMESTAMPDIFF(MONTH, c.dateAjout, CURRENT_TIMESTAMP) as months_since_creation,
               c.typeContenu,
               c.lienContenu
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
    <title><?= htmlspecialchars($course['titre']) ?> - LearnHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .content-overlay {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(8px);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Modern Fixed Header -->
    <header class="fixed w-full z-50 glass-effect border-b border-gray-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="index.php" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i data-feather="book-open" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        LearnHub
                    </span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-blue-600 transition-all">Formations</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 transition-all">Catégories</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 transition-all">Blog</a>
                </nav>

                <!-- Auth Section -->
                <div class="flex items-center space-x-4">
                    <?php if ($userId): ?>
                        <!-- User Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-all">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center">
                                    <i data-feather="user" class="w-5 h-5 text-blue-600"></i>
                                </div>
                                <i data-feather="chevron-down" class="w-4 h-4"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Mon profil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Mes cours</a>
                                <hr class="my-2 border-gray-100">
                                <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Déconnexion
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="../Auth/login.php" class="text-gray-700 hover:text-blue-600 transition-all">Connexion</a>
                        <a href="../Auth/register.php" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
                            S'inscrire
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-16">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-20 px-4">
            <!-- Decorative Background -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0H11.03zm32.284 0L49.8 6.485 48.384 7.9l-7.9-7.9h2.83zM16.686 0L10.2 6.485 11.616 7.9l7.9-7.9h-2.83zM22.344 0L13.858 8.485 15.272 9.9l9.9-9.9h-2.828zM32.57 0L22.344 10.227 23.758 11.64l10.827-10.826h-2.015zm8.485 0L29.828 11.227l1.414 1.414L42.47 0h-1.415zM56.97 0L44.343 12.627l1.414 1.414L58.385 1.414 56.97 0zM3.03 0L.616 2.414l2.828 2.83L15.272 17.07l1.414-1.414L4.444 3.414 3.03 0zm50.912 0L52.93 2.414l2.827 2.83L67.585 17.07l1.414-1.414L55.757 3.414 53.942 0zM25.172 0L36 10.828 37.414 9.414 26.586 0h-1.414zM.284 0L0 .283v1.414L11.414 13.11l1.414-1.413L2 .83.284 0zM39.03 0L40.442 1.414 52.857 13.83l1.415-1.414L42.443 0h-3.414zm-30.886 0l12.85 12.85 1.414-1.414L9.294 0h-1.15zm12.85 0l12.85 12.85 1.415-1.414L21.308 0h-1.15zm12.85 0l12.85 12.85 1.415-1.414L33.322 0h-1.15zm12.85 0L58.86 12.85l1.414-1.414L45.336 0h-1.15z\' fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');"></div>
            </div>

            <!-- Content -->
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Course Info -->
                    <div>
                        <!-- Category & Tags -->
                        <div class="flex flex-wrap items-center gap-3 mb-6">
                            <span class="px-4 py-1.5 bg-white/20 rounded-full text-white text-sm font-medium">
                                <?= htmlspecialchars($course['category_name']) ?>
                            </span>
                            <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                                <span class="px-4 py-1.5 bg-white/10 rounded-full text-white/90 text-sm">
                                    #<?= htmlspecialchars(trim($tag)) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>

                        <!-- Title & Description -->
                        <h1 class="text-4xl font-bold text-white mb-6 leading-tight">
                            <?= htmlspecialchars($course['titre']) ?>
                        </h1>
                        <p class="text-xl text-white/90 mb-8 leading-relaxed">
                            <?= htmlspecialchars($course['description']) ?>
                        </p>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-3 gap-6 mb-8">
                            <div class="bg-white/10 rounded-2xl p-4">
                                <div class="text-3xl font-bold text-white mb-1">
                                    <?= $course['nombre_inscrits'] ?>
                                </div>
                                <div class="text-sm text-white/80">Étudiants</div>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4">
                                <div class="text-3xl font-bold text-white mb-1">
                                    <?= $course['months_since_creation'] ?>
                                </div>
                                <div class="text-sm text-white/80">Mois</div>
                            </div>
                            <?php if (!empty($course['typeContenu'])): ?>
                            <div class="bg-white/10 rounded-2xl p-4">
                                <div class="text-2xl font-bold text-white mb-1">
                                    <i data-feather="<?= $course['typeContenu'] === 'video' ? 'video' : 'file-text' ?>" 
                                       class="w-8 h-8"></i>
                                </div>
                                <div class="text-sm text-white/80">
                                    <?= $course['typeContenu'] === 'video' ? 'Vidéo' : 'Document' ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <?php if (!$userId): ?>
                        <div class="flex gap-4">
                            <a href="../Auth/login.php" 
                               class="flex-grow flex items-center justify-center px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg">
                                <i data-feather="log-in" class="w-5 h-5 mr-2"></i>
                                Se connecter
                            </a>
                        </div>
                        <?php elseif (!$isEnrolled): ?>
                        <form action="inscription_cours.php" method="POST" class="flex-grow">
                            <input type="hidden" name="cours_id" value="<?= $course['cours_id'] ?>">
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg">
                                <i data-feather="user-plus" class="w-5 h-5 mr-2"></i>
                                S'inscrire maintenant
                            </button>
                        </form>
                        <?php else: ?>
                        <div class="bg-green-500/20 rounded-xl p-4 flex items-center">
                            <i data-feather="check-circle" class="w-6 h-6 mr-3 text-green-300"></i>
                            <span class="text-lg font-medium text-white">Vous êtes inscrit à cette formation</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Instructor Card -->
                    <div class="hidden md:block">
                        <div class="bg-white/10 rounded-2xl p-8 backdrop-blur-sm">
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-xl flex items-center justify-center">
                                    <i data-feather="user" class="w-8 h-8 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white">
                                        <?= htmlspecialchars($course['prenom'] . ' ' . $course['nom_enseignant']) ?>
                                    </h3>
                                    <p class="text-white/80"><?= htmlspecialchars($course['email_enseignant']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Course Content Section -->
        <?php if ($userId && $isEnrolled && !empty($course['lienContenu'])): ?>
        <section class="max-w-7xl mx-auto px-4 py-12">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800">Contenu du cours</h2>
                </div>
                
                <div class="p-6">
                    <?php if ($course['typeContenu'] === 'video'): ?>
                    <!-- Video Content -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i data-feather="video" class="w-5 h-5 mr-2 text-blue-600"></i>
                            <span>Contenu vidéo</span>
                        </h3>
                        <div class="relative w-full bg-black rounded-xl overflow-hidden" style="padding-top: 56.25%;">
                            <iframe 
                                src="<?= htmlspecialchars($course['lienContenu']) ?>" 
                                class="absolute top-0 left-0 w-full h-full"
                                frameborder="0"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    <?php elseif ($course['typeContenu'] === 'document'): ?>
                    <!-- Document Content -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i data-feather="file-text" class="w-5 h-5 mr-2 text-blue-600"></i>
                            <span>Document du cours</span>
                        </h3>
                        <div class="bg-gray-50 rounded-xl overflow-hidden" style="height: 800px;">
                            <iframe 
                                src="<?= htmlspecialchars($course['lienContenu']) ?>" 
                                class="w-full h-full"
                                frameborder="0">
                            </iframe>
                        </div>
                        <!-- Download Button -->
                        <div class="mt-4 flex justify-center">
                            <a href="<?= htmlspecialchars($course['lienContenu']) ?>" 
                               target="_blank"
                               download
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg">
                                <i data-feather="download" class="w-5 h-5 mr-2"></i>
                                Télécharger le document
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php elseif (!$userId): ?>
        <section class="max-w-7xl mx-auto px-4 py-12">
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <i data-feather="lock" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Contenu réservé aux membres</h3>
                <p class="text-gray-600 mb-6 text-lg">Connectez-vous pour accéder au contenu complet de ce cours.</p>
                <a href="../Auth/login.php" 
                   class="inline-flex items-center px-8 py-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg">
                    <i data-feather="log-in" class="w-5 h-5 mr-2"></i>
                    Se connecter
                </a>
            </div>
        </section>
        <?php elseif (!$isEnrolled): ?>
        <section class="max-w-7xl mx-auto px-4 py-12">
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <i data-feather="lock" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Contenu réservé aux inscrits</h3>
                <p class="text-gray-600 mb-6 text-lg">Inscrivez-vous au cours pour accéder au contenu complet.</p>
                <form action="inscription_cours.php" method="POST" class="inline-block">
                    <input type="hidden" name="cours_id" value="<?= $course['cours_id'] ?>">
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg">
                        <i data-feather="user-plus" class="w-5 h-5 mr-2"></i>
                        S'inscrire maintenant
                    </button>
                </form>
            </div>
        </section>
        <?php endif; ?>

        <!-- Similar Courses Section -->
        <section class="max-w-7xl mx-auto px-4 py-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Formations similaires</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($similarCourses as $similarCourse): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all hover:shadow-xl card-hover">
                        <div class="p-6">
                            <div class="mb-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">
                                    <?= htmlspecialchars($similarCourse['category_name']) ?>
                                </span>
                            </div>
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="cours-details.php?id=<?= $similarCourse['cours_id'] ?>" 
                                   class="text-gray-800 hover:text-blue-600 transition-colors">
                                    <?= htmlspecialchars($similarCourse['titre']) ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?= htmlspecialchars($similarCourse['description']) ?>
                            </p>
                            <div class="flex items-center text-gray-500">
                                <i data-feather="user" class="w-4 h-4 mr-2"></i>
                                <span class="text-sm">
                                    <?= htmlspecialchars($similarCourse['prenom'] . ' ' . $similarCourse['nom_enseignant']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Tags Section -->
        <?php if (!empty($tags)): ?>
        <section class="max-w-7xl mx-auto px-4 py-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Tags associés</h2>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($tags as $tag): ?>
                        <a href="catalogue.php?tag=<?= urlencode($tag) ?>" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                            #<?= htmlspecialchars($tag) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-xl font-bold text-gray-800 mb-4">LearnHub</h4>
                    <p class="text-gray-600">Apprenez, grandissez, réussissez.</p>
                </div>
                <div>
                    <h5 class="font-semibold text-gray-800 mb-4">Formations</h5>
                    <nav class="flex flex-col space-y-2">
                        <a href="#" class="text-gray-600 hover:text-blue-600">Développement</a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">Design</a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">Marketing</a>
                    </nav>
                </div>
                <div>
                    <h5 class="font-semibold text-gray-800 mb-4">À propos</h5>
                    <nav class="flex flex-col space-y-2">
                        <a href="#" class="text-gray-600 hover:text-blue-600">Notre mission</a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">Nos instructeurs</a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">Contact</a>
                    </nav>
                </div>
                <div>
                    <h5 class="font-semibold text-gray-800 mb-4">Légal</h5>
                    <nav class="flex flex-col space-y-2">
                        <a href="#" class="text-gray-600 hover:text-blue-600">CGV</a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">Politique de confidentialité</a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 py-6">
            <div class="max-w-7xl mx-auto px-4 text-center text-gray-600">
                © <?= date('Y') ?> LearnHub. Tous droits réservés.
            </div>
        </div>
    </footer>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>