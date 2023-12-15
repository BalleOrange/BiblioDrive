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
  include('entete.html');
  session_start();
  ?>
  <div class="row">
    <?php
    require_once('bdd/biblio.php');
    $stmt = $connexion->prepare("SELECT * FROM emprunter");
    $stmt->execute();
    date_default_timezone_set('Europe/Paris');
    $date = date('Y-m-d');
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
    $stmt = $connexion->prepare("SELECT * FROM livre WHERE nolivre =:nolivre");
    $stmt->bindParam(':nolivre', $_GET["nolivre"]);
    $stmt->execute();
    $enregistrement1 = $stmt->fetch(PDO::FETCH_OBJ);
    $stmt = $connexion->prepare("SELECT auteur.nom, auteur.prenom FROM auteur INNER JOIN livre ON auteur.noauteur = livre.noauteur WHERE livre.nolivre =:nolivre");
    $stmt->bindParam(":nolivre", $enregistrement1->nolivre);
    $stmt->execute();
    $enregistrement2 = $stmt->fetch(PDO::FETCH_OBJ);
    echo '<div class="col-md-6">';
    echo '<p>Auteur : ' . $enregistrement2->prenom . ' ' . $enregistrement2->nom . '</p>';
    echo '<p>ISBN13 : ' . $enregistrement1->isbn13 . '</p>';
    echo '<p class="text-danger">Résumé du livre</p>';
    echo "<p>" . $enregistrement1->resume . "</p>";
    echo "<p>Date de paruption : " . $enregistrement1->anneeparution . "</p>";
    $stmt = $connexion->prepare("SELECT * FROM emprunter WHERE nolivre =:nolivre");
    $stmt->bindParam(':nolivre', $_GET["nolivre"]);
    $stmt->execute();
    $enregistrement = $stmt->fetch(PDO::FETCH_OBJ);
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
    {
      $profil = $_SESSION['profil'];
      if ($profil === 'Membre') 
      {
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
        else 
        {
          echo '<p class="text-danger">Indisponible</p>';
        }
      }
    }
    else 
    {
      if ($enregistrement === false || empty($enregistrement)) 
      {
        echo '<div class="d-flex">';
        echo '<p class="text-success">Disponible</p>'; 
        echo '<p class="text-danger mx-4">Pour pouvoir réserver vous devez posséder un compte et vous identifier</p>';
        echo '</div>';
      } 
      else 
      {
        echo '<p class="text-danger">Indisponible</p>';
      }
    }
    echo '</div>';

    echo '
        <div class="col-md-3">
            <img src="img/' . $enregistrement1->image . '" height="400" alt="Pas d image disponible">
        </div>';

    ?>
    <div class="col-md-3">
      <?php
      include_once('authentification.php');
      ?>
    </div>
  </div>
  
</body>

</html>