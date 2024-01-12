<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Detail</title>
</head>

<body class="container-fluid">
  <?php
  //Inclusion de l'entete
  include('entete.html');
  session_start();
  ?>
  <div class="row">
    <?php
    require_once('bdd/biblio.php');
    //Récupération de tout ce qu'il y a dans la table emprunter
    $stmt = $connexion->prepare("SELECT * FROM emprunter");
    $stmt->execute();
    //Récupération de la date actuelle sous le format année, mois et jour
    date_default_timezone_set('Europe/Paris');
    $date = date('Y-m-d');
    //Vérification sur toutes les lignes de la table emprunter si la date retour est dépassée. Si oui, suppression de la ligne pour rendre le livre disponible
    while($enregistrement = $stmt->fetch(PDO::FETCH_OBJ))
    {
        if($enregistrement->dateretour <= $date)
        {
            $stmt = $connexion->prepare("DELETE FROM `emprunter` WHERE `emprunter`.`mel` =:mel AND `emprunter`.`nolivre` =:nolivre AND `emprunter`.`dateemprunt` =:dateempreunt");
            $stmt->bindParam(":mel", $enregistrement->mel);
            $stmt->bindParam(":nolivre", $enregistrement->nolivre);
            $stmt->bindParam(":dateempreunt", $enregistrement->dateemprunt);
            $stmt->execute();
        }
    }
    //Affichage des données du livre selon le numéro de livre récupéré dans l'url
    $stmt = $connexion->prepare("SELECT * FROM livre WHERE nolivre =:nolivre");
    $stmt->bindParam(':nolivre', $_GET["nolivre"]);
    $stmt->execute();
    $detailLivre = $stmt->fetch(PDO::FETCH_OBJ);

    $stmt = $connexion->prepare("SELECT auteur.nom, auteur.prenom FROM auteur INNER JOIN livre ON auteur.noauteur = livre.noauteur WHERE livre.nolivre =:nolivre");
    $stmt->bindParam(":nolivre", $detailLivre->nolivre);
    $stmt->execute();
    $auteur = $stmt->fetch(PDO::FETCH_OBJ);
    echo '<div class="col-md-6">';
    echo '<p>Auteur : ' . $auteur->prenom . ' ' . $auteur->nom . '</p>';
    echo '<p>ISBN13 : ' . $detailLivre->isbn13 . '</p>';
    echo '<p class="text-danger">Résumé du livre</p>';
    echo "<p>" . $detailLivre->resume . "</p>";
    echo "<p>Date de paruption : " . $detailLivre->anneeparution . "</p>";

    $stmt = $connexion->prepare("SELECT * FROM emprunter WHERE nolivre =:nolivre");
    $stmt->bindParam(':nolivre', $_GET["nolivre"]);
    $stmt->execute();
    $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
    //Vérification pour voir si l'utilisateur est connecté
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
    {
      //Récupération du profil
      $profil = $_SESSION['profil'];
      //Si l'utilisateur est membre
      if ($profil === 'Membre') 
      {
        //Si le livre n'est pas dans la table emprunter on affiche qu'il est disponible et le bouton pour le réserver
        if ($enregistrement === false || empty($enregistrement)) 
        {
          echo '<div class="d-flex">';
          echo '<p class="text-success">Disponible</p>';
          echo '<form method="post" action="panier.php">';
          echo '<a class="mx-4" href="panier.php"><input type="submit" class="btn btn-outline-secondary" name="reserver" value="Emprunter (ajout au panier)"></a>';
          echo '</form>';
          echo '</div>';
          $_SESSION["noEmprunter"] = $_GET["nolivre"];
        } 
        //Sinon le livre est indisponible et impossible de le reserver
        else 
        {
          echo '<p class="text-danger">Indisponible</p>';
        }
      }
    }
    //Si personne est connecté
    else 
    {
      //Si le livre n'est pas dans la table emprunter on affiche qu'il est disponible mais pas le bouton pour le réserver car il faut etre connecté
      if ($enregistrement === false || empty($enregistrement)) 
      {
        echo '<div class="d-flex">';
        echo '<p class="text-success">Disponible</p>'; 
        echo '<p class="text-danger mx-4">Pour pouvoir réserver vous devez posséder un compte et vous identifier</p>';
        echo '</div>';
      } 
       //Sinon le livre est indisponible
      else 
      {
        echo '<p class="text-danger">Indisponible</p>';
      }
    }
    echo '</div>';

    //Affichage de l'image de couverture
    echo '
        <div class="col-md-3">
            <img src="img/' . $detailLivre->image . '" height="400" alt="Pas d image disponible">
        </div>';

    ?>
    <!--Inclusion de la page de connexion-->
    <div class="col-md-3">
      <?php
      include_once('authentification.php');
      ?>
    </div>
  </div>
  
</body>

</html>