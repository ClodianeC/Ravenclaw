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
$pdosResultatListes->closeCursor();
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/styles_clodiane.css">
</head>
<div class="header">
    <div class="topEntete" id="topEntete">
        <p class="bienvenue">Bienvenue <?php echo $nomUtilisateur ?></p>
        <form class="recherche" action="<?php echo $niveau ?>index.php" method="get">
            <input type="text" name="search" id="search" class="inputRecherche">
            <button type="submit" class="buttonSearch boutonIcone" id="buttonSearch"><div class="icon"></div><p>Rechercher</p></button>
        </form>
        <div class="boutonsEntete">
            <a href="" class="icon_header boutonIcone" id="compte"><div class="icon" ></div><p>Mon compte</p></a>
            <a href="" class="icon_header boutonIcone" id="deconnexion"><div class="icon"></div><p>Déconnexion</p></a>
        </div>
    </div>
    <a class="hamburger boutonIcone" id="hamburger">
        <div class="icon"></div><p>Menu</p>
    </a>
    <div class="logoAccueil">
        <a class="lienAccueil" href="<?php echo $niveau ?>index.php">
            <p class="titre01">TODO</p>
            <p class="titre02">List</p>
            <p class="slogan">Un gestionnaire de liste adapté à vous</p>
        </a>
    </div>
    <div class="listesEntete">
        <p class="titreEnteteListe">Vos listes</p>
        <a class="lienAjouterListe lienBouton" href="<?php echo $niveau ?>maj/index.php?ajouter=ajouter">Ajouter une liste</a>
        <ul class="listeLiensEntete">
            <?php
            for($intCptAffichageListes=0; $intCptAffichageListes<count($arrListes); $intCptAffichageListes++){
                $idListe = $arrListes[$intCptAffichageListes]['id'];
                $nomListe = $arrListes[$intCptAffichageListes]['nom'];
                echo "<li class='itemListeEntete'><a class='lienListeEntete' href='".$niveau."liste/index.php?id_liste=$idListe'>$nomListe</a></li>";
            }
            ?>
        </ul>
    </div>
</div>
