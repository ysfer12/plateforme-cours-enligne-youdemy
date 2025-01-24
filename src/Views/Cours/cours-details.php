<?php
// Start the session
session_start();

// Include the necessary files (autoload or manual includes)
require_once '../../../vendor/autoload.php';

// Instantiate the controller
$coursController = new App\Controllers\Catalogue\CoursController();

// Get the course ID from the URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Redirect to the homepage if the course ID is invalid
if (!$courseId) {
    header('Location: index.php');
    exit;
}

// Initialize $userId
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch course details using the controller
$course = $coursController->getCourseDetails($courseId, $userId);

// Redirect to the homepage if the course is not found
if (!$course) {
    header('Location: index.php');
    exit;
}

// Extract tags if they exist
$tags = !empty($course['tag_names']) ? explode(',', $course['tag_names']) : [];

// Check if the user is enrolled in the course
$isEnrolled = $course['is_inscrit'] ?? false;

// Fetch similar courses
$similarCourses = $coursController->getSimilarCourses($course['category_id'], $courseId);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['titre']) ?> - LearnHub</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(to right, #3B82F6, #2563EB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .testimonial-card {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: scale(1.02);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
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
                        <a href="../Views/Cours/Cours.php" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
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
                    <div class="flex items-center space-x-3">
                        <a href="../Views/Auth/login.php" class="px-4 py-2 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                            Connexion
                        </a>
                        <a href="../Views/Auth/registre.php" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:opacity-90 transition">
                            Inscription
                        </a>
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
                    <a href="../Views/Cours/Cours.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-book-open mr-2"></i>
                        Catalogue
                    </a>
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-th-large mr-2"></i>
                        Qui sommes nous?
                    </a>
                    <div class="pt-4 flex flex-col space-y-2">
                        <a href="../Views/Auth/login.php" class="px-4 py-2 text-center text-blue-600 rounded-lg border border-blue-600 hover:bg-blue-50 transition">
                            Connexion
                        </a>
                        <a href="../Views/Auth/registre.php" class="px-4 py-2 text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:opacity-90 transition">
                            Inscription
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

<!-- Main Content -->
<main class="flex-grow pt-16">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-20 px-4">
            <!-- Course Info -->
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Course Details -->
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

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent event from bubbling up
            mobileMenu.classList.toggle('hidden');
            
            // Update the icon
            const icon = mobileMenuButton.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                const icon = mobileMenuButton.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Prevent menu from closing when clicking inside it
        mobileMenu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    </script>
</body>
</html>