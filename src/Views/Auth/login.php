<?php
session_start(); // Ensure session is started at the beginning
require_once("../../../vendor/autoload.php");
use App\Controllers\Auth\AuthController;

if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['user_id']['user_role']) {
        case 'Etudiant':
            header('Location: ../Etudiant/home.php');
            break;

        case 'Enseignant':
            header('Location: ../Enseignant/dashboard.php');
            break;

        case 'Admin':
            header('Location: ../Admin/dashboard.php');
            break;

        default:
            // Handle unexpected roles or errors
            $_SESSION['login_error'] = "Rôle utilisateur non reconnu.";
            header('Location: ../Auth/login.php');
            break;
    }
    exit();
}

// Check if form is submitted
if (isset($_POST["submit"])) {
    // Validate input
    if (empty($_POST["email"]) || empty($_POST["mot_de_pass"])) {
        // Store error in session for display
        $_SESSION['login_error'] = "L'adresse e-mail ou le mot de passe est vide.";
        header("Location: ../Auth/login.php");
        exit();
    } else {
        $email = trim($_POST["email"]);
        $mot_de_pass = $_POST["mot_de_pass"];

        $authController = new AuthController();
        $authController->login($email, $mot_de_pass);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Connexion</title>
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600 mr-2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                <span class="text-xl font-bold text-gray-900">Youdemy</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="../" class="text-gray-700 hover:text-indigo-600">Accueil</a>
                <a href="../courses" class="text-gray-700 hover:text-indigo-600">Cours</a>
                <a href="../about" class="text-gray-700 hover:text-indigo-600">À propos</a>
                <a href="registre.php" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    S'inscrire
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto min-h-screen flex items-center justify-center px-4 py-12">
        <!-- Main Card -->
        <div class="w-full max-w-5xl bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
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

                <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">Connectez-vous</h2>
                <p class="text-center text-gray-600 mb-8">Continuez votre parcours d'apprentissage</p>

                <form class="space-y-5" id="login-form" action="" method="POST">
                    <?php 
                    // Display login error if exists
                    if (isset($_SESSION['login_error'])): ?>
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                            <?php 
                            echo htmlspecialchars($_SESSION['login_error']);
                            unset($_SESSION['login_error']); 
                            ?>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="votreemail@exemple.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" id="mot_de_pass" name="mot_de_pass" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" 
                                class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
                        </div>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" name="submit" value="1"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium shadow-md">
                        Se connecter
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Pas encore de compte ? 
                        <a href="registre.php" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Inscrivez-vous gratuitement
                        </a>
                    </p>
                </div>
            </div>

            <!-- Right Side - Info Section -->
            <div class="hidden md:block w-1/2 bg-gradient-to-br from-indigo-600 to-purple-600 p-12 text-white">
                <div class="h-full flex flex-col justify-center">
                    <h2 class="text-3xl font-bold mb-6">Transformez votre avenir</h2>
                    <p class="text-indigo-100 text-lg mb-8">
                        Développez vos compétences avec les meilleurs cours en ligne, 
                        explorez de nouvelles opportunités et boostez votre carrière.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 bg-white/10 p-4 rounded-lg">
                            <i class="fas fa-video text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Cours en Vidéo</h3>
                                <p class="text-indigo-100 text-sm">Apprenez à votre rythme</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 bg-white/10 p-4 rounded-lg">
                            <i class="fas fa-certificate text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Certifications</h3>
                                <p class="text-indigo-100 text-sm">Certificats reconnus</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 bg-white/10 p-4 rounded-lg">
                            <i class="fas fa-globe text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Apprentissage Global</h3>
                                <p class="text-indigo-100 text-sm">Accès mondial</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t border-gray-200">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600 mr-2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">Youdemy</span>
                    </div>
                    <p class="text-gray-600">Développez vos compétences, réalisez vos objectifs.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Liens Rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="../courses" class="text-gray-600 hover:text-indigo-600">Nos Cours</a></li>
                        <li><a href="../about" class="text-gray-600 hover:text-indigo-600">À Propos</a></li>
                        <li><a href="../contact" class="text-gray-600 hover:text-indigo-600">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Légal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600">Conditions d'utilisation</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600">Politique des cookies</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Rejoignez-nous</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-600 hover:text-indigo-600"><i class="fab fa-facebook text-2xl"></i></a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600"><i class="fab fa-twitter text-2xl"></i></a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600"><i class="fab fa-linkedin text-2xl"></i></a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600"><i class="fab fa-instagram text-2xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 mt-8 pt-6 text-center text-gray-600">
                &copy; 2024 Youdemy. Tous droits réservés.
            </div>
        </div>
    </footer>
</body>
</html>