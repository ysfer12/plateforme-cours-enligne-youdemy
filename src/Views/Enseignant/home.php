<?php
require_once '../../../vendor/autoload.php';
use App\Controllers\OffreController;

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(empty($_POST["titre"]) && empty($_POST["description"]) && empty($_POST["salaire"]) && empty($_POST["localisation"]) && empty($_POST["categorie"]) && empty($_POST["tag"]) && empty($_POST["date_publication"]))
    {
        echo "Veuillez remplir tous les champs";
    }
    else{
        $titre = $_POST["titre"];
        $description = $_POST["description"];
        $salaire = $_POST["salaire"];
        $localisation = $_POST["localisation"];
        $categorie = $_POST["categorie"];
        $tag = $_POST["tag"];
        $date_publication = $_POST["date_publication"];
        $offre = new OffreController();
        $offre->create($titre, $description, $salaire, $localisation, $categorie, $tag, $date_publication);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerLink - Espace Recruteur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-gray-800 text-white p-4">
        <div class="flex items-center mb-8">
            <i class="fas fa-briefcase text-2xl mr-2"></i>
            <span class="text-xl font-bold">CareerLink</span>
        </div>

        <div class="mb-8 p-3 bg-gray-700 rounded-lg">
            <div class="flex items-center space-x-3">
                <img src="https://via.placeholder.com/40" alt="Company Logo" class="rounded-full">
                <div>
                    <p class="font-semibold">TechCorp</p>
                    <p class="text-sm text-gray-300">Recruteur</p>
                </div>
            </div>
        </div>

        <nav class="space-y-2">
            <a href="#" class="flex items-center space-x-2 bg-blue-600 text-white p-3 rounded-lg" onclick="showSection('dashboard')">
                <i class="fas fa-chart-line"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition" onclick="showSection('offers')">
                <i class="fas fa-briefcase"></i>
                <span>Mes offres</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition" onclick="showSection('applications')">
                <i class="fas fa-users"></i>
                <span>Candidatures</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition" onclick="showSection('messages')">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition" onclick="showSection('profile')">
                <i class="fas fa-building"></i>
                <span>Profil entreprise</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition" onclick="showSection('settings')">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-8">
    <section id="dashboard" class="section">
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold mb-1">Bonjour, TechCorp</h1>
                <p class="text-gray-600">Voici un aperçu de vos activités de recrutement</p>
            </div>
            <div class="flex items-center space-x-4">
            <button id="openModalBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Publier une offre
            </button>
            <button id="openModalBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Logout
            </button>
            <div class="relative">
                <i class="fas fa-bell text-gray-500 text-xl cursor-pointer"></i>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center">5</span>
            </div>
        </div>
    </div>
<!-- Modal Backdrop with blur and smooth transition -->
<div id="modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden transition-all duration-300">
    <!-- Modal Content -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-w-3xl max-h-[90vh] overflow-hidden">
        <!-- Glass effect header -->
        <div class="bg-white/90 backdrop-blur-sm border-b border-gray-100 p-6 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Publier une offre</h2>
                        <p class="text-sm text-gray-500">Remplissez les détails de votre offre d'emploi</p>
                    </div>
                </div>
                <button id="closeModalBtn" class="group p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
            <form action="" method="POST" class="space-y-8">
                <!-- Title Field -->
                <div class="space-y-2 group">
                    <label for="titre" class="flex items-center text-sm font-semibold text-gray-700 group-focus-within:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Titre d'Offre
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="titre" 
                               name="titre" 
                               class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Ex: Développeur Full Stack Senior"
                               required>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="space-y-2 group">
                    <label for="description" class="flex items-center text-sm font-semibold text-gray-700 group-focus-within:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                              placeholder="Décrivez le poste, les responsabilités et les exigences"
                              required></textarea>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Salary Field -->
                    <div class="space-y-2 group">
                        <label for="salaire" class="flex items-center text-sm font-semibold text-gray-700 group-focus-within:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Salaire
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">€</span>
                            <input type="text" 
                                   id="salaire" 
                                   name="salaire" 
                                   class="block w-full pl-8 pr-4 py-3.5 rounded-xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Ex: 45,000 - 60,000 par an"
                                   required>
                        </div>
                    </div>

                    <!-- Location Field -->
                    <div class="space-y-2 group">
                        <label for="localisation" class="flex items-center text-sm font-semibold text-gray-700 group-focus-within:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Localisation
                        </label>
                        <input type="text" 
                               id="localisation" 
                               name="localisation" 
                               class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Ex: Paris, France"
                               required>
                    </div>
                </div>

                <!-- Category Field -->
                <div class="space-y-2 group">
                    <label for="categorie" class="flex items-center text-sm font-semibold text-gray-700 group-focus-within:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Catégorie
                    </label>
                    <div class="relative">
                        <select id="categorie" 
                                name="categorie" 
                                class="appearance-none block w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                required>
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="technology">Technology</option>
                            <option value="marketing">Marketing</option>
                            <option value="finance">Finance</option>
                            <option value="design">Design</option>
                            <option value="sales">Sales</option>
                            <option value="customer-service">Customer Service</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tags Field -->
                <div class="space-y-2 group">
                    <label for="tag" class="flex items-center text-sm font-semibold text-gray-700 group-focus-within:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Tags
                    </label>
                    <input type="text" 
                           id="tag" 
                           name="tag" 
                           class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Ex: React, Node.js, TypeScript (séparés par des virgules)"
                           required>
                </div>

                <!-- Publication Date Field -->
                <div class="space-y-2 group">
                    <label for="date_publication" class="flex items-center text-sm font-semibold text-gray-700 group-focus-within:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Date de publication
                    </label>
                    <input type="date" 
                           id="date_publication" 
                           name="date_publication" 
                           class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           required>
                </div>
            </form>
        </div>

        <!-- Sticky Footer with Glass Effect -->
        <div class="sticky bottom-0 bg-white/90 backdrop-blur-sm border-t border-gray-100 p-6">
            <div class="flex justify-end space-x-4">
                <button type="button"
                        id="cancelBtn"
                        class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 transition duration-200">
                    Annuler
                </button>
                <button type="submit"
                        name="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    Publier l'offre
                </button>
            </div>
        </div>
    </div>
</div>
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Offres actives</h3>
                    <i class="fas fa-briefcase text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">12</p>
                <div class="mt-2 flex items-center text-sm text-green-500">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>+2 ce mois</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Candidatures reçues</h3>
                    <i class="fas fa-file-alt text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">48</p>
                <div class="mt-2 flex items-center text-sm text-green-500">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>+15 cette semaine</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Entretiens programmés</h3>
                    <i class="fas fa-calendar-alt text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">8</p>
                <div class="mt-2 flex items-center text-sm text-blue-500">
                    <i class="fas fa-clock mr-1"></i>
                    <span>3 cette semaine</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Messages non lus</h3>
                    <i class="fas fa-envelope text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">15</p>
                <div class="mt-2 flex items-center text-sm text-yellow-500">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <span>À traiter</span>
                </div>
            </div>
        </div>

        <!-- Recent Applications & Active Jobs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Applications -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold">Dernières candidatures</h3>
                    <a href="#" class="text-blue-500 hover:text-blue-600">Voir tout</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div class="flex items-center space-x-4">
                            <img src="https://via.placeholder.com/40" alt="Candidate" class="rounded-full">
                            <div>
                                <p class="font-semibold">Julie Dupont</p>
                                <p class="text-sm text-gray-500">Développeur Frontend</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="text-green-500 hover:text-green-600"><i class="fas fa-check"></i></button>
                            <button class="text-red-500 hover:text-red-600"><i class="fas fa-times"></i></button>
                            <button class="text-blue-500 hover:text-blue-600"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div class="flex items-center space-x-4">
                            <img src="https://via.placeholder.com/40" alt="Candidate" class="rounded-full">
                            <div>
                                <p class="font-semibold">Marc Martin</p>
                                <p class="text-sm text-gray-500">UX Designer</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="text-green-500 hover:text-green-600"><i class="fas fa-check"></i></button>
                            <button class="text-red-500 hover:text-red-600"><i class="fas fa-times"></i></button>
                            <button class="text-blue-500 hover:text-blue-600"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Jobs -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold">Offres actives</h3>
                    <a href="#" class="text-blue-500 hover:text-blue-600">Gérer les offres</a>
                </div>
                <div class="space-y-4">
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold">Développeur Full Stack</h4>
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-sm">Active</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-2">Paris - CDI</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">15 candidatures</span>
                            <div class="flex space-x-2">
                                <button class="text-blue-500 hover:text-blue-600"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold">UX/UI Designer Senior</h4>
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-sm">Active</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-2">Lyon - CDI</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">8 candidatures</span>
                            <div class="flex space-x-2">
                                <button class="text-blue-500 hover:text-blue-600"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Interviews -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Prochains entretiens</h3>
                <a href="#" class="text-blue-500 hover:text-blue-600">Voir le calendrier</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3">Candidat</th>
                            <th class="text-left py-3">Poste</th>
                            <th class="text-left py-3">Date</th>
                            <th class="text-left py-3">Heure</th>
                            <th class="text-left py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-3">
                                <div class="flex items-center space-x-3">
                                    <img src="https://via.placeholder.com/32" alt="Candidate" class="rounded-full">
                                    <span>Sophie Bernard</span>
                                </div>
                            </td>
                            <td>Développeur Frontend</td>
                            <td>12 Jan 2024</td>
                            <td>14:00</td>
                            <td class="space-x-2">
                                <button class="text-blue-500 hover:text-blue-600"><i class="fas fa-video"></i></button>
                                <button class="text-gray-500 hover:text-gray-600"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-600"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3">
                                <div class="flex items-center space-x-3">
                                    <img src="https://via.placeholder.com/32" alt="Candidate" class="rounded-full">
                                    <span>Pierre Durand</span>
                                </div>
                            </td>
                            <td>UX Designer</td>
                            <td>13 Jan 2024</td>
                            <td>10:30</td>
                            <td class="space-x-2">
                                <button class="text-blue-500 hover:text-blue-600"><i class="fas fa-video"></i></button>
                                <button class="text-gray-500 hover:text-gray-600"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-600"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>

        <!-- Quick Actions & Messages -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-6">Actions rapides</h3>
                <div class="grid grid-cols-2 gap-4">
                    <button class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-plus-circle text-blue-500 text-2xl mb-2"></i>
                        <span class="text-sm">Nouvelle offre</span>
                    </button>
                    <button class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-calendar-plus text-blue-500 text-2xl mb-2"></i>
                        <span class="text-sm">Planifier entretien</span>
                    </button>
                    <button class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-file-export text-blue-500 text-2xl mb-2"></i>
                        <span class="text-sm">Exporter données</span>
                    </button>
                    <button class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-chart-bar text-blue-500 text-2xl mb-2"></i>
                        <span class="text-sm">Voir statistiques</span>
                    </button>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold">Messages récents</h3>
                    <a href="#" class="text-blue-500 hover:text-blue-600">Voir tous les messages</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <img src="https://via.placeholder.com/40" alt="User" class="rounded-full">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold">Marie Lambert</h4>
                                <span class="text-xs text-gray-500">10:30</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">À propos de l'entretien de demain...</p>
                        </div>
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    </div>

                    <div class="flex items-center space-x-4 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <img src="https://via.placeholder.com/40" alt="User" class="rounded-full">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold">Thomas Dubois</h4>
                                <span class="text-xs text-gray-500">09:15</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">Merci pour votre retour concernant...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center text-gray-500 text-sm">
            <p>&copy; 2024 CareerLink. Tous droits réservés.</p>
        </footer>
        </section>
    <!-- Offers Section -->
    <section id="offers" class="section hidden">
            <h1 class="text-2xl font-bold mb-1">Mes offres</h1>
            <p class="text-gray-600">Voici vos offres d'emploi</p>
            <!-- Add your offers content here -->
        </section>

        <!-- Applications Section -->
        <section id="applications" class="section hidden">
            <h1 class="text-2xl font-bold mb-1">Candidatures</h1>
            <p class="text-gray-600">Voici les candidatures reçues</p>
            <!-- Add your applications content here -->
        </section>

        <!-- Messages Section -->
        <section id="messages" class="section hidden">
            <h1 class="text-2xl font-bold mb-1">Messages</h1>
            <p class="text-gray-600">Voici vos messages</p>
            <!-- Add your messages content here -->
        </section>
        <!-- Profile Section -->
        <section id="profile" class="section hidden">
            <h1 class="text-2xl font-bold mb-1">Profil entreprise</h1>
            <p class="text-gray-600">Voici les détails de votre entreprise</p>
            <!-- Add your profile content here -->
        </section>

        <!-- Settings Section -->
        <section id="settings" class="section hidden">
            <h1 class="text-2xl font-bold mb-1">Paramètres</h1>
            <p class="text-gray-600">Voici vos paramètres</p>
            <!-- Add your settings content here -->
        </section>
    </main>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => {
                section.classList.add('hidden');
            });

            // Show the selected section
            const selectedSection = document.getElementById(sectionId);
            selectedSection.classList.remove('hidden');
        }
        // Notifications dropdown
        const notificationBtn = document.querySelector('.fa-bell').parentElement;
        notificationBtn.addEventListener('click', function() {
            // Add notification dropdown logic here
        });

        document.querySelectorAll('.fa-edit').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                // Add edit job logic here
            });
        });

        document.querySelectorAll('.fa-trash').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) {
                    // Add delete job logic here
                }
            });
        });

        // Quick action buttons
        document.querySelectorAll('.grid-cols-2 button').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.querySelector('span').textContent;
                switch(action) {
                    case 'Nouvelle offre':
                        // Handle new job creation
                        break;
                    case 'Planifier entretien':
                        // Handle interview scheduling
                        break;
                    case 'Exporter données':
                        // Handle data export
                        break;
                    case 'Voir statistiques':
                        // Handle statistics view
                        break;
                }
            });
        });

        // Add this script just before the closing </body> tag

    </script>
</body>
</html>