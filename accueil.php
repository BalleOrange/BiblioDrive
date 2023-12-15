<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <title>Accueil</title>
</head>

<body class="container-fluid">

  <?php
  include_once('entete.html');
  ?>
  <div class="row">
    <div class="col-md-9 text-center align-items-center">
      <div class=" justify-content-center align-items-center">
      <h1 class="text-success">Dernières acquisitions</h1>
      <div id="demo" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
        </div>

        <div class="carousel-inner">
        <?php
            require_once('bdd/biblio.php');
            $stmt = $connexion->prepare("SELECT image FROM livre ORDER BY dateajout DESC LIMIT 2");
            $stmt->execute();
                $active = "active";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                {
                    ?>
                    <div class="carousel-item <?php echo $active; ?>">
                      <img src="img/<?php echo $row["image"]; ?>" alt="Image indisponible" class="img-fluid">
                    </div>
                    <?php
                    $active = "";
                }
            ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
      </div>

    </div>

    <div class="col-md-3">
      <?php
      session_start();
      include_once('authentification.php');
      ?>
    </div>
  </div>
</body>

</html>