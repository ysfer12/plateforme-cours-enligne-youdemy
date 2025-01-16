<?php
namespace Controllers;

use Models\Cours;
use Models\EnseignantCourModel;
use Config\Database;
use PDOException;

class AjouterCoursController
{
    public function index()
    {
        session_start();

        // Vérifier la connexion de l'utilisateur (enseignant)
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../Auth/login.php');
            exit();
        }

        // Récupérer les informations de l'utilisateur
        try {
            $requete = Database::getInstance()->getConnection()->prepare("
                SELECT u.*, r.titre as role_titre 
                FROM Utilisateurs u 
                JOIN Role r ON u.role_id = r.role_id
                WHERE u.id = :id
            ");
            $requete->execute([':id' => $_SESSION['user_id']]);
            $utilisateur = $requete->fetch(\PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $erreur = "Erreur de récupération des informations : " . $e->getMessage();
        }

        // Récupérer les catégories
        try {
            $requeteCategories = Database::getInstance()->getConnection()->query("SELECT category_id, nom FROM Category ORDER BY nom");
            $categories = $requeteCategories->fetchAll(\PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $erreur = "Erreur de récupération des catégories : " . $e->getMessage();
        }

        // Traitement du formulaire d'ajout de cours
        $erreurs = [];
        $succes = '';
        $titre = '';
        $description = '';
        $type_contenu = '';
        $lien_contenu = '';
        $category_id = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et valider les données
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $type_contenu = $_POST['type_contenu'] ?? '';
            $lien_contenu = trim($_POST['lien_contenu'] ?? '');
            $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

            // Validation
            if (empty($titre)) {
                $erreurs[] = "Le titre est obligatoire";
            }

            if (empty($type_contenu)) {
                $erreurs[] = "Le type de contenu est obligatoire";
            }

            if ($category_id <= 0) {
                $erreurs[] = "Veuillez sélectionner une catégorie";
            }

            // Si pas d'erreurs, ajouter le cours
            if (empty($erreurs)) {
                try {
                    // Créer un nouvel objet Cours
                    $nouveauCours = new Cours(
                        $titre,
                        $description,
                        $type_contenu,
                        $lien_contenu,
                        $_SESSION['user_id'],
                        $category_id
                    );

                    // Utiliser le modèle pour ajouter le cours
                    $coursModel = new EnseignantCourModel();
                    $resultat = $coursModel->ajouter($nouveauCours);

                    if ($resultat) {
                        $succes = "Le cours a été ajouté avec succès";
                        // Réinitialiser les variables
                        $titre = $description = $type_contenu = $lien_contenu = $category_id = '';
                    }
                } catch(PDOException $e) {
                    $erreurs[] = "Erreur d'ajout de cours : " . $e->getMessage();
                }
            }
        }

        require_once '../Views/ajouter-cours.php';
    }
}