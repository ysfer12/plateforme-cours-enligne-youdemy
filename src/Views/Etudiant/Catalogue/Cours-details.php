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
    <title><?= htmlspecialchars($course['titre']) ?> - Youdemy</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
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
<!-- Navigation Bar -->
<nav class="fixed w-full z-50 glass-effect border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo with Gradient -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                </div>
                <span class="text-xl md:text-2xl font-bold gradient-text">Youdemy</span>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-blue-600 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Enhanced Search Bar - Hidden on Mobile -->
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <div class="relative w-full">
                    <input type="text" 
                           placeholder="Que souhaitez-vous apprendre aujourd'hui ?" 
                           class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pl-12">
                    <div class="absolute left-4 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="absolute right-2 top-1 px-4 py-1 bg-blue-600 text-white rounded-full text-sm hover:bg-blue-700 transition">
                        Rechercher
                    </button>
                </div>
            </div>

            <!-- Navigation Links - Hidden on Mobile -->
            <div class="hidden md:flex items-center space-x-8">
                <div class="hidden md:flex items-center space-x-6">
                    <a href="../Admin/Catalogue/Cours.php" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
                        <i class="fas fa-book-open text-sm"></i>
                        <span>Catalogue</span>
                    </a>
                    <div class="relative group">
                        <a href="#" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
                            <i class="fas fa-th-large text-sm"></i>
                            <span>Qui sommes nous?</span>
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 focus:outline-none">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <i class="fas fa-chevron-down text-xs group-hover:rotate-180 transition-transform duration-200"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg py-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200">
                        <a href="../home.php" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-book-reader mr-2"></i>
                            Mes cours
                        </a>
                        <hr class="my-2 border-gray-100">
                        <form method="POST">
                            <button type="submit" name="submit" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </form> 
                        </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu - Hidden by Default -->
        <div id="mobile-menu" class="hidden md:hidden bg-white pb-4 absolute top-16 left-0 right-0 border-b border-gray-200 shadow-lg">
            <!-- Mobile Search -->
            <div class="px-4 pt-2 pb-3">
                <div class="relative">
                    <input type="text" 
                           placeholder="Rechercher..." 
                           class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pl-12">
                    <div class="absolute left-4 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Links -->
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="../Catalogue/Cours.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                    <i class="fas fa-book-open mr-2"></i>
                    Catalogue
                </a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                    <i class="fas fa-th-large mr-2"></i>
                    Qui sommes nous?
                </a>
                <!-- Mobile Profile Section -->
                <div class="pt-4 space-y-2">
                    <div class="px-3 py-2 text-sm text-gray-500">Mon compte</div>
                    <a href="../Admin/dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-book-reader mr-2"></i>
                        Mes cours
                    </a>
                    <form method="POST">
                        <button type="submit" name="submit" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
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

    <!-- Enhanced Footer with Newsletter -->
    <footer class="bg-gray-800 text-gray-300">
        <!-- Newsletter Section -->
        <div class="border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="max-w-3xl mx-auto text-center">
                    <h3 class="text-2xl font-bold text-white mb-3">Restez informé de nos nouveautés</h3>
                    <p class="text-gray-400 mb-6">
                        Recevez nos meilleures offres et conseils pédagogiques directement dans votre boîte mail
                    </p>
                    <form class="flex flex-col sm:flex-row gap-4">
                        <input type="email" 
                               placeholder="Votre adresse email" 
                               class="flex-1 px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            S'abonner
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <!-- Company Info -->
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-2xl text-white"></i>
                        </div>
                        <span class="text-2xl font-bold text-white">Youdemy</span>
                    </div>
                    <p class="text-gray-400 mb-6">
                        Youdemy est la plateforme leader de l'apprentissage en ligne, 
                        offrant des cours de qualité pour développer vos compétences professionnelles.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition">
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition">
                            <i class="fab fa-linkedin-in text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold text-lg mb-4">Liens rapides</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                À propos
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Carrières
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Blog
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Devenir formateur
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Affiliations
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Popular Categories -->
                <div>
                    <h4 class="text-white font-semibold text-lg mb-4">Catégories</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Développement Web
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Business & Marketing
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Design & Créativité
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                IA & Data Science
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Développement Personnel
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-white font-semibold text-lg mb-4">Support</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Centre d'aide
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Documentation
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Contact
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                FAQ
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Communauté
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 mt-12 pt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Copyright -->
                    <div class="text-gray-400 text-sm">
                        © 2024 Youdemy. Tous droits réservés.
                    </div>
                    
                    <!-- Legal Links -->
                    <div class="flex flex-wrap gap-4 text-sm justify-start md:justify-end">
                        <a href="#" class="text-gray-400 hover:text-white transition">Confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">CGU</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">Mentions légales</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">Cookies</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">Accessibilité</a>
                    </div>
                </div>
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
             // Mobile menu toggle functionality
             document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>