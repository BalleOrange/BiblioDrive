<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Administrateur</title>
</head>
<body class="container-fluid">
    <?php
    require_once('bdd/biblio.php');
    session_start();

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
        {
            $profil = $_SESSION['profil'];
            if ($profil === 'Administrateur') 
            {
            echo '<div class="row">
                <div class="col-md-9">
                    <p>La Bibliothèque de Moulinsart est fermée au public jusqu à nouvel ordre. Mais il vous est possible de réserver et retirer vos livres via notre service Biblio Drive !</p>
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
                    <div class="col-md-9">';
                        
                        

                    if(isset($_POST["creerMembre"]))
                    {
                        if(!isset($_POST["creer"]))
                        {
                        echo '<h1>Créer un membre</h1>';
                        echo '<form method="post">';
                        echo 'Mel : <input type="text" class="form-control" name="mel"><br>';
                        echo 'Mot de passe : <input type="text" class="form-control" name="mdp"><br>';
                        echo 'Nom : <input type="text" class="form-control" name="nom"><br>';
                        echo 'Prénom : <input type="text" class="form-control" name="prenom"><br>';
                        echo 'Adresse : <input type="text" class="form-control" name="adresse"><br>';
                        echo 'Ville : <input type="text" class="form-control" name="ville"><br>';
                        echo 'Code Postal : <input type="text" class="form-control" name="codePostal"><br>';
                        echo '<input type="submit" class="btn btn-outline-secondary" name="creer" value="Créer un membre">';
                        echo '</form>';
                        }
                        else
                        {
                            require_once('bdd/biblio.php');
                            $stmt = $connexion->prepare("INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, codepostal, profil) VALUES (:mel, :mdp, :nom, :prenom, :adresse, :ville, :codePostal, :membre)");
                            $stmt->bindParam(':mel', $_POST["mel"]);
                            $stmt->bindParam(':mdp', $_POST["mdp"]);
                            $stmt->bindParam(':nom', $_POST["nom"]);
                            $stmt->bindParam(':prenom', $_POST["prenom"]);
                            $stmt->bindParam(':adresse', $_POST["adresse"]);
                            $stmt->bindParam(':ville', $_POST["ville"]);
                            $stmt->bindParam(':codePostal', $_POST["codePostal"]);
                            $stmt->bindParam(':membre', "Membre");
                            $stmt->execute();

                            $nb_lignes_inserees = $stmt->rowCount();
                            echo $nb_lignes_inserees . " ligne(s) insérée(s).<br>";  
                        }
                    }
                    elseif(isset($_POST["ajouterLivre"]))
                    {
                
                        $stmt = $connexion->prepare("SELECT prenom FROM auteur");
                        $stmt->execute();

                        if (!isset($_POST["ajouter"])) 
                        {
                            echo '<h1>Ajouter un livre</h1>';
                            echo '<div class="dropdown">
                                Auteur : <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Sélectionner un auteur
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';
                            echo '<form method="post">';
                            while ($enregistrement = $stmt->fetch(PDO::FETCH_OBJ))
                            {
                                echo '<li><a class="dropdown-item" href="?prenom=' . $enregistrement->prenom . '">' . $enregistrement->prenom . '</a></li>';
                                $test = $enregistrement->prenom;
                            }
                            echo '</ul><br>';
                            echo 'Titre : <input type="text" class="form-control" name="titre"><br>';
                            echo 'ISBN13 : <input type="text" class="form-control" name="isbn13"><br>';
                            echo 'Année de parution : <input type="text" class="form-control" name="anneeParution"><br>';
                            echo 'Résumé : <input type="text" class="form-control" name="resume"><br>';
                            echo 'Image : <input type="text" class="form-control" name="image" value="img/"><br>';
                            echo '<input type="submit" class="btn btn-outline-secondary" name="ajouter" value="Ajouter">';
                            echo '</form>';
                            echo '</div>';
                        } 
                        else
                        {
                            $stmt = $connexion->prepare("SELECT noauteur FROM auteur WHERE prenom=:prenom");
                            $stmt->bindParam(':prenom', $_GET["prenom"]);
                            $stmt->execute();
                            
                            $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
                            $noauteur = $enregistrement->noauteur;
                            $stmt = $connexion->prepare("INSERT INTO livre (noauteur, titre, isbn13, anneeparution, resume, dateajout, image) VALUES (:noauteur, :titre, :isbn13, :anneeparution, :resume, :dateajout, :image)");
                            $stmt->bindParam(':noauteur', $noauteur);
                            $stmt->bindParam(':titre', $_POST["titre"]);
                            $stmt->bindParam(':isbn13', $_POST["isbn13"]);
                            $stmt->bindParam(':anneeparution', $_POST["anneeParution"]);
                            $stmt->bindParam(':resume', $_POST["resume"]);
                            date_default_timezone_set('Europe/Paris');
                            $date = date('Y-m-d');
                            $stmt->bindParam(':dateajout', $date);
                            $stmt->bindParam(':image', $_POST["image"]);
                            $stmt->execute();
                            
                            $nb_lignes_inserees = $stmt->rowCount();
                            echo $nb_lignes_inserees . " ligne(s) insérée(s).<br>";   
                        } 
                    }
                    echo '</div>';
                    echo '<div class="col-md-3">';
                    include_once("authentification.php");
                    echo '</div>';
            echo '</div>';
            }
            elseif ($profil === 'Membre') 
            {
                echo '<p class="text-danger">Vous devez être administrateur !</p>';
            }
        }
        else
        {
            echo '<p class="text-danger">Vous devez être administrateur !</p>';
        }



    ?>
</body>
</html>