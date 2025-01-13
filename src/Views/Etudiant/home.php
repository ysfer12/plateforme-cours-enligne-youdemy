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
            <a href="#" class="flex items-center space-x-2 bg-blue-600 text-white p-3 rounded-lg">
                <i class="fas fa-chart-line"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-briefcase"></i>
                <span>Mes offres</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-users"></i>
                <span>Candidatures</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
            <a href="#" class="flex items-center space-x-2 hover:bg-gray-700 p-3 rounded-lg transition">
                <i class="fas fa-building"></i>
                <span>Profil entreprise</span>
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
            <div>
                <h1 class="text-2xl font-bold mb-1">Bonjour, TechCorp</h1>
                <p class="text-gray-600">Voici un aperçu de vos activités de recrutement</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i> Publier une offre
                </button>
                <div class="relative">
                    <i class="fas fa-bell text-gray-500 text-xl cursor-pointer"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center">5</span>
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
    </main>

    <script>
        // Notifications dropdown
        const notificationBtn = document.querySelector('.fa-bell').parentElement;
        notificationBtn.addEventListener('click', function() {
            // Add notification dropdown logic here
        });

        // Job posting buttons
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
    </script>
</body>
</html>