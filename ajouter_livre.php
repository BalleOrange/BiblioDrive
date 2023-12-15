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

            if (!empty($_POST["submit"])) {
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

                if ($stmt->execute()) {
                    echo "Livre ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout du livre : " . $stmt->errorInfo()[2];
                }
            } else {
                $stmt = $connexion->prepare("SELECT noauteur, prenom FROM auteur");
                $stmt->execute();

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
                echo 'Résumé : <input type="text" class="form-control" name="resume"><br>';
                echo 'Image : <input type="text" class="form-control" name="image"><br>';
                echo '<input type="submit" class="btn btn-outline-secondary" name="submit" value="Ajouter">';
                echo '</form>';
            }
    ?>
</body>

</html>