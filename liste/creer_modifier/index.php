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
$strRequeteItem = "SELECT nom_item, hexadecimale FROM t_item INNER JOIN t_couleur ON t_item.id_couleur=t_couleur.id_couleur WHERE id_item=".$id_item;
$pdosResultatItem = $pdoConnexion->prepare($strRequeteItem);
$pdosResultatItem->execute();
$ligne=$pdosResultatItem->fetch();
$arrItem["nom"]=$ligne["nom_item"];
$arrItem["hex"]=$ligne["hexadecimale"];
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
        echo "<h1 class='h1 h1Liste $strCouleurItem'>Nouvel item</h1>";
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
    ?>

            <fieldset>
                <legend>Couleur de l'item</legend>
                <label for="aleatoire" class="label">Aléatoire</label>
                <input type="radio" name="couleur" id="aleatoire" value="aleatoire">
                <label for="blanc" class="label">Blanc</label>
                <input type="radio" name="couleur" id="blanc" value="blanc">
                <label for="tPale" class="label">Gris très pâle</label>
                <input type="radio" name="couleur" id="tPale" value="tPale">
                <label for="pale" class="label">Gris pâle</label>
                <input type="radio" name="couleur" id="pale" value="pale">
                <label for="moyen" class="label">Gris moyen</label>
                <input type="radio" name="couleur" id="moyen" value="moyen">
                <label for="fonce" class="label">Gris foncé</label>
                <input type="radio" name="couleur" id="fonce" value="fonce">
                <label for="tFonce" class="label">Gris très foncé</label>
                <input type="radio" name="couleur" id="tFonce" value="tFonce">
            </fieldset>

            <label for="nom" class="label">Nom de l'item</label>
            <input type="text" name="nom" id="nom" value="">
            <fieldset name="date">
                <legend>Date due</legend>
                <select id="jour" name="jour">
                    <option value="0" selected></option>
                    <?php
                    for($intCptJour = 1; $intCptJour<=31; $intCptJour++){
                        echo "<option value='$intCptJour'>".$intCptJour."</option>";
                    }
                    ?>
                </select>
                <select id="mois" name="mois">
                    <option value="0" selected></option>
                    <?php
                    for($intCptMois = 0; $intCptMois<count($arr_mois); $intCptMois++){
                        echo "<option value='$intCptMois'>".$arr_mois[$intCptMois]."</option>";
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
                        echo "<option value='$intCptAnnee'>$intCptAnnee</option>";
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
