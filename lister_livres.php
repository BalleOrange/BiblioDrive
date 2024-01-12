<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Lister Livre</title>
</head>

<body class="container-fluid">
  <?php
  //Inclusion de l'entete
  include('entete.html');
  ?>
  <div class="row">
    <div class="col-md-9">
      <?php
      require_once('bdd/biblio.php');
      $auteur = $_POST["searchbar"];
      //Requete pour prendre les livres d'un certain auteur
      $stmt = $connexion->prepare("SELECT * FROM livre INNER JOIN auteur ON livre.noauteur = auteur.noauteur WHERE auteur.nom =:auteur");
      $stmt->bindParam(':auteur', $auteur);
      $stmt->execute();

      //Affichage de tous les livres de l'auteur sous forme de lien
      while ($enregistrement = $stmt->fetch(PDO::FETCH_OBJ)) 
      {
        echo "<a href='http://localhost/bibliodrive/detail.php?nolivre=" . $enregistrement->nolivre . "'>" . $enregistrement->titre . " " . $enregistrement->anneeparution . "<br></a>";
      }
      ?>
    </div>
    <!--Inclusion de la page de connexion-->
    <div class="col-md-3">
      <?php
      session_start();
      include_once('authentification.php');
      ?>
    </div>
  </div>
</body>

</html>