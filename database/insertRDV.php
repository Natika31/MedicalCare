<?php
require('../database/connexionBDD.php');
require('../model/patientsModel.php');
require('../model/medecinsModel.php');
require('../model/rdvModel.php');

$patients = requestPatientsId($linkpdo,$_GET['idPat']);
foreach($patients as $pat){
  $patients = $pat;
}

$medecins = explode(' ',$_POST['medecin']);
$medecins = requestMedecinSpecifique($linkpdo, $medecins[0], $medecins[1]);
foreach($medecins as $med){
  $medecins = $med;
}

$date = strtotime($_POST['Date']);

//conversion des heures en TimeStamp unix
$heure = strtotime(date($_POST['Date']." ".$_POST['heure']));
$duree = strtotime(date("01-01-1970"." ".$_POST['Duree']))+3600;


$pdo = $linkpdo;
$stmt = $pdo->prepare("INSERT into RendezVous (idRendezVous, date, heure, duree, Patient_idPatient, Medecin_idMedecin)
VALUES (default, :date, :heure, :duree, :Patient_idPatient, :Medecin_idMedecin)");


$rdv = requestRdvMedecin($pdo,$medecins['idMedecin']);

if(verificationInsertion($rdv,$heure,$duree)){
  $stmt->execute(array('date' => $date,
                        'heure' => $heure,
                        'duree' => $duree,
                        'Patient_idPatient' => $patients['idPatient'],
                        'Medecin_idMedecin' =>  $medecins['idMedecin']));
  header('Location: ../patientList.php');
} else {
  echo '<script type="text/javascript">alert(Veuillez sélectionner un autre horaire.);</script>';
  header('Location: ../priseRdv.php?id='.$patients['idPatient']);
}

function verificationInsertion($rdv,$heureInsert,$dureeInsert){
  foreach($rdv as $rendezVous){
    if(!chevauchement($rendezVous['heure'],$rendezVous['duree'],$heureInsert,$dureeInsert)){
      return false;
    }
  }
  return true;
}

// Vérification de chevauchement
// Ne fonctionne qu'avec les timestamp UNIX
function chevauchement($heure1, $duree1, $heure2, $duree2){
  $creneau1 = $heure1 + $duree1;
  $creneau2 = $heure2 + $duree2;
  if($heure1 >= $creneau2 || $creneau1 <= $heure2){
    return true;
  } else {
    return false;
  }
}
 ?>
