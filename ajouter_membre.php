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
    if (!isset($_POST["creer"])) 
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
        $stmt = $connexion->prepare("INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, codepostal, profil) VALUES (:mel, :mdp, :nom, :prenom, :adresse, :ville, :codePostal, :membre)");
        $stmt->bindParam(':mel', $_POST["mel"]);
        $stmt->bindParam(':mdp', $_POST["mdp"]);
        $stmt->bindParam(':nom', $_POST["nom"]);
        $stmt->bindParam(':prenom', $_POST["prenom"]);
        $stmt->bindParam(':adresse', $_POST["adresse"]);
        $stmt->bindParam(':ville', $_POST["ville"]);
        $stmt->bindParam(':codePostal', $_POST["codePostal"]);
        $membre = "Membre";
        $stmt->bindParam(':membre', $membre);
        $stmt->execute();

        $nb_lignes_inserees = $stmt->rowCount();
        echo $nb_lignes_inserees . " ligne(s) insérée(s).<br>";
    }
    ?>
</body>

</html>