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
            background: rgba(255, 255, 255, 0.8);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar - Glass Effect -->
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

                <!-- Navigation Links with Better Styling -->
                <div class="flex items-center space-x-8">
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-1">
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
                        <button class="px-4 py-2 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                            Connexion
                        </button>
                        <button class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:opacity-90 transition">
                            Inscription
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Enhanced Hero Section with Animation -->
    <section class="pt-24 pb-12 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800">
        <div class="max-w-7xl mx-auto px-4 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left">
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
                <div class="hidden md:block relative">
                    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                    <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                    <img src="../../public/assets/depositphotos_109883396-stock-photo-student-in-school-library-using.jpg" alt="Learning Illustration" class="relative rounded-2xl shadow-2xl transform -rotate-2 hover:rotate-0 transition duration-500">
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

   

    <!-- Why Choose Us -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">Pourquoi choisir Youdemy ?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Experts qualifiés</h3>
                    <p class="text-gray-600">Apprenez avec des instructeurs expérimentés et passionnés.</p>
                </div>
                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Apprentissage flexible</h3>
                    <p class="text-gray-600">Étudiez à votre rythme, où que vous soyez.</p>
                </div>
                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-certificate text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Certificats reconnus</h3>
                    <p class="text-gray-600">Obtenez des certificats valorisants pour votre carrière.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-600">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-white mb-6">Prêt à commencer votre voyage d'apprentissage ?</h2>
            <p class="text-blue-100 mb-8 text-lg">Rejoignez plus de 100 000 apprenants qui ont déjà fait confiance à Youdemy</p>
            <button class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition">
                Commencer maintenant
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-graduation-cap text-2xl text-white"></i>
                        <span class="text-xl font-bold text-white">Youdemy</span>
                    </div>
                    <p class="text-sm">La plateforme d'apprentissage en ligne leader pour développer vos compétences.</p>
                </div>
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">À propos</a></li>
                        <li><a href="#" class="hover:text-white transition">Carrières</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Devenir enseignant</a></li>
                        <li><a href="#" class="hover:text-white transition">Affiliations</a></li>
                    </ul>
                </div>
                <!-- Categories -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Catégories</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">Développement Web</a></li>
                        <li><a href="#" class="hover:text-white transition">Business</a></li>
                        <li><a href="#" class="hover:text-white transition">Design</a></li>
                        <li><a href="#" class="hover:text-white transition">Marketing</a></li>
                        <li><a href="#" class="hover:text-white transition">Intelligence Artificielle</a></li>
                    </ul>
                </div>
                <!-- Support -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">Centre d'aide</a></li>
                        <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition">Communauté</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <!-- Copyright -->
                    <div class="text-sm mb-4 md:mb-0">
                        © 2024 Youdemy. Tous droits réservés.
                    </div>
                    
                    <!-- Social Links -->
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                    
                    <!-- Legal Links -->
                    <div class="flex space-x-4 text-sm mt-4 md:mt-0">
                        <a href="#" class="hover:text-white transition">Confidentialité</a>
                        <a href="#" class="hover:text-white transition">CGU</a>
                        <a href="#" class="hover:text-white transition">Légal</a>
                        <a href="#" class="hover:text-white transition">Accessibilité</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>