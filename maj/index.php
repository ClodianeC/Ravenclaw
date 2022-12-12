<?php

ini_set('display_errors', 1);

$niveau = "../";

include ($niveau . "inc/config.inc.php");


// *********************** CODE OPERATION *******************************************************************************************************************************************************************************************************************************************

$strCodeOperation = "";

switch (true) {
    case isset($_GET['btn_modifier']):
        // code operation de modifier
        $strCodeOperation = "afficher";
        break;
    case isset($_GET['btn_nouveau']):
        // code operation ajouter
        $strCodeOperation = "nouveau";
        break;
    case isset($_GET["btn_ajouter"]):
        $strCodeOperation = "ajouter";
        break;
    case isset($_GET['btn_supprimer']):
        // code opération supprimer
        $strCodeOperation = "supprimer";
        break;
        case isset($_GET['btn_supprimer_oui']):
        // code opération supprimer
        $strCodeOperation = "supprimer_oui";
        break;
    case isset($_GET['btn_enregistrer']):
        $strCodeOperation = "modifier";
        break;
    default :
        // code operation affichage simple
        $strCodeOperation = "afficher";
        break;
}

$arrUpdate = array();

if ($strCodeOperation == "modifier" || $strCodeOperation == "ajouter") {
    $arrUpdate['nom_liste'] = $_GET['nom_liste'];
    $arrUpdate['id_couleur'] = $_GET['id_couleur'];
}
// ******************* TABLEAU DES COULEURS DE T_LISTE ***********************************************************************************************************************************************************************************************************************************************

$strRequeteCouleur = 'SELECT *, nom_liste
FROM t_liste
INNER JOIN t_couleur ON t_liste.id_couleur = t_couleur.id_couleur';

$arrCouleur = array();

$pdosResultatCouleur = $pdoConnexion -> prepare($strRequeteCouleur);
$pdosResultatCouleur -> execute();

for ($cpt = 0; $ligne = $pdosResultatCouleur -> fetch(); $cpt++) {
    $arrCouleur[$cpt]['id_liste'] = $ligne['id_liste'];
    $arrCouleur[$cpt]['nom_liste'] = $ligne['nom_liste'];
    $arrCouleur[$cpt]['id_couleur'] = $ligne['id_couleur'];
    $arrCouleur[$cpt]['id_utilisateur'] = $ligne['id_utilisateur'];
    $arrCouleur[$cpt]['id_couleur'] = $ligne['id_couleur'];
    $arrCouleur[$cpt]['nom_couleur_fr'] = $ligne['nom_couleur_fr'];
    $arrCouleur[$cpt]['nom_couleur_en'] = $ligne['nom_couleur_en'];
    $arrCouleur[$cpt]['hexadecimale'] = $ligne['hexadecimale'];
    $arrCouleur[$cpt]['rgb'] = $ligne['rgb'];
    $arrCouleur[$cpt]['nom_liste'] = $ligne['nom_liste'];
}

$pdosResultatCouleur ->closeCursor();

// ************ TABLEAU DES COULEURS DE T_COULEUR ************************************************************************************************************************************************

$strRequeteCouleur2 = 'SELECT id_couleur, nom_couleur_fr, hexadecimale
FROM t_couleur';

$arrCouleur2 = array();

$pdosResultatCouleur2 = $pdoConnexion -> prepare($strRequeteCouleur2);
$pdosResultatCouleur2 -> execute();

for ($cpt2 = 0; $ligne = $pdosResultatCouleur2 -> fetch(); $cpt2++){
    $arrCouleur2[$cpt2]['id_couleur'] = $ligne['id_couleur'];
    $arrCouleur2[$cpt2]['nom_couleur_fr'] = $ligne['nom_couleur_fr'];
    $arrCouleur2[$cpt2]['hexadecimale'] = $ligne['hexadecimale'];
}

$pdosResultatCouleur2 ->closeCursor();

// ********* REQUETE SI $strCodeOperation = "modifier"; *********************************************************************************************************************************************************************************************************************************************************

if (isset($_GET['id_liste'])){
    $id_liste = $_GET["id_liste"];
} else {
    $id_liste = 0;
}

if ($strCodeOperation == "modifier") {
    $strRequeteUpdate = "UPDATE t_liste SET " .
        "nom_liste='" . $arrUpdate['nom_liste'] . "'," .
        "id_couleur='" . $arrUpdate['id_couleur'] . "' " .
        " WHERE id_liste=" . $id_liste;

    $pdoConnexionAjoutModif = new PDO($strDsn, $strUser, $strPassword);
    $pdoConnexionAjoutModif->exec("SET CHARACTER SET utf8");
    $pdoConnexionAjoutModif->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    $pdosResultatAjoutModif = $pdoConnexionAjoutModif->query($strRequeteUpdate);
    $pdoConnexionAjoutModif = $pdoConnexionAjoutModif->errorCode();

} else if ($strCodeOperation == "ajouter") {
    $strRequeteAjouter = "INSERT INTO t_liste ".
        "(nom_liste, id_couleur, id_utilisateur)".
        " VALUES ('".
        $arrUpdate["nom_liste"]."',".
        $arrUpdate["id_couleur"].",1)";

    $pdoConnexionAjoutModif = new PDO($strDsn, $strUser, $strPassword);
    $pdoConnexionAjoutModif->exec("SET CHARACTER SET utf8");
    $pdoConnexionAjoutModif->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    $pdosResultatAjoutModif = $pdoConnexionAjoutModif->query($strRequeteAjouter);
    $pdoConnexionAjoutModif = $pdoConnexionAjoutModif->errorCode();

} else if ($strCodeOperation == "supprimer_oui") {
    $strRequeteSupprimer = "DELETE FROM t_liste WHERE id_liste =" . $id_liste;

    $pdoConnexionAjoutModif = new PDO($strDsn, $strUser, $strPassword);
    $pdoConnexionAjoutModif->exec("SET CHARACTER SET utf8");
    $pdoConnexionAjoutModif->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    $pdosResultatAjoutModif = $pdoConnexionAjoutModif->query($strRequeteSupprimer);
    $pdoConnexionAjoutModif = $pdoConnexionAjoutModif->errorCode();

    header('Location: ' . $niveau . 'index.php?btn_supprimer_oui=Oui');
}



// ******************************************************************************************************************************************************************************************************************************************************************
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet tofu</title>
</head>

<body>

    <?php if ($strCodeOperation == "afficher") { ?>
        <h1> <?php echo $_GET['nom_liste']; ?> </h1>
    <?php } else if ($strCodeOperation == "nouveau") { ?>
        <h1>Créer une liste</h1>
    <?php } ?>

    <?php
        if ($strCodeOperation == "afficher") { ?>
            <form action="<?php echo "index.php" ?>">
                <input type="text" name="id_liste" id="id_liste" value="<?php echo $_GET['id_liste']; ?>" hidden>
                <label for="nom_liste">Nom de la liste : </label>
                <input type="text" name="nom_liste" id="nom_liste" value="<?php echo $_GET['nom_liste'] ?>">
                <br>
                <br>
                <label for="hexadecimale">Couleur de la liste : </label>
                <input type="text" name="hexadecimale" id="hexadecimale" value="<?php echo $_GET['hexadecimale'] ?>">
                <br>
                <br>
                <?php for ($cptCouleur = 0; $cptCouleur < count($arrCouleur2); $cptCouleur++){
                    $strChecked = "";
                    if ($_GET['hexadecimale'] == $arrCouleur2[$cptCouleur]['hexadecimale']) {
                        $strChecked = "checked = 'checked'";
                    } ?>
                <label for="id_couleur"><?php echo $arrCouleur2[$cptCouleur]['nom_couleur_fr'];?></label>
                <input type="radio" name="id_couleur" id="<?php echo $arrCouleur2[$cptCouleur]['hexadecimale'] ?>" <?php echo $strChecked;?> value="<?php echo $arrCouleur2[$cptCouleur]['id_couleur']; ?>">
                <?php } ?>
                <br>
                <br>
                <input type="submit" value="Enregistrer" name="btn_enregistrer">
                <br>
                <a href="<?php echo $niveau . "index.php" ?>">Annuler</a>

        <?php } else if ($strCodeOperation == "nouveau") { ?>
            <form action="<?php echo "index.php" ?>" method="get">
            <label for="nom_liste">Nom de la liste : </label>
            <input type="text" name="nom_liste" id="nom_liste">
            <br>
            <br>
            <?php for ($cptCouleur = 0; $cptCouleur < count($arrCouleur2); $cptCouleur++){ ?>
                <label for="id_couleur"><?php echo $arrCouleur2[$cptCouleur]['nom_couleur_fr'];?></label>
                <input type="radio" name="id_couleur" id="<?php echo $arrCouleur2[$cptCouleur]['hexadecimale'] ?>" value="<?php echo $arrCouleur2[$cptCouleur]['id_couleur']; ?>">
            <?php } ?>
            <br>
            <br>
                <input type="submit" value="Ajouter" name="btn_ajouter">
            </form>
            <a href="<?php echo $niveau . "index.php" ?>">Annuler</a>

        <?php } else if ($strCodeOperation == "supprimer") { ?>
            <form action="<?php echo "index.php" ?>" method="get">
                <p>Êtes-vous sûr de vouloir supprimer la liste <strong><?php echo $_GET['nom_liste']; ?></strong> et tout ce qu'elle contient ?</p>
                <input type="text" value="<?php echo $id_liste ?>" name="id_liste" hidden>
                <input type="submit" value="Oui" name="btn_supprimer_oui">
            </form>
            <a href="../index.php">Annuler</a>
        <?php } else { ?>
            <form action="<?php echo $niveau . "index.php" ?>">
                <p>Nous avons enregistré vos modifications !</p>
            <input type="submit" value="Retour à l'accueil" name="btn_accueil">
            </form>
        <?php } ?>
    </form>

</body>

</html>
