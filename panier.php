<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Panier</title>
</head>

<body class="container-fluid">

    <?php
    include_once('entete.html');
    ?>
    <div class="row">
        <div class="col-md-9 text-center">
            <?php
            session_start();
            require_once('bdd/biblio.php');
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
            if (isset($_POST["reserver"])) 
            {
                $stmt = $connexion->prepare("SELECT * FROM livre WHERE nolivre = :nolivre");
                $stmt->bindParam(':nolivre', $_SESSION["noEmprunter"]);
                $stmt->execute();
            
                $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
            
                if (!isset($_SESSION["panier"])) 
                {
                    $_SESSION["panier"] = array();
                }
            
                $nouveauLivre = array(
                    "titre" => $enregistrement->titre,
                    "auteur" => $enregistrement->noauteur,
                    "anneeparution" => $enregistrement->anneeparution,
                    "nolivre" => $enregistrement->nolivre
                );
                
                $stmt = $connexion->prepare("SELECT COUNT(*) As count FROM emprunter WHERE mel =:mel");
                $stmt->bindParam(":mel", $_SESSION["mel"]);
                $stmt->execute();
                $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
                $resa = 5 - $enregistrement->count - count($_SESSION["panier"]);

                if (!livreDejaDansPanier($nouveauLivre, $_SESSION["panier"])) 
                {
                    if($resa != 0)
                    {
                        array_push($_SESSION["panier"], $nouveauLivre);
                    }
                    else
                    {
                        echo '<p class="text-danger">Nombre maximum de réservation atteint</p>';
                    }
                } 
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
            
            if(isset($_SESSION["panier"]) && count($_SESSION["panier"]) > 0) 
            {
                $resa = 5 - $enregistrement->count - count($_SESSION["panier"]);
                echo '<p class="text-info">(encore ' . $resa . ' réservation(s) possible(s), ' . $enregistrement->count . ' emprunt(s) en cours)</p>';
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
                if (count($_SESSION["panier"]) > 0) 
                {
                    echo '<br><form method="post">';
                    echo '<input type="submit" class="btn btn-outline-secondary" name="valider" value="Valider le panier">';
                    echo '</form>';
                }
            }

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

            if (isset($_POST["valider"])) 
            {
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
            ?>
        </div>
        <div class="col-md-3">
            <?php
            include_once('authentification.php');
            ?>
        </div>
    </div>
</body>

</html>