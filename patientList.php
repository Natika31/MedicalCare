<?php require('functions/verifConnexion.php')?>
<!doctype html>
<html>
  <head>
      <meta charset="utf-8">
      <title>Medical Care - patient</title>
      <?php require('css/style.php');  ?>
  </head>

  <body>
      <?php require('view/navBar.html'); ?>
      <main>
        <h1> Rechercher un Patient : </h1>
        <?php require('view/patientTab.php'); ?>
        <?php require('view/newPatient.php'); ?>
    </main>
  </body>
</html>
