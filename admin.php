<?php
echo'<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Administrateur</title>
</head>

<body class="container-fluid">';
    
    require_once('bdd/biblio.php');
    session_start();

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
    {
        $profil = $_SESSION['profil'];
        if ($profil === 'Administrateur') 
        {
            echo '<div class="row">
                <div class="col-md-9">
                    <p>La Bibliothèque de Moulinsart est fermée au public jusqu’à nouvel ordre. Mais il vous est possible de réserver et retirer vos livres via notre service Biblio Drive !</p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="form-control">
                            <form method="post" class="input-group">
                                <button class="mx-4 btn btn-outline-secondary" type="submit" name="ajouterLivre">Ajouter un livre</button>
                                <button class="mx-4 btn btn-outline-secondary" type="submit" name="creerMembre">Créer un membre</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <img src="img/2.jpg" alt="" class="img-fluid">
                </div>
            </div>';

            echo '<div class="row">
                    <div class="col-md-9 d-flex justify-content-center text-center align-items-center">';
            if (isset($_POST["creerMembre"])) 
            {
                if (!isset($_POST["creer"])) 
                {
                    echo '<div class="col-md-5">';
                    echo '<h1>Créer un membre</h1>';
                    echo '<form method="post">';
                    echo 'Mel : <input type="text" class="form-control" name="mel"><br>';
                    echo 'Mot de passe : <input type="password" class="form-control" name="mdp"><br>';
                    echo 'Nom : <input type="text" class="form-control" name="nom"><br>';
                    echo 'Prénom : <input type="text" class="form-control" name="prenom"><br>';
                    echo 'Adresse : <input type="text" class="form-control" name="adresse"><br>';
                    echo 'Ville : <input type="text" class="form-control" name="ville"><br>';
                    echo 'Code Postal : <input type="text" class="form-control" name="codePostal"><br>';
                    echo '<input type="submit" class="btn btn-outline-secondary" name="creer" value="Créer un membre">';
                    echo '</form>';
                    echo '</div>';
                } 
            }
            elseif(isset($_POST["creer"]))  
            {
                $stmt = $connexion->prepare("INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, codepostal, profil) VALUES (:mel, :mdp, :nom, :prenom, :adresse, :ville, :codePostal, :membre)");
                $stmt->bindParam(':mel', $_POST["mel"]);
                $mdp = password_hash($_POST["mdp"], PASSWORD_ARGON2I);
                $stmt->bindParam(':mdp', $mdp);
                $stmt->bindParam(':nom', $_POST["nom"]);
                $stmt->bindParam(':prenom', $_POST["prenom"]);
                $stmt->bindParam(':adresse', $_POST["adresse"]);
                $stmt->bindParam(':ville', $_POST["ville"]);
                $stmt->bindParam(':codePostal', $_POST["codePostal"]);
                $membre = "Membre";
                $stmt->bindParam(':membre', $membre);
                $stmt->execute();

                header("Location: admin.php");
                exit();
            }
            else
            {
                if (!isset($_POST["submit"])) 
                {
                    $stmt = $connexion->prepare("SELECT noauteur, prenom FROM auteur");
                    $stmt->execute();
                    echo '<div class="col-md-5">';
                    echo '<h1>Ajouter un livre</h1>';
                    echo '<form method="post">';
                    echo '<label for="auteur">Auteur :</label>';
                    echo '<select class="form-select" name="auteur">';
                    while ($enregistrement = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $enregistrement['noauteur'] . '">' . $enregistrement['prenom'] . '</option>';
                    }
                    echo '</select><br>';
                    echo 'Titre : <input type="text" class="form-control" name="titre"><br>';
                    echo 'ISBN13 : <input type="text" class="form-control" name="isbn13"><br>';
                    echo 'Année de parution : <input type="text" class="form-control" name="anneeParution"><br>';
                    echo 'Résumé : <textarea class="form-control" name="resume" rows="7"></textarea><br>';
                    echo 'Image : <input type="text" class="form-control" name="image"><br>';
                    echo '<input type="submit" class="btn btn-outline-secondary" name="submit" value="Ajouter">';
                    echo '</form>';
                    echo '</div>';
                } 
                else 
                {
                    $noauteur = $_POST["auteur"];
                    $titre = $_POST["titre"];
                    $isbn13 = $_POST["isbn13"];
                    $anneeParution = $_POST["anneeParution"];
                    $resume = $_POST["resume"];
                    $image = $_POST["image"];
    
                    $stmt = $connexion->prepare("INSERT INTO livre (noauteur, titre, isbn13, anneeparution, resume, dateajout, image) VALUES (:noauteur, :titre, :isbn13, :anneeParution, :resume, :dateajout, :image)");
                    $dateajout = date('Y-m-d'); 
                    $stmt->bindParam(':noauteur', $noauteur);
                    $stmt->bindParam(':titre', $titre);
                    $stmt->bindParam(':isbn13', $isbn13);
                    $stmt->bindParam(':anneeParution', $anneeParution);
                    $stmt->bindParam(':resume', $resume);
                    $stmt->bindParam(':dateajout', $dateajout);
                    $stmt->bindParam(':image', $image);
                    $stmt->execute();

                    header("Location: admin.php");
                    exit();
                }
            }
        }
        echo '</div>
                <div class="col-md-3">';
        include_once("authentification.php");
        echo '</div>
                </div>';
    } 
    elseif ($profil === 'Membre') 
    {
        echo '<p class="text-danger">Vous devez être administrateur !</p>';
    } 
    else 
    {
        echo '<p class="text-danger">Vous devez être administrateur !</p>';
    } 
echo '</body>

</html>';
?>