<?php
session_start();
require_once('bdd/biblio.php');

//Fonction pour vérifier si le livre est déjà présent dans le panier
function livreDejaDansPanier($nouveauLivre, $panier)
{
    foreach ($panier as $livre) 
    {
        if ($livre['titre'] === $nouveauLivre['titre'] && $livre['auteur'] === $nouveauLivre['auteur']) 
        {
            return true;
        }
    }
    return false;
}

echo '<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Panier</title>
</head>

<body class="container-fluid">';

    include_once('entete.html');
    if (isset($_SESSION['loggedin']) && $_SESSION['profil'] === "Membre") 
    {
    echo '<div class="row">
        <div class="col-md-9 text-center">';
            //Si le bouton réserver est appuyé
            if (isset($_POST["reserver"])) 
            {
                //Récupération des données du livre
                $stmt = $connexion->prepare("SELECT * FROM livre WHERE nolivre = :nolivre");
                $stmt->bindParam(':nolivre', $_SESSION["noEmprunter"]);
                $stmt->execute();
            
                $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
                
                //Si la list panier existe pas on l'initialise
                if (!isset($_SESSION["panier"])) 
                {
                    $_SESSION["panier"] = array();
                }
                
                //Création d'une variable livre qui est une liste avec toutes les données du livre
                $nouveauLivre = array(
                    "titre" => $enregistrement->titre,
                    "auteur" => $enregistrement->noauteur,
                    "anneeparution" => $enregistrement->anneeparution,
                    "nolivre" => $enregistrement->nolivre
                );
                //Récupération du nombre de livre emprunté par l'utilisateur
                $stmt = $connexion->prepare("SELECT COUNT(*) As count FROM emprunter WHERE mel =:mel");
                $stmt->bindParam(":mel", $_SESSION["mel"]);
                $stmt->execute();
                $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
                //Voir le nombre de reservation encore possible en partant de 5 et en enlevant le nombre de livre emprunter + ceux dans le panier
                $resa = 5 - $enregistrement->count - count($_SESSION["panier"]);
                
                //Si le livre n'est pas deja dans le panier
                if (!livreDejaDansPanier($nouveauLivre, $_SESSION["panier"])) 
                {
                    //Si un réservation est encore possible on ajoute le livre dans la variable panier
                    if($resa != 0)
                    {
                        array_push($_SESSION["panier"], $nouveauLivre);
                    }
                    //Sinon on affiche un message d'erreur
                    else
                    {
                        echo '<p class="text-danger">Nombre maximum de réservation atteint</p>';
                    }
                } 
                //Sinon on affiche un message d'erreur
                else 
                {
                    echo '<p class="text-danger">Ce livre est déjà dans votre panier.</p>';
                }
            }
            $stmt = $connexion->prepare("SELECT COUNT(*) As count FROM emprunter WHERE mel =:mel");
            $stmt->bindParam(":mel", $_SESSION["mel"]);
            $stmt->execute();
            $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
            
            echo '<h1 class="text-success">Votre panier</h1>';
            //Si panier existe et qu'il contient des éléments
            if(isset($_SESSION["panier"]) && count($_SESSION["panier"]) > 0) 
            {
                $resa = 5 - $enregistrement->count - count($_SESSION["panier"]);
                echo '<p class="text-info">(encore ' . $resa . ' réservation(s) possible(s), ' . $enregistrement->count . ' emprunt(s) en cours)</p>';
                //Pour tous les livres de la liste pannier j'affiche les informations du livre et un bouton supprimer
                foreach ($_SESSION["panier"] as $index => $livre) 
                {
                    $stmt = $connexion->prepare("SELECT auteur.nom, auteur.prenom FROM auteur INNER JOIN livre ON auteur.noauteur = livre.noauteur WHERE livre.nolivre =:nolivre");
                    $stmt->bindParam(":nolivre", $livre["auteur"]);
                    $stmt->execute();
                    $enregistrement1 = $stmt->fetch(PDO::FETCH_OBJ);
                    echo '<div class="d-flex justify-content-center align-items-center text-center">';
                    echo $enregistrement1->prenom . ' ' . $enregistrement1->nom . ' - ' . $livre["titre"] . ' (' . $livre["anneeparution"] . ') ';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="index" value="' . $index . '">';
                    echo '<input type="submit" class="btn btn-outline-secondary mx-4" name="supprimer" value="Supprimer">';
                    echo '</form>';
                    echo '</div>';
                }
                //Si il y a des livres dans le panier le bouton Valider le panier s'affiche
                if (count($_SESSION["panier"]) > 0) 
                {
                    echo '<br><form method="post">';
                    echo '<input type="submit" class="btn btn-outline-secondary" name="valider" value="Valider le panier">';
                    echo '</form>';
                }
            }
            //Si supprimer est appuyé et son index récupéré le livre est retiré de panier et panier mis a jour
            if (isset($_POST["supprimer"]) && isset($_POST["index"])) 
            {
                $index = $_POST["index"];
                if (isset($_SESSION["panier"][$index])) 
                {
                    unset($_SESSION["panier"][$index]);
                    $_SESSION["panier"] = array_values($_SESSION["panier"]);
                }
                header("Location: panier.php");
                exit();
            }
            
            //Si valider le panier est appuyé 
            if (isset($_POST["valider"])) 
            {
                //Tous les livres de panier sont ajoutés dans la table emprunter
                foreach ($_SESSION["panier"] as $index => $livre) 
                {
                    $stmt = $connexion->prepare("INSERT INTO emprunter VALUES (:mel, :nolivre, :dateemprunt, :dateretour)");
                    $stmt->bindParam(":mel", $_SESSION["mel"]);
                    $stmt->bindParam(":nolivre", $livre["nolivre"]);
                    date_default_timezone_set('Europe/Paris');
                    $date = date('Y-m-d');
                    $stmt->bindParam(":dateemprunt", $date);
                    $dateRetour = date('Y-m-d', strtotime('+30 days'));
                    $stmt->bindParam(":dateretour", $dateRetour);
                    $stmt->execute();
                }
                unset($_SESSION["panier"]);
                header("Location: panier.php");
                exit();
            }
    } 
    else
    {
        echo '<p>Vous devez être membre pour accéder au panier</p>';
    }       
        //Inclusion du formulaire de connexion
        echo '</div>
        <div class="col-md-3">';
        include_once('authentification.php');  
        echo'</div>
    </div>
</body>

</html>';
?>