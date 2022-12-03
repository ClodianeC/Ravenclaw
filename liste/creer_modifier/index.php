<?php
$niveau = "../../";

include($niveau."inc/config.inc.php");

$arr_mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
$strCouleurItem="";

//Déterminer à quelle liste on appartient
if(isset($_GET["id_liste"])){
    $id_liste = $_GET["id_liste"];
}
else{
    $id_liste = 0;
}
$strRequeteListe="SELECT nom_liste, hexadecimale FROM t_liste INNER JOIN t_couleur ON t_liste.id_couleur=t_couleur.id_couleur WHERE id_liste=".$id_liste;
$pdosResultatListe = $pdoConnexion->prepare($strRequeteListe);
$pdosResultatListe->execute();
$rangee=$pdosResultatListe->fetch();
$arrListe["nom"]=$rangee["nom_liste"];
$arrListe["hex"]=$rangee["hexadecimale"];
$pdosResultatListe->closeCursor();

//Déterminer à quel item nous sommes
if(isset($_GET["id_item"])){
    $id_item = $_GET["id_item"];
}
else{
    $id_item = 0;
}
$strRequeteItem = "SELECT nom_item, hexadecimale, DAYOFMONTH(echeance) AS jour, MONTH(echeance) AS mois, YEAR(echeance) AS annee, est_complete FROM t_item INNER JOIN t_couleur ON t_item.id_couleur=t_couleur.id_couleur WHERE id_item=".$id_item;
$pdosResultatItem = $pdoConnexion->prepare($strRequeteItem);
$pdosResultatItem->execute();
$ligne=$pdosResultatItem->fetch();
$arrItem["nom"]=$ligne["nom_item"];
$arrItem["hex"]=$ligne["hexadecimale"];
$arrItem["jour"]=$ligne["jour"];
$arrItem["mois"]=$ligne["mois"];
$arrItem["annee"]=$ligne["annee"];
$arrItem["complete"]=$ligne["est_complete"];
$pdosResultatItem->closeCursor();

//Déterminer quelle est la couleur de l'item
switch ($arrItem["hex"]){
    case "FFFFFF":
        $strCouleurItem="blanc";
        break;
    case "C9C9C9":
        $strCouleurItem="gtpale";
        break;
    case "ABABAB":
        $strCouleurItem="gpale";
        break;
    case "777777":
        $strCouleurItem="gmoyen";
        break;
    case "3B3B3B":
        $strCouleurItem="gfonce";
        break;
    case "242424":
        $strCouleurItem="gtfonce";
        break;
}

//Déterminer quelle page appeler
$strCodeOperation="";
switch (true){
    case isset($_GET["add"]):
        $strCodeOperation="Ajouter";
        break;
    case isset($_GET["edit"]):
        $strCodeOperation="Modifier";
        break;
    case isset($_GET["delete"]):
        $strCodeOperation="Supprimer";
        break;
}

//faire le tableau des couleurs
$strRequeteItem = "SELECT id_couleur, hexadecimale, nom_couleur_fr FROM t_couleur";
$pdosResultatItem = $pdoConnexion->prepare($strRequeteItem);
$pdosResultatItem->execute();
for($intCptCouleur=0; $ligne=$pdosResultatItem->fetch(); $intCptCouleur++){
    $arrCouleur[$intCptCouleur]["id"]=$ligne["id_couleur"];
    $arrCouleur[$intCptCouleur]["hex"]=$ligne["hexadecimale"];
    $arrCouleur[$intCptCouleur]["nom"]=$ligne["nom_couleur_fr"];

    switch ($arrCouleur[$intCptCouleur]["hex"]){
        case "FFFFFF":
            $arrCouleur[$intCptCouleur]["surnom"]="blanc";
            break;
        case "C9C9C9":
            $arrCouleur[$intCptCouleur]["surnom"]="gtpale";
            break;
        case "ABABAB":
            $arrCouleur[$intCptCouleur]["surnom"]="gpale";
            break;
        case "777777":
            $arrCouleur[$intCptCouleur]["surnom"]="gmoyen";
            break;
        case "3B3B3B":
            $arrCouleur[$intCptCouleur]["surnom"]="gfonce";
            break;
        case "242424":
            $arrCouleur[$intCptCouleur]["surnom"]="gtfonce";
            break;
    }
}
$pdosResultatItem->closeCursor();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TodoList - <?php echo $strCodeOperation." ".$arrListe["nom"] ?></title>
    <?php include($niveau."inc/fragments/head.php"); ?>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/styles_clodiane.css">
</head>
<main>
    <?php include($niveau."inc/fragments/entete.php"); ?>
    <a href="../index.php?id_liste=<?php echo $id_liste ?>"><div class="icon" id="retourListe">Retour à la liste</div></a>
    <?php
    if($strCodeOperation=="Ajouter"){
        echo "<h1 class='h1 h1Liste blanc'>Nouvel item</h1>";
        echo "<p class='avertissement'>Les champs accompagnés d'un astérisque (*) sont obligatoires</p>";
    }
    elseif($strCodeOperation=="Modifier"){
        $nomItem = $arrItem["nom"];
        echo "<h1 class='h1 h1Liste $strCouleurItem'>Modifier l'item $nomItem</h1>";
        echo "<p class='avertissement'>Les champs accompagnés d'un astérisque (*) sont obligatoires</p>";
    }
    elseif($strCodeOperation=="Supprimer"){
        $nomItem = $arrItem["nom"];
        echo "<h1 class='h1 h1Liste $strCouleurItem'>Supprimer l'item $nomItem</h1>";
    }
    ?>
    <h2 class="h2 h2Liste">Pour la liste <?php echo $arrListe["nom"] ?></h2>
    <form action="./index.php" method="get" class="formulaireItem">
    <?php
    if($strCodeOperation!="Supprimer"){
        if($strCodeOperation=="Modifier"){
            $nom_item = $arrItem["nom"];
            $couleur_item = $strCouleurItem;
            $jour_item = $arrItem["jour"];
            $mois_item = $arrItem["mois"];
            $annee_item = $arrItem["annee"];
            $complete_item = $arrItem["complete"];
        }
        elseif($strCodeOperation=="Ajouter"){
            $nom_item = "";
            $couleur_item = "";
            $jour_item = "";
            $mois_item = "";
            $annee_item = "";
            $complete_item = "";
        }
    ?>
            <fieldset class="fieldset_couleur">
                <legend>Couleur de l'item</legend>
                <ul class="listeCouleur">
                    <li class="choix_couleur">
                        <input type="radio" name="couleur" id="aleatoire" value="aleatoire" class="radioCouleur screen-reader-only">
                        <label for="aleatoire" class="label"><div class="cercleCouleur aleatoire"></div><span>Aléatoire</span></label>
                    </li>
                    <?php
                    for($intCptAffichageCouleur=0; $intCptAffichageCouleur<count($arrCouleur); $intCptAffichageCouleur++){
                        $strCheckedCouleur = "";
                        if($strCouleurItem==$arrCouleur[$intCptAffichageCouleur]["surnom"]){
                            $strCheckedCouleur = "checked=true";
                        }
                        echo "<li class='choix_couleur'>";
                        echo "<input type='radio' name='id_couleur' id='".$arrCouleur[$intCptAffichageCouleur]["surnom"]."' class='radioCouleur screen-reader-only' value='".$arrCouleur[$intCptAffichageCouleur]["id"]."' $strCheckedCouleur>";
                        echo "<label for='".$arrCouleur[$intCptAffichageCouleur]["surnom"]."' class=label><div class='cercleCouleur ".$arrCouleur[$intCptAffichageCouleur]["surnom"]."'></div><span>".$arrCouleur[$intCptAffichageCouleur]["nom"]."</span></label>";
                    }
                    ?>
                </ul>
            </fieldset>

            <label for="nom" class="label">Nom de l'item</label>
            <input type="text" name="nom" id="nom" value="<?php echo $nom_item ?>">
            <fieldset name="date">
                <legend>Date due</legend>
                <select id="jour" name="jour">
                    <option value="0" selected></option>
                    <?php
                    for($intCptJour = 1; $intCptJour<=31; $intCptJour++){
                        $strSelectedJour = "";
                        if($jour_item==$intCptJour){
                            $strSelectedJour = "selected";
                        }
                        echo "<option value='$intCptJour' $strSelectedJour>$intCptJour</option>";
                    }
                    ?>
                </select>
                <select id="mois" name="mois">
                    <option value="0" selected></option>
                    <?php
                    for($intCptMois = 0; $intCptMois<count($arr_mois); $intCptMois++){
                        $strSelectedMois = "";
                        if($mois_item-1==$intCptMois){
                            $strSelectedMois = "selected";
                        }
                        echo "<option value='$intCptMois' $strSelectedMois>".$arr_mois[$intCptMois]."</option>";
                    }
                    ?>
                </select>
                <select id="annee" name="annee">
                    <option value="0" selected></option>
                    <?php
                    $intAnneeActu = date("Y");
                    $intAnneeMin = $intAnneeActu-15;
                    $intAnneeMax = $intAnneeActu+15;
                    for($intCptAnnee=$intAnneeMax; $intCptAnnee>=$intAnneeMin; $intCptAnnee--){
                        $strSelectedAnnee = "";
                        if($annee_item==$intCptAnnee){
                            $strSelectedAnnee = "selected";
                        }
                        echo "<option value='$intCptAnnee' $strSelectedAnnee>$intCptAnnee</option>";
                    }
                    ?>
                    <option value="01">2022</option>
                </select>

            </fieldset>
            <?php
            if($strCodeOperation=="Ajouter"){
                echo "<button type='submit' name='ajouter' id='ajouter' value='ajouter' class='bouton'>Ajouter l'item</button>";
            }
            elseif($strCodeOperation=="Modifier"){
                echo "<button type='submit' name='modifier' id='modifier' value='modifier' class='bouton'>Modifier l'item</button>";
            }
            ?>
    <?php
    }
    else{
    ?>
        <p class="avertissementSuppression">Êtes-vous certain de vouloir supprimer l'item <?php echo $arrItem["nom"] ?> de la liste?</p>
        <button type='submit' name='supprimer' id='supprimer' value='supprimer' class='bouton'>Supprimer l'item</button>
        <?php
    }
    ?>
    <a href="../index.php?id_liste=<?php echo $id_liste ?>" class="lienBouton">Annuler</a>
    </form>
</main>
<footer>
    <?php include($niveau."inc/fragments/footer.php"); ?>
</footer>
</html>
