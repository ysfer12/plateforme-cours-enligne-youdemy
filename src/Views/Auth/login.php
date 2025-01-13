<?php

require_once("../../../vendor/autoload.php");
use App\Controllers\Auth\AuthController;

if (isset($_POST["submit"])) {
    if (empty($_POST["email"]) && empty($_POST["mot_de_pass"])) {
        echo "L'adresse e-mail ou le mot de passe est vide.";
    } else {
        $email = $_POST["email"];
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
    <title>E-Learn - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-r from-blue-100 to-blue-200">
    <div class="container mx-auto min-h-screen flex items-center justify-center px-4">
        <!-- Main Card -->
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
            <!-- Left Side - Form -->
            <div class="w-full md:w-1/2 p-10">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-10">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <i class="fas fa-graduation-cap text-blue-600 text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800 ml-3">E-Learn</span>
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Connexion</h2>
                <p class="text-gray-500 text-center mb-6">Rejoignez-nous et continuez votre apprentissage.</p>

                <form class="space-y-6" id="login-form" action="" method="POST">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all"
                            placeholder="votreemail@exemple.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" id="mot_de_pass" name="mot_de_pass" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all"
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" 
                                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" name="submit" value="1"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-all font-medium">
                        Se connecter
                    </button>
                </form>

                <p class="text-center mt-8 text-gray-600">
                    Pas encore de compte ? 
                    <a href="registre.php" class="text-blue-600 hover:text-blue-800 font-medium">
                        Inscrivez-vous gratuitement
                    </a>
                </p>
            </div>
            <!-- Right Side - Info Section -->
            <div class="hidden md:block w-1/2 bg-gradient-to-r from-blue-600 to-blue-500 p-12 text-white">
                <div class="h-full flex flex-col justify-center">
                    <h2 class="text-3xl font-bold mb-6">Apprenez et grandissez avec nous</h2>
                    <p class="text-blue-100 text-lg mb-8">
                        Accédez à des milliers de cours créés par des experts, explorez vos passions, 
                        et boostez votre carrière dès aujourd'hui.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            Plus de 100 catégories disponibles
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            Des certificats reconnus
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            Formation accessible 24/7
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>