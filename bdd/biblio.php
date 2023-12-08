<?php
try {
  $dns = 'mysql:host=localhost;dbname=biblio';
  $utilisateur = 'root';
  $motDePasse = '';
  $connexion = new PDO( $dns, $utilisateur, $motDePasse );
} catch (Exception $e) {
  echo "Connexion à MySQL impossible : ", $e->getMessage();
  die();
}
?>