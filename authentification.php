<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
        //Verification di si quelqu'un est connecté
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
        {
            //Récupération du proful
            $profil = $_SESSION['profil'];
            //Si la personne est adminisatrateur on affiche les données qui la concerne
            if ($profil === 'Administrateur') 
            {
                echo '<div class="text-center border p-3">';
                echo '<h3>Administrateur :</h3>';
                echo $_SESSION['prenom'] . " " . $_SESSION['nom'] . "<br>";
                echo $_SESSION['mel'] . "<br><br>";
                echo '<form method="post">
                    <input type="submit" class="btn btn-outline-secondary" name="deconnexion" value="Se déconnecter">
                    </form>';
                //Si se déconnecter est appuyé on arrête la session et renvoie la personne à l'accueil
                if (isset($_POST["deconnexion"])) 
                {
                    session_destroy();
                    header("Location: accueil.php");
                    exit();
                }
                echo '</div>';
            } 
            //Si la personne est membre on affiche les données qui la concerne
            elseif ($profil === 'Membre') 
            {
                echo '<div class="text-center border p-3">';
                echo $_SESSION['prenom'] . " " . $_SESSION['nom'] . "<br>";
                echo $_SESSION['mel'] . "<br><br>";
                echo $_SESSION['adresse'] . "<br>";
                echo $_SESSION['codepostal'] . " " . $_SESSION['ville'] . "<br><br>";
                echo '<form method="post">
                    <input type="submit" class="btn btn-outline-secondary" name="deconnexion" value="Se déconnecter">
                    </form>';
                //Si se déconnecter est appuyé on arrête la session et renvoie la personne à l'accueil
                if (isset($_POST["deconnexion"])) 
                {
                    session_destroy();
                    header("Location: accueil.php");
                    exit();
                }
                echo '</div>';
            }
        } 
        //Si personne est connecté
        else 
        {
            //Si le bouton connexion n'est pas encore appuyé on affiche le formulaire de connexion
            if(!isset($_POST["connexion"]))
            {
                echo '<div class="text-center border p-3">';
                echo '<h2>Se connecter</h2>';
                //Si le mot de passe ou email est invalide affichage d'un message d'erreur
                if(isset($_SESSION['erreur_connexion']))
                {
                    echo '<p class="text-danger">' . $_SESSION['erreur_connexion'] . '</p>';
                    unset($_SESSION['erreur_connexion']);
                }
                echo '<form method="post">';
                echo '<p>Identifiant</p>';
                echo '<input type="text" class="form-control" name="identifiant"><br><br>';
                echo '<p>Mot de passe</p>';
                echo '<input type="password" class="form-control" name="pswd"><br><br>';
                echo '<input type="submit" class="btn btn-outline-secondary" name="connexion" value="Connexion">';
                echo '</form>';
                echo '<div>';
            }
            //Si le bouton connexion est appuyé
            else
            {
                require_once('bdd/biblio.php');
                //Récupération du mot de passe et email
                $mel = $_POST['identifiant'];
                $mot_de_passe = $_POST['pswd'];
                
                //Requete pour voir si le mail correspond à un persoone
                $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE mel=:mel");
                $stmt->bindParam(':mel', $mel);
                $stmt->execute();
                    
                $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
                //Verifier si le mot de passe entré est le bon
                if (password_verify($mot_de_passe, $enregistrement->motdepasse)) 
                {
                    //Récupération des données de la personne dans  des variables de session
                    $_SESSION['mel'] = $enregistrement->mel;
                    $_SESSION['nom'] = $enregistrement->nom;
                    $_SESSION['prenom'] = $enregistrement->prenom;
                    $_SESSION['adresse'] = $enregistrement->adresse;
                    $_SESSION['codepostal'] = $enregistrement->codepostal;
                    $_SESSION['ville'] = $enregistrement->ville;
                    $_SESSION['profil'] = $enregistrement->profil;
                    $_SESSION['loggedin'] = true;
                    $profil = $_SESSION['profil'];
                    //Si la personne est admin elle est rédirigée vers la page admin
                    if ($profil === 'Administrateur') 
                    {
                        header("Location: admin.php");
                        exit();
                    }
                    //Sinon elle est redirigée vers l'accueil
                    else
                    {
                        header("Location: accueil.php");
                        exit();
                    }
                }
                else
                {
                    $_SESSION['loggedin'] = false;
                    $_SESSION['erreur_connexion'] = "Identifiants incorrects. Veuillez réessayer.";
                    header("Location: accueil.php");
                    exit();
                }
            }
        }    
?>
</body>
</html>