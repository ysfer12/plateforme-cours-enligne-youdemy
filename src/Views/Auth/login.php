<?php

require_once("../../../vendor/autoload.php");
use App\Controllers\Auth\AuthController;



if(isset($_POST["submit"]))
{

    if(empty($_POST["email"]) && empty($_POST["mot_de_pass"]))
    {
        echo "email or password is empty";
    }
    else{
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
    <title>CareerLink - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100">
    <div class="container mx-auto min-h-screen flex items-center justify-center px-4">
        <!-- Main Card -->
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
            <!-- Left Side - Form -->
            <div class="w-full md:w-1/2 p-8">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-8">
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 ml-2">CareerLink</span>
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Connexion</h2>
                <p class="text-gray-500 text-center mb-8">Heureux de vous revoir !</p>

                <form class="space-y-4" id="login-form" action="" method="POST">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all"
                                placeholder="votreemail@exemple.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <div class="relative">
                            <input type="password" id="mot_de_pass" name="mot_de_pass" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" 
                                class="w-4 h-4 rounded text-blue-500 focus:ring-blue-400 border-gray-300">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" name="submit" value="1"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Se connecter
                    </button>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Ou continuez avec</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <a href="#" class="flex items-center justify-center py-2 px-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fab fa-google text-[#db4437]"></i>
                        </a>
                        <a href="#" class="flex items-center justify-center py-2 px-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fab fa-facebook-f text-[#3b5998]"></i>
                        </a>
                        <a href="#" class="flex items-center justify-center py-2 px-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fab fa-linkedin-in text-[#0077b5]"></i>
                        </a>
                    </div>
                </div>

                <p class="text-center mt-8 text-gray-600">
                    Pas encore de compte ? 
                    <a href="registre.php" class="text-blue-600 hover:text-blue-800 font-medium">
                        Inscrivez-vous gratuitement
                    </a>
                </p>
            </div>

            <!-- Right Side - Image -->
            <div class="hidden md:block w-1/2 bg-blue-600 p-12 text-white">
                <div class="h-full flex flex-col justify-center">
                    <h2 class="text-3xl font-bold mb-6">Débloquez votre potentiel professionnel</h2>
                    <p class="text-blue-100 text-lg mb-8">Rejoignez notre communauté et découvrez des opportunités qui correspondent à vos ambitions.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-500/20 p-4 rounded-lg">
                            <div class="text-2xl mb-2">10k+</div>
                            <div class="text-blue-100">Entreprises</div>
                        </div>
                        <div class="bg-blue-500/20 p-4 rounded-lg">
                            <div class="text-2xl mb-2">50k+</div>
                            <div class="text-blue-100">Offres d'emploi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>