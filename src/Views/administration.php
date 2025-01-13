<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerLink - Dashboard Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-gray-800 text-white p-4">
        <div class="flex items-center mb-8">
            <i class="fas fa-briefcase text-2xl mr-2"></i>
            <span class="text-xl font-bold">CareerLink Admin</span>
        </div>

        <nav class="space-y-2">
            <a href="#" class="flex items-center space-x-2 bg-blue-600 text-white p-3 rounded-lg">
                <i class="fas fa-chart-line"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-briefcase"></i>
                <span>Offres d'emploi</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-building"></i>
                <span>Entreprises</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-tags"></i>
                <span>Catégories</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-8">
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Tableau de bord</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <i class="fas fa-bell text-gray-500 text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center">3</span>
                </div>
                <div class="flex items-center space-x-2">
                    <img src="../../public/assets/admin.jpg" alt="Admin" class="w-10 h-10 rounded-full">
                    <span class="font-semibold">Admin</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Total Utilisateurs</h3>
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">12,845</p>
                <p class="text-green-500 text-sm mt-2">
                    <i class="fas fa-arrow-up"></i> +12% ce mois
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Offres Actives</h3>
                    <i class="fas fa-briefcase text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">3,426</p>
                <p class="text-green-500 text-sm mt-2">
                    <i class="fas fa-arrow-up"></i> +8% ce mois
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Entreprises</h3>
                    <i class="fas fa-building text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">1,245</p>
                <p class="text-green-500 text-sm mt-2">
                    <i class="fas fa-arrow-up"></i> +5% ce mois
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500">Candidatures</h3>
                    <i class="fas fa-file-alt text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold">8,742</p>
                <p class="text-green-500 text-sm mt-2">
                    <i class="fas fa-arrow-up"></i> +15% ce mois
                </p>
            </div>
        </div>

        <!-- Recent Activity & Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-4">Activités Récentes</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-plus text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Nouvelle offre d'emploi</p>
                            <p class="text-sm text-gray-500">TechCorp a publié une nouvelle offre</p>
                            <p class="text-xs text-gray-400">Il y a 2 heures</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-user text-green-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Nouvel utilisateur</p>
                            <p class="text-sm text-gray-500">Jean Dupont s'est inscrit</p>
                            <p class="text-xs text-gray-400">Il y a 3 heures</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-edit text-yellow-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Offre modifiée</p>
                            <p class="text-sm text-gray-500">DesignCo a mis à jour son offre</p>
                            <p class="text-xs text-gray-400">Il y a 5 heures</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Jobs -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-4">Dernières Offres d'Emploi</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Poste</th>
                                <th class="text-left py-2">Entreprise</th>
                                <th class="text-left py-2">Statut</th>
                                <th class="text-left py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="py-2">Développeur Full Stack</td>
                                <td>TechCorp</td>
                                <td><span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-sm">Active</span></td>
                                <td class="space-x-2">
                                    <button class="text-blue-500"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-500"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2">UX Designer</td>
                                <td>DesignCo</td>
                                <td><span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded-full text-sm">En attente</span></td>
                                <td class="space-x-2">
                                    <button class="text-blue-500"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-500"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">Chef de Projet</td>
                                <td>ProManage</td>
                                <td><span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-sm">Active</span></td>
                                <td class="space-x-2">
                                    <button class="text-blue-500"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-500"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Categories and Tags Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Categories -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Catégories</h3>
                    <button class="text-blue-500">Voir tout</button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-laptop-code text-blue-500"></i>
                            <span>Développement</span>
                        </div>
                        <span class="text-gray-500">450 offres</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-paint-brush text-purple-500"></i>
                            <span>Design</span>
                        </div>
                        <span class="text-gray-500">280 offres</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chart-line text-green-500"></i>
                            <span>Marketing</span>
                        </div>
                        <span class="text-gray-500">320 offres</span>
                    </div>
                </div>
            </div>

            <!-- Popular Tags -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Tags Populaires</h3>
                    <button class="text-blue-500">Gérer les tags</button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#javascript</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#react</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#python</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#design</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#ui/ux</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#marketing</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#seo</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">#nodejs</span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>