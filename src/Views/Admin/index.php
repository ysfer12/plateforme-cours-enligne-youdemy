<?php   
require_once '../../../vendor/autoload.php';
use App\Config\Database;
AuthMiddleware::checkUserRole('admin');
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - La plateforme d'apprentissage nouvelle génération</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar - Enhanced Glass Effect -->
    <nav class="fixed w-full z-50 glass-effect border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo with Gradient -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-2xl text-white"></i>
                    </div>
                    <span class="text-2xl font-bold gradient-text">Youdemy</span>
                </div>

                <!-- Enhanced Search Bar -->
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

                <!-- Navigation Links -->
                <div class="flex items-center space-x-8">
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="../Cours/Cours.php" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
                            <i class="fas fa-book-open text-sm"></i>
                            <span>Catalogue</span>
                        </a>
                        <div class="relative group">
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
                                <i class="fas fa-th-large text-sm"></i>
                                <span>Catégories</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <div class="absolute top-full -left-4 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block border border-gray-100">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    Développement Web
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    Business
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    Design
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="../Admin/dashboard.php" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:opacity-90 transition">
                            Administration bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Enhanced Hero Section with Animated Elements -->
    <section class="pt-24 pb-12 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-32 h-32 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
            <div class="absolute bottom-20 right-10 w-32 h-32 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s"></div>
            <div class="absolute top-40 right-1/4 w-24 h-24 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 1s"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left relative z-10">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                        Apprenez sans limites.<br/>
                        <span class="text-blue-200">Évoluez sans frontières.</span>
                    </h1>
                    <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                        Découvrez une nouvelle façon d'apprendre avec des experts passionnés 
                        et une communauté dynamique.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center md:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                        <button class="px-8 py-4 bg-white text-blue-600 rounded-full font-semibold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-rocket mr-2"></i>
                            Commencer gratuitement
                        </button>
                        <button class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-blue-600 transition">
                            <i class="fas fa-play mr-2"></i>
                            Voir la démo
                        </button>
                    </div>
                    <!-- Stats -->
                    <div class="mt-12 grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">100k+</div>
                            <div class="text-blue-200 text-sm">Étudiants</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">1000+</div>
                            <div class="text-blue-200 text-sm">Cours</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">4.8/5</div>
                            <div class="text-blue-200 text-sm">Satisfaction</div>
                        </div>
                    </div>
                </div>
                <!-- Enhanced Hero Image -->
                <div class="hidden md:block relative z-10">
                    <img src="../../../public/assets/depositphotos_109883396-stock-photo-student-in-school-library-using.jpg" alt="Learning Illustration" class="rounded-2xl shadow-2xl transform hover:scale-105 transition duration-500">
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 bg-white rounded-lg shadow-lg p-4 animate-float">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-users text-blue-600"></i>
                            <span class="font-semibold">Communauté active</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-lg shadow-lg p-4 animate-float" style="animation-delay: 1s">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-certificate text-blue-600"></i>
                            <span class="font-semibold">Certifié</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wave Separator -->
        <div class="wave-bottom">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>
    <!-- Featured Courses Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Cours les plus populaires</h2>
                <a href="../../Views/Cours/Cours.php" class="text-blue-600 hover:text-blue-700 flex items-center">
                    Voir tout <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Course Card 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="../../public/assets/depositphotos_68471565-stock-illustration-online-communication-education-and-social.jpg" alt="Course" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm">
                            Bestseller
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <img src="../../public/assets/depositphotos_68471565-stock-illustration-online-communication-education-and-social.jpg" alt="Instructor" class="w-8 h-8 rounded-full">
                            <span class="text-sm text-gray-600">John Doe</span>
                        </div>
                        <h3 class="font-semibold text-xl mb-2">Développement Web Full Stack</h3>
                        <p class="text-gray-600 text-sm mb-4">Maîtrisez HTML, CSS, JavaScript et les frameworks modernes</p>
                        <div class="flex items-center mb-4">
                            <div class="text-yellow-400 flex">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-sm text-gray-600 ml-2">4.8 (2.5k avis)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">49.99 €</span>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                S'inscrire
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Course Card 2 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="../../public/assets/depositphotos_68471565-stock-illustration-online-communication-education-and-social.jpg" alt="Course" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                            Nouveau
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <img src="/api/placeholder/32/32" alt="Instructor" class="w-8 h-8 rounded-full">
                            <span class="text-sm text-gray-600">Marie Martin</span>
                        </div>
                        <h3 class="font-semibold text-xl mb-2">Intelligence Artificielle : Les Fondamentaux</h3>
                        <p class="text-gray-600 text-sm mb-4">Découvrez le Machine Learning et le Deep Learning</p>
                        <div class="flex items-center mb-4">
                            <div class="text-yellow-400 flex">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-600 ml-2">5.0 (180 avis)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">69.99 €</span>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                S'inscrire
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Course Card 3 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="../../public/assets/depositphotos_68471565-stock-illustration-online-communication-education-and-social.jpg" alt="Course" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-purple-600 text-white px-3 py-1 rounded-full text-sm">
                            Populaire
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <img src="/api/placeholder/32/32" alt="Instructor" class="w-8 h-8 rounded-full">
                            <span class="text-sm text-gray-600">Sophie Bernard</span>
                        </div>
                        <h3 class="font-semibold text-xl mb-2">Marketing Digital Complet</h3>
                        <p class="text-gray-600 text-sm mb-4">Stratégies avancées de marketing digital</p>
                        <div class="flex items-center mb-4">
                            <div class="text-yellow-400 flex">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-600 ml-2">4.9 (820 avis)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">59.99 €</span>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                S'inscrire
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 
    <!-- Learning Path Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Votre parcours d'apprentissage</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Un chemin personnalisé vers vos objectifs professionnels
                </p>
            </div>
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-blue-200"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-12">
                    <!-- Timeline Item 1 -->
                    <div class="flex items-center justify-center relative">
                        <div class="w-1/2 pr-8 text-right">
                            <h3 class="font-semibold text-xl mb-2">Débutez votre voyage</h3>
                            <p class="text-gray-600">Évaluez vos compétences et définissez vos objectifs</p>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-flag text-white"></i>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>

                    <!-- Timeline Item 2 -->
                    <div class="flex items-center justify-center relative">
                        <div class="w-1/2 pr-8"></div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <div class="w-1/2 pl-8">
                            <h3 class="font-semibold text-xl mb-2">Apprenez à votre rythme</h3>
                            <p class="text-gray-600">Suivez des cours adaptés à votre niveau</p>
                        </div>
                    </div>

                    <!-- Timeline Item 3 -->
                    <div class="flex items-center justify-center relative">
                        <div class="w-1/2 pr-8 text-right">
                            <h3 class="font-semibold text-xl mb-2">Pratiquez régulièrement</h3>
                            <p class="text-gray-600">Appliquez vos connaissances sur des projets concrets</p>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-laptop-code text-white"></i>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>

                    <!-- Timeline Item 4 -->
                    <div class="flex items-center justify-center relative">
                        <div class="w-1/2 pr-8"></div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-certificate text-white"></i>
                        </div>
                        <div class="w-1/2 pl-8">
                            <h3 class="font-semibold text-xl mb-2">Obtenez votre certification</h3>
                            <p class="text-gray-600">Validez vos acquis avec un certificat reconnu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Success Stories Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Histoires de réussite</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Découvrez comment nos apprenants ont transformé leur carrière
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Success Story Card 1 -->
                <div class="testimonial-card bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <img src="../../public/assets/depositphotos_208209736-stock-photo-portrait-smiling-young-female-student.jpg" alt="Student" class="w-16 h-16 rounded-full">
                        <div>
                            <h4 class="font-semibold">Marie Laurent</h4>
                            <p class="text-gray-600 text-sm">Développeuse Frontend</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">
                        "Grâce à Youdemy, j'ai pu me reconvertir dans le développement web en seulement 6 mois. Aujourd'hui, je travaille pour une startup innovante !"
                    </p>
                    <div class="flex items-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Success Story Card 2 -->
                <div class="testimonial-card bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <img src="../../public/assets/depositphotos_109883396-stock-photo-student-in-school-library-using.jpg" alt="Student" class="w-16 h-16 rounded-full">
                        <div>
                            <h4 class="font-semibold">Thomas Dubois</h4>
                            <p class="text-gray-600 text-sm">Data Scientist</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">
                        "Les cours d'IA et de Data Science m'ont permis d'acquérir des compétences très recherchées. J'ai doublé mon salaire en moins d'un an !"
                    </p>
                    <div class="flex items-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Success Story Card 3 -->
                <div class="testimonial-card bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <img src="/api/placeholder/64/64" alt="Student" class="w-16 h-16 rounded-full">
                        <div>
                            <h4 class="font-semibold">Sophie Martin</h4>
                            <p class="text-gray-600 text-sm">UX Designer</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">
                        "La qualité des cours et le support de la communauté sont exceptionnels. J'ai pu créer mon portfolio et décrocher mon premier poste en UX Design."
                    </p>
                    <div class="flex items-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Mobile App Promo Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">
                        Apprenez partout avec notre application mobile
                    </h2>
                    <p class="text-gray-600 mb-8">
                        Téléchargez notre application pour accéder à vos cours hors ligne et apprendre où que vous soyez.
                    </p>
                    <div class="flex space-x-4">
                        <button class="flex items-center space-x-2 bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                            <i class="fab fa-apple text-2xl"></i>
                            <div class="text-left">
                                <div class="text-xs">Télécharger sur</div>
                                <div class="text-sm font-semibold">App Store</div>
                            </div>
                        </button>
                        <button class="flex items-center space-x-2 bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                            <i class="fab fa-google-play text-2xl"></i>
                            <div class="text-left">
                                <div class="text-xs">Disponible sur</div>
                                <div class="text-sm font-semibold">Google Play</div>
                            </div>
                        </button>
                    </div>
                    <!-- Features List -->
                    <div class="mt-12 space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-video text-blue-600"></i>
                            </div>
                            <span class="text-gray-700">Cours disponibles hors ligne</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bell text-blue-600"></i>
                            </div>
                            <span class="text-gray-700">Rappels d'apprentissage personnalisés</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-blue-600"></i>
                            </div>
                            <span class="text-gray-700">Suivi de progression avancé</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="/api/placeholder/400/600" alt="Mobile App" class="rounded-xl shadow-2xl">
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 bg-white rounded-lg shadow-lg p-4 animate-float">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-video text-blue-600"></i>
                            <span class="font-semibold">Cours hors ligne</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-lg shadow-lg p-4 animate-float" style="animation-delay: 1s">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-certificate text-blue-600"></i>
                            <span class="font-semibold">Progression tracée</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Enhanced Categories Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Explorer par catégorie
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Découvrez notre sélection de cours dans différents domaines pour développer vos compétences.
                </p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Category Card 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition card-hover cursor-pointer group">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-laptop-code text-2xl text-white"></i>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Développement Web</h3>
                    <p class="text-gray-600 text-sm mb-4">HTML, CSS, JavaScript et plus</p>
                    <div class="flex items-center text-blue-600">
                        <span class="text-sm font-medium">150+ cours</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                    </div>
                </div>

                <!-- Category Card 2 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition card-hover cursor-pointer group">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Business</h3>
                    <p class="text-gray-600 text-sm mb-4">Marketing, Finance, Stratégie</p>
                    <div class="flex items-center text-purple-600">
                        <span class="text-sm font-medium">200+ cours</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                    </div>
                </div>

                <!-- Category Card 3 -->
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-6 hover:shadow-lg transition card-hover cursor-pointer group">
                    <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-pink-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-palette text-2xl text-white"></i>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Design</h3>
                    <p class="text-gray-600 text-sm mb-4">UI/UX, Graphisme, Motion</p>
                    <div class="flex items-center text-pink-600">
                        <span class="text-sm font-medium">120+ cours</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                    </div>
                </div>

                <!-- Category Card 4 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition card-hover cursor-pointer group">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-brain text-2xl text-white"></i>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">IA & Data</h3>
                    <p class="text-gray-600 text-sm mb-4">Machine Learning, Data Science</p>
                    <div class="flex items-center text-green-600">
                        <span class="text-sm font-medium">80+ cours</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Enhanced Why Choose Us Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Pourquoi choisir Youdemy ?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Nous nous engageons à vous offrir la meilleure expérience d'apprentissage possible
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chalkboard-teacher text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl text-center mb-4">Experts qualifiés</h3>
                    <p class="text-gray-600 text-center">
                        Nos instructeurs sont des professionnels reconnus dans leur domaine avec une réelle passion pour l'enseignement.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Plus de 500 experts certifiés
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Support personnalisé
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Mise à jour régulière des contenus
                        </li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl text-center mb-4">Apprentissage flexible</h3>
                    <p class="text-gray-600 text-center">
                        Apprenez à votre rythme, où que vous soyez, avec un accès illimité à tous nos contenus.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Accès 24/7 aux cours
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Apprentissage mobile
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Mode hors ligne disponible
                        </li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-certificate text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl text-center mb-4">Certificats reconnus</h3>
                    <p class="text-gray-600 text-center">
                        Obtenez des certifications reconnues par l'industrie pour valoriser vos nouvelles compétences.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Certifications accréditées
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Valorisation professionnelle
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Partage sur LinkedIn
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
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
</body>
</html>