<?php
$niveau = "../";

include($niveau."inc/config.inc.php");

$strFichierTexte=file_get_contents($niveau."js/messages-erreur.json");
$jsonMessagesErreur=json_decode($strFichierTexte);

$arr_mois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
$strCouleurListe="";


if(isset($_GET["id_liste"])){
    $idListeActu = $_GET["id_liste"];
}
else{
    $idListeActu = 0;
}
if(isset($_GET["delete"])){
    $strCodeOperation = "supprimer";
    $idDelete = $_GET["id_item"];
    $strMessage = "L'item numéro $idDelete a été supprimé avec succès.";
}
else{
    $strCodeOperation = "afficher";
    $strMessage = "";
}

if($strCodeOperation=="supprimer"){
    $strRequeteDelete = "DELETE FROM t_item WHERE id_item=".$idDelete;
    $pdoConnexionDelete=new PDO($strDsn, $strUser, $strPassword);
    $pdoConnexionDelete->exec("SET CHARACTER SET utf8");
    $pdoConnexionDelete->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    $pdosResultatDelete = $pdoConnexionDelete->prepare($strRequeteDelete);
    $pdosResultatDelete->execute();
    $pdoConnexionDelete=$pdoConnexionDelete->errorCode();
}

$strRequeteListe="SELECT id_liste, nom_liste, hexadecimale FROM t_liste INNER JOIN t_couleur ON t_liste.id_couleur=t_couleur.id_couleur WHERE id_liste=".$idListeActu;
$pdosResultatListe = $pdoConnexion->prepare($strRequeteListe);
$pdosResultatListe->execute();
$rangee=$pdosResultatListe->fetch();
$arrListe["id"]=$rangee["id_liste"];
$arrListe["nom"]=$rangee["nom_liste"];
$arrListe["hex"]=$rangee["hexadecimale"];
$pdosResultatListe->closeCursor();

//Déterminer quelle est la couleur de la liste
switch ($arrListe["hex"]){
    case "FFFFFF":
        $strCouleurListe="blanc";
        break;
    case "C9C9C9":
        $strCouleurListe="gtpale";
        break;
    case "ABABAB":
        $strCouleurListe="gpale";
        break;
    case "777777":
        $strCouleurListe="gmoyen";
        break;
    case "3B3B3B":
        $strCouleurListe="gfonce";
        break;
    case "242424":
        $strCouleurListe="gtfonce";
        break;
}

$strRequeteItems = "SELECT id_item, nom_item, hexadecimale, echeance, DAYOFMONTH(echeance) AS jour, MONTH(echeance) AS mois, YEAR(echeance) AS annee, est_complete FROM t_item INNER JOIN t_couleur ON t_item.id_couleur=t_couleur.id_couleur WHERE id_liste=".$idListeActu." ORDER BY echeance DESC, nom_item";
$pdosResultatItems = $pdoConnexion->prepare($strRequeteItems);
$pdosResultatItems->execute();
for($intCptItems=0; $rangee=$pdosResultatItems->fetch(); $intCptItems++){
    $arrItems[$intCptItems]["id"]=$rangee["id_item"];
    $arrItems[$intCptItems]["nom"]=$rangee["nom_item"];
    $arrItems[$intCptItems]["hex"]=$rangee["hexadecimale"];
    $arrItems[$intCptItems]["echeance"]=$rangee["echeance"];
    $arrItems[$intCptItems]["jour"]=$rangee["jour"];
    $arrItems[$intCptItems]["mois"]=$rangee["mois"];
    $arrItems[$intCptItems]["annee"]=$rangee["annee"];
    $arrItems[$intCptItems]["completee"]=$rangee["est_complete"];

    switch ($arrItems[$intCptItems]["hex"]){
        case "FFFFFF":
            $arrItems[$intCptItems]["couleur"]="blanc";
            break;
        case "C9C9C9":
            $arrItems[$intCptItems]["couleur"]="gtpale";
            break;
        case "ABABAB":
            $arrItems[$intCptItems]["couleur"]="gpale";
            break;
        case "777777":
            $arrItems[$intCptItems]["couleur"]="gmoyen";
            break;
        case "3B3B3B":
            $arrItems[$intCptItems]["couleur"]="gfonce";
            break;
        case "242424":
            $arrItems[$intCptItems]["couleur"]="gtfonce";
            break;
        default:
            $arrItems[$intCptItems]["couleur"]="";
    }
}
$pdosResultatItems->closeCursor();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TodoList - Liste <?php echo $arrListe["nom"] ?></title>
    <?php include($niveau."inc/fragments/head.php"); ?>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/styles_clodiane.css">
</head>
<main>
    <?php include($niveau."inc/fragments/entete.php"); ?>
    <a href="../index.php" id="retourAccueil" class="retour"><div class="icon"></div><p>Retour à l'accueil</p></a>
    <h1 class="h1 h1Liste <?php echo $strCouleurListe ?>"><?php echo $arrListe["nom"]?></h1>
    <p class="message"><?php echo $strMessage ?></p>
    <form action="creer_modifier/index.php" method="get">
        <?php echo "<input type='text' hidden name='id_liste' id='id_liste' value='$idListeActu'>" ?>
        <button type="submit" name="add" id="add" value="add" class="bouton"></div>Ajouter un item</button>
    </form>
    <ul class="liste">
        <?php
        for($intCptAffichageItems = 0; $intCptAffichageItems<count($arrItems); $intCptAffichageItems++){
            if($intCptAffichageItems%2==0){
                $strPairImpair = "pair";
            }
            else{
                $strPairImpair = "impair";
            }
            $id_mois = $arrItems[$intCptAffichageItems]["mois"]-1;
            $id_item = $arrItems[$intCptAffichageItems]["id"];
            $strCouleur = $arrItems[$intCptAffichageItems]["couleur"];
            echo "<li class='itemListe $strPairImpair'>";
            echo "<h2 class='h2 h2Item $strCouleur'>".$arrItems[$intCptAffichageItems]["nom"]."</h2>";
            if($arrItems[$intCptAffichageItems]["echeance"]!=""){
                echo "<p class='date'>Date due: ".$arrItems[$intCptAffichageItems]["jour"]." ".$arr_mois[$id_mois]." ".$arrItems[$intCptAffichageItems]["annee"]."</p>";
            }
            else{
                echo "<p class='date'>Date due: Non-déterminée";
            }
            if($arrItems[$intCptAffichageItems]["completee"]==0){
                echo "<p class='completion'>Complétion: Non-complété";
            }
            else{
                echo "<p class='completion'>Complétion: Complété";
            }
            echo "<div class='boutonsItems'>";
            echo "<form action='creer_modifier/index.php' method='get'>
                  <input type='text' hidden name='id_liste' id='id_liste' value='$idListeActu'>
                  <input type='text' hidden name='id_item' id='id_item' value='$id_item'>
                  <button type='submit' name='edit' value='edit' class='buttonEdit'><div class='icon'></div><p>Éditer</p></button>
                  </form>";
            echo "<form action='index.php' method='get'>
                     <input type='text' hidden name='id_liste' id='id_liste' value='$idListeActu'>
                     <input type='text' hidden name='id_item' id='id_item' value='$id_item'>
                     <button type='submit' name='delete' value='delete' class='buttonDelete'><div class='icon'></div><p>Supprimer</p></button>
                  </form>";
            echo "</div>";
            echo "</li>";
        }
        ?>
    </ul>
</main>
<footer>
    <?php include($niveau."inc/fragments/footer.php"); ?>
</footer>
</html>
