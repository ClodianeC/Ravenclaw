<?php

$strRequeteUtilisateur = "SELECT prenom, nom FROM t_utilisateur";
$pdosResultatUtilisateur = $pdoConnexion->prepare($strRequeteUtilisateur);
$pdosResultatUtilisateur->execute();
$rangee=$pdosResultatUtilisateur->fetch();
$arrUtilisateur["prenom"]=$rangee["prenom"];
$arrUtilisateur["nom"]=$rangee["nom"];

$nomUtilisateur = $arrUtilisateur["prenom"]." ".$arrUtilisateur["nom"];
$pdosResultatUtilisateur->closeCursor();

$strRequeteListes = "SELECT id_liste, nom_liste FROM t_liste ORDER BY nom_liste ASC";
$pdosResultatListes = $pdoConnexion->prepare($strRequeteListes);
$pdosResultatListes->execute();
for($intCptListes=0; $rangee=$pdosResultatListes->fetch();$intCptListes++){
    $arrListes[$intCptListes]["id"]=$rangee["id_liste"];
    $arrListes[$intCptListes]["nom"]=$rangee["nom_liste"];
}
$pdosResultatUtilisateur->closeCursor();
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/styles_clodiane.css">
</head>
<div class="header">
    <p class="bienvenue">Bienvenue <?php echo $nomUtilisateur ?></p>
    <a href="" class="icon_header"><div class="icon" id="deconnexion"></div>Déconnexion</a>
    <a href="" class="icon_header"><div class="icon id="compte></div>Mon compte</a>
    <a class="lienAccueil" href="<?php echo $niveau ?>index.php">
        <p class="titre">TODO</p>
        <p class="slogan">Un gestionnaire de liste adapté à vous</p>
    </a>
    <a class="lienAjouterListe" href="<?php echo $niveau ?>liste/creer_modifier/index.php">Ajouter une liste</a>
    <ul>
        <?php
            for($intCptAffichageListes=0; $intCptAffichageListes<count($arrListes); $intCptAffichageListes++){
                $idListe = $arrListes[$intCptAffichageListes]['id'];
                $nomListe = $arrListes[$intCptAffichageListes]['nom'];
                echo "<li class='lienListe'><a href=".$niveau."liste/index.php?id_liste=$idListe'>$nomListe</a></li>";
            }
        ?>
    </ul>
</div>
<br><br><br>
