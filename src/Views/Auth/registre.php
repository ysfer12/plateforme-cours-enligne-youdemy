<?php
require_once("../../../vendor/autoload.php");
use App\Controllers\Auth\AuthController;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['roleTitle'];
    $status = 'isActive';
    $authController = new AuthController();
    $authController->register($firstname, $lastname, $email, $password, $role, $status);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
    }
    
    .gradient-text {
        background: linear-gradient(to right, #3B82F6, #2563EB);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
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
                     
                        <a href="../Auth/login.php" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:opacity-90 transition">
                            Connexion
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
    <main class="container mx-auto min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
            <!-- Left Side - Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600 mr-2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    <span class="text-2xl font-bold text-gray-900">Youdemy</span>
                </div>

                <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">Rejoignez Youdemy</h2>
                <p class="text-center text-gray-600 mb-8">Commencez votre parcours d'apprentissage</p>

                <form method="POST" class="space-y-5">
                    <!-- Name -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" name="firstname" id="firstname" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                placeholder="Jean">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" name="lastname" id="lastname" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                placeholder="Dupont">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="votreemail@exemple.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="••••••••">
                        <p class="text-xs text-gray-500 mt-2">Minimum 8 caractères avec des lettres et des chiffres</p>
                    </div>

                    <!-- Role Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                        <select name="roleTitle" id="roleTitle" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <option value="">Sélectionnez votre rôle</option>
                            <option value="Etudiant">Etudiant</option>
                            <option value="Enseignant">Enseignant</option>
                        </select>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start space-x-2">
                        <input type="checkbox" required
                            class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label class="text-sm text-gray-600">
                            J'accepte les <a href="#" class="text-indigo-600 hover:text-indigo-800">conditions d'utilisation</a> et la <a href="#" class="text-indigo-600 hover:text-indigo-800">politique de confidentialité</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium shadow-md">
                        Créer mon compte
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Vous avez déjà un compte ? 
                        <a href="login.php" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Connectez-vous
                        </a>
                    </p>
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="hidden md:block w-1/2 bg-gradient-to-br from-indigo-600 to-purple-600 p-12 text-white">
                <div class="h-full flex flex-col justify-center">
                    <h2 class="text-3xl font-bold mb-6">Transformez votre avenir</h2>
                    <p class="text-indigo-100 text-lg mb-8">Développez vos compétences avec les meilleurs cours en ligne</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 bg-white/10 p-4 rounded-lg">
                            <i class="fas fa-video text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Cours Vidéo</h3>
                                <p class="text-indigo-100 text-sm">Apprenez à votre rythme avec nos vidéos interactives</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 bg-white/10 p-4 rounded-lg">
                            <i class="fas fa-certificate text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Certifications</h3>
                                <p class="text-indigo-100 text-sm">Obtenez des certificats reconnus</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 bg-white/10 p-4 rounded-lg">
                            <i class="fas fa-globe text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Apprentissage Global</h3>
                                <p class="text-indigo-100 text-sm">Accès à des cours du monde entier</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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