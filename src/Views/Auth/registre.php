<?php

require_once("../../../vendor/autoload.php");
use App\Controllers\Auth\AuthController;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['roleTitle'];

    $authController = new AuthController();
    try {
        $authController->register($firstname, $lastname, $email, $password, $role);
        echo "Registration successful!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerLink - Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100">
    <div class="container mx-auto min-h-screen flex items-center justify-center px-4">
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

                <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Créer un compte</h2>

                <form id="registre.php" method="POST" class="space-y-4">
                    <!-- Name -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" name="firstname" id="firstname" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all"
                                placeholder="Jean">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" name="lastname" id="lastname" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all"
                                placeholder="Dupont">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all"
                            placeholder="votreemail@exemple.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" name="password"  id="password" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all"
                            placeholder="••••••••">
                    </div>
<!-- 
                    Role Selection 
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <select name="role" id="role" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all appearance-none">
                            <option value="">Sélectionnez un rôle</option>
                            <option value="candidate">Candidat</option>
                            <option value="recruiter">Recruteur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div> -->

                <!-- Role Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Title</label>
                <input type="text" name="roleTitle" id="roleTitle" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all"
                    placeholder="Role Title">
            </div>



                    <!-- Terms Checkbox -->
                    <div class="flex items-start space-x-2">
                        <input type="checkbox" required
                            class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label class="text-sm text-gray-600">
                            J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800">conditions d'utilisation</a> et la <a href="#" class="text-blue-600 hover:text-blue-800">politique de confidentialité</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Créer mon compte
                    </button>
                </form>

                <p class="text-center mt-8 text-gray-600">
                    Déjà inscrit ? 
                    <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium">
                        Connectez-vous
                    </a>
                </p>
            </div>

            <!-- Right Side - Content -->
            <div class="hidden md:block w-1/2 bg-blue-600 p-12 text-white">
                <div class="h-full flex flex-col justify-center">
                    <h2 class="text-3xl font-bold mb-6">Commencez votre aventure professionnelle</h2>
                    <p class="text-blue-100 text-lg mb-8">Rejoignez notre communauté et accédez à des milliers d'opportunités professionnelles.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 bg-blue-500/20 p-4 rounded-lg">
                            <i class="fas fa-check-circle text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Profil personnalisé</h3>
                                <p class="text-blue-100 text-sm">Créez votre CV en ligne et soyez visible</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 bg-blue-500/20 p-4 rounded-lg">
                            <i class="fas fa-bell text-2xl"></i>
                            <div>
                                <h3 class="font-semibold">Alertes emploi</h3>
                                <p class="text-blue-100 text-sm">Recevez les offres qui vous correspondent</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
