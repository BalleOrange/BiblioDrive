<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
        {
            $profil = $_SESSION['profil'];
            if ($profil === 'Administrateur') 
            {
                echo '<div class="text-center border p-3">';
                echo '<h3>Administrateur :</h3>';
                echo $_SESSION['prenom'] . " " . $_SESSION['nom'] . "<br>";
                echo $_SESSION['mel'] . "<br><br>";
                echo '<form method="post">
                    <input type="submit" class="btn btn-outline-secondary" name="deconnexion" value="Se déconnecter">
                    </form>';
                if (isset($_POST["deconnexion"])) 
                {
                    session_destroy();
                    header("Location: accueil.php");
                    exit();
                }
                echo '</div>';
            } 
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
                if (isset($_POST["deconnexion"])) 
                {
                    session_destroy();
                    header("Location: accueil.php");
                    exit();
                }
                echo '</div>';
            }
        } 
        else 
        {
            if(!isset($_POST["connexion"]))
            {
            echo '<div class="text-center border p-3">';
            echo '<h2>Se connecter</h2>';
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
            else
            {
                require_once('bdd/biblio.php');
                    
                $mel = $_POST['identifiant'];
                $mot_de_passe = $_POST['pswd'];
                    
                $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE mel=:mel");
                $stmt->bindParam(':mel', $mel);
                $stmt->execute();
                    
                $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
                    
                if (password_verify($mot_de_passe, $enregistrement->motdepasse)) 
                {
                    $_SESSION['mel'] = $enregistrement->mel;
                    $_SESSION['nom'] = $enregistrement->nom;
                    $_SESSION['prenom'] = $enregistrement->prenom;
                    $_SESSION['adresse'] = $enregistrement->adresse;
                    $_SESSION['codepostal'] = $enregistrement->codepostal;
                    $_SESSION['ville'] = $enregistrement->ville;
                    $_SESSION['profil'] = $enregistrement->profil;
                    $_SESSION['loggedin'] = true;
                    $profil = $_SESSION['profil'];
                    if ($profil === 'Administrateur') 
                    {
                        header("Location: admin.php");
                        exit();
                    }
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