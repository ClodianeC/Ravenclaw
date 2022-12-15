<?php

ini_set('display_errors', 1);

$niveau = "";

include ($niveau . "inc/config.inc.php");

// ************************************************ CRÉATION TABLEAU POUR INFOS LISTES *********************************************************************

$strRequeteListe = 'SELECT t_liste.id_liste, nom_liste, hexadecimale, id_utilisateur, id_liste, t_liste.id_couleur
FROM t_liste INNER JOIN t_couleur ON t_liste.id_couleur = t_couleur.id_couleur
ORDER BY nom_liste';


$arrInfosListes = array();
$pdosResultat = $pdoConnexion -> prepare($strRequeteListe);
$pdosResultat -> execute();

for ($cpt = 0; $ligne = $pdosResultat -> fetch(); $cpt++){
    $arrInfosListes[$cpt]['id_liste'] = $ligne['id_liste'];
    $arrInfosListes[$cpt]['nom_liste'] = $ligne['nom_liste'];
    $arrInfosListes[$cpt]['hexadecimale'] = $ligne['hexadecimale'];
    $arrInfosListes[$cpt]['id_utilisateur'] = $ligne['id_utilisateur'];
    $arrInfosListes[$cpt]['id_liste'] = $ligne['id_liste'];
    $arrInfosListes[$cpt]['id_couleur'] = $ligne['id_couleur'];

    $strRequeteNbreItem = '    SELECT nom_item
        FROM t_item
        WHERE id_liste = ' . $ligne['id_liste'];

    $arrNbreItem = array();
    $pdosResultatNbreItem = $pdoConnexion -> prepare($strRequeteNbreItem);
    $pdosResultatNbreItem -> execute();

    for ($cpt2 = 0; $ligne = $pdosResultatNbreItem -> fetch(); $cpt2++){
        $arrNbreItem[$cpt2]['nom_item'] = $ligne['nom_item'];
    }

    $arrInfosListes[$cpt]['nombre_item'] = COUNT($arrNbreItem);
}

$pdosResultat -> closeCursor();

// ****************************** REQUETE POUR TABLEAU URGENT *****************************************************************************************************************************************

$strRequeteUrgent = 'SELECT t_item.nom_item, echeance, hexadecimale
FROM t_item
INNER JOIN t_liste ON t_item.id_liste = t_liste.id_liste
INNER JOIN t_couleur ON t_liste.id_couleur = t_liste.id_couleur
WHERE echeance IS NOT NULL AND est_complete = 0
ORDER BY echeance DESC
LIMIT 3';

$arrUrgent = array();
$pdosResultatUrgent = $pdoConnexion -> prepare($strRequeteUrgent);
$pdosResultatUrgent -> execute();

for ($cpt3 = 0; $ligne = $pdosResultatUrgent -> fetch(); $cpt3++) {
    $arrUrgent[$cpt3]['nom_item'] = $ligne['nom_item'];
    $arrUrgent[$cpt3]['echeance'] = $ligne['echeance'];
    $arrUrgent[$cpt3]['hexadecimale'] = $ligne['hexadecimale'];
}

$pdosResultatUrgent ->closeCursor();

// ******************************************************************************************************************************************************************************************************************

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles_benjamin.css">
    <script src="https://kit.fontawesome.com/ddf2613701.js" crossorigin="anonymous"></script>
    <title>Projet tofu</title>
</head>

<header class="header">
    <div class="div_header">
        <h1 class="h1_header">TODO</h1>
        <p class="list">list</p>
    </div>
    <div class="div_small">
        <small class="small">Un gestionnaire de liste adapté à vous</small>
    </div>
</header>

<body>

<!--<h1 class="h1">N'oublie pas le tofu !</h1>-->

<h2 class="h2 urgent">Qui arrivent bientôt à échéance :</h2>
<ul class="urgent_liste">
    <?php for ($cptUrgent = 0; $cptUrgent < count($arrUrgent); $cptUrgent++) { ?>
        <li class="li_urgent"> <?php echo $arrUrgent[$cptUrgent]['nom_item'] . " le " . $arrUrgent[$cptUrgent]['echeance'] . " " . $arrUrgent[$cptUrgent]['hexadecimale']; ?> </li>
    <?php } ?>
</ul>
<h2 class="h2 listes">Vos listes :</h2>
<?php
    if (isset($_GET["btn_supprimer_oui"]) == "Oui") { ?>
        <p class="h2">Nous avons supprimé la liste !</p>
    <?php } ?>
<form action="maj/index.php" method="get" class="form">
    <input type="text" name="id_liste" id="id_liste" value="id_liste" hidden>
    <input type="submit" value="Ajouter une liste" name="btn_nouveau" class="bouton btn_ajouter">
</form>
<ul class="ul">
    <?php for($cptNom = 0; $cptNom < count($arrInfosListes); $cptNom++){ ?>
            <div class="liste">
                <div class="nom_liste" style="background: #<?php echo $arrInfosListes[$cptNom]['hexadecimale']; ?>">
                    <h3 class="nom_liste_h3"><a class="nom_liste_lien" href="liste/index.php?id_liste=<?php echo $arrInfosListes[$cptNom]['id_liste']; ?>"><?php echo $arrInfosListes[$cptNom]['nom_liste']; ?></a></h3>
                </div>
                <form action="maj/index.php" method="get">
                    <li> <?php echo "Liste #" .  $arrInfosListes[$cptNom]['id_liste']; ?> </li>
                    <li> <?php echo "Code de couleur hexadécimale : " . $arrInfosListes[$cptNom]['hexadecimale']; ?> </li>
                    <li> <?php echo "Nombre d'items de la liste : " . $arrInfosListes[$cptNom]['nombre_item']; ?> </li>
                    <input type="text" name="id_liste" id="id_liste" value="<?php echo $arrInfosListes[$cptNom]['id_liste']; ?>" hidden>
                    <input type="text" name="nom_liste" id="nom_liste" value="<?php echo $arrInfosListes[$cptNom]['nom_liste']; ?>" hidden>
                    <input type="text" name="hexadecimale" id="hexadecimale" value="<?php echo $arrInfosListes[$cptNom]['hexadecimale']; ?>" hidden>
                    <input type="text" name="id_couleur" id="id_couleur" value="<?php echo $arrInfosListes[$cptNom]['id_couleur']; ?>" hidden>
                    <div class="boutons_form">
                        <input type="submit" value="Modifier" name="btn_modifier" class="bouton">
                        <input type="submit" value="Supprimer" name="btn_supprimer" class="bouton">
                    </div>
                </form>
            </div>

    <?php } ?>
</ul>


</body>
<script src="js/script_benjamin.js"></script>
</html>