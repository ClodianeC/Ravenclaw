<?php
$niveau = "../../";

include($niveau."inc/config.inc.php");

$strFichierTexte=file_get_contents($niveau."js/messages-erreur.json");
$jsonMessagesErreur=json_decode($strFichierTexte);

$arr_mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
$strCouleurItem="";
$strMessage="";
$strCodeErreur=0;

//Déterminer à quel item nous sommes
if(isset($_GET["id_item"])){
    $id_item = $_GET["id_item"];
}
else{
    $id_item = 0;
}

//Déterminer à quelle liste on appartient
if(isset($_GET["id_liste"])){
    $id_liste = $_GET["id_liste"];
}
else{
    $id_liste = 0;
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
    case isset($_GET["modifier"]):
        $strCodeOperation="Modification-complete";
        $strMessage="L'item numéro $id_item a été modifié avec succès!";
        break;
    case isset($_GET["ajouter"]):
        $strCodeOperation="Ajout-complet";
        $strMessage="L'item numéro $id_item a été créé avec succès!";
        break;
}

$strRequeteListe="SELECT nom_liste, hexadecimale FROM t_liste INNER JOIN t_couleur ON t_liste.id_couleur=t_couleur.id_couleur WHERE id_liste=".$id_liste;
$pdosResultatListe = $pdoConnexion->prepare($strRequeteListe);
$pdosResultatListe->execute();
$rangee=$pdosResultatListe->fetch();
$arrListe["nom"]=$rangee["nom_liste"];
$arrListe["hex"]=$rangee["hexadecimale"];
$pdosResultatListe->closeCursor();

if($strCodeOperation=="Modifier") {
    $strRequeteItem = "SELECT nom_item, hexadecimale, DAYOFMONTH(echeance) AS jour, MONTH(echeance) AS mois, YEAR(echeance) AS annee, est_complete FROM t_item INNER JOIN t_couleur ON t_item.id_couleur=t_couleur.id_couleur WHERE id_item=" . $id_item;
    $pdosResultatItem = $pdoConnexion->prepare($strRequeteItem);
    $pdosResultatItem->execute();
    $ligne = $pdosResultatItem->fetch();
    $arrItem["nom"] = $ligne["nom_item"];
    $arrItem["hex"] = $ligne["hexadecimale"];
    $arrItem["jour"] = $ligne["jour"];
    $arrItem["mois"] = $ligne["mois"];
    $arrItem["annee"] = $ligne["annee"];
    $arrItem["complete"] = $ligne["est_complete"];
    $pdosResultatItem->closeCursor();

//Déterminer quelle est la couleur de l'item
    switch ($arrItem["hex"]) {
        case "FFFFFF":
            $strCouleurItem = "blanc";
            break;
        case "C9C9C9":
            $strCouleurItem = "gtpale";
            break;
        case "ABABAB":
            $strCouleurItem = "gpale";
            break;
        case "777777":
            $strCouleurItem = "gmoyen";
            break;
        case "3B3B3B":
            $strCouleurItem = "gfonce";
            break;
        case "242424":
            $strCouleurItem = "gtfonce";
            break;
    }
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

if($strCodeOperation=="Modification-complete" || $strCodeOperation=="Ajout-complet"){
    $arrItemFinal["id"]=$_GET["id_item"];
    $arrItemFinal["liste"]=$_GET["id_liste"];
    $arrItemFinal["nom"]=$_GET["nom"];
    $arrItemFinal["couleur"]=$_GET["id_couleur"];
    $arrItemFinal["est_complete"]=$_GET["est-complete"];
    if($_GET["mois"]<10){
        $strMois = "0".$_GET["mois"];
    }
    else{
        $strMois=$_GET["mois"];
    }
    if($_GET["jour"]<10){
        $strJour = "0".$_GET["jour"];
    }
    else{
        $strJour=$_GET["jour"];
    }
    if($_GET["jour"]==0 || $_GET["mois"]==0 || $_GET["annee"]==0){
        $arrItemFinal["echeance"]="";
    }
    //Validations champs
    elseif(checkdate($_GET["mois"], $_GET["jour"], $_GET["annee"])){
        $arrItemFinal["echeance"]=$_GET["annee"]."-".$strMois."-".$strJour." 00:00:00";
    }
    else{
        $arrItemFinal["echeance"]="";
        $strCodeErreur = -1;
    }
    if($arrItemFinal["nom"]=="" || strlen($arrItemFinal["nom"])>50){
        $strCodeErreur = -1;
    }
    if($strCodeOperation=="Modification-complete"){
        if($arrItemFinal["id"]==0){
            $strCodeErreur = -1;
        }
    }
    if($arrItemFinal["est_complete"]=="empty"){
        $strCodeErreur = -1;
    }
    //Pour la couleur aléatoire
    if($arrItemFinal["couleur"]=="aleatoire"){
        $intNoCouleur=rand(0, count($arrCouleur)-1);
        $arrItemFinal["couleur"]=$arrCouleur[$intNoCouleur]["id"];
    }
    //Requête pour modification et ajout
    if($strCodeErreur==0){
        if($strCodeOperation=="Modification-complete"){
            if($arrItemFinal["echeance"]!=""){
                $strRequeteAjoutModif="UPDATE t_item SET nom_item='".$arrItemFinal["nom"]."', id_couleur='".$arrItemFinal["couleur"]."',echeance='".$arrItemFinal["echeance"]."', est_complete='".$arrItemFinal["est_complete"]."', id_liste='".$arrItemFinal["liste"]."' WHERE id_item=".$arrItemFinal["id"];
            }
            else{
                $strRequeteAjoutModif="UPDATE t_item SET nom_item='".$arrItemFinal["nom"]."', id_couleur='".$arrItemFinal["couleur"]."', est_complete='".$arrItemFinal["est_complete"]."', id_liste='".$arrItemFinal["liste"]."' WHERE id_item=".$arrItemFinal["id"];
            }
        }
        else{
            if($arrItemFinal["echeance"]!=""){
                $strRequeteAjoutModif="INSERT INTO t_item (nom_item, id_couleur, echeance, est_complete, id_liste) VALUES ('".$arrItemFinal["nom"]."', ".$arrItemFinal["couleur"].", '".$arrItemFinal["echeance"]."', ".$arrItemFinal["est_complete"].", ".$arrItemFinal["liste"].")";
            }
            else{
                $strRequeteAjoutModif="INSERT INTO t_item (nom_item, id_couleur, est_complete, id_liste) VALUES ('".$arrItemFinal["nom"]."', ".$arrItemFinal["couleur"].", ".$arrItemFinal["est_complete"].", ".$arrItemFinal["liste"].")";
            }
        }
        $pdoConnexionAjoutModif = new PDO($strDsn, $strUser, $strPassword);
        $pdoConnexionAjoutModif->exec("SET CHARACTER SET utf8");
        $pdoConnexionAjoutModif->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $pdosResultatAjoutModif = $pdoConnexionAjoutModif->query($strRequeteAjoutModif);
        $pdoConnexionAjoutModif = $pdoConnexionAjoutModif->errorCode();
    }
}
?>

<!DOCTYPE html>
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
<body>
<main>
    <?php include($niveau."inc/fragments/entete.php"); ?>
    <a href="../index.php?id_liste=<?php echo $id_liste ?>" id="retourListe" class="retour"><div class="icon"></div><p>Retour à la liste</p></a>
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
    else{
        $nomItem = $arrItem["nom"];
        echo "<h1 class='h1 h1Liste $strCouleurItem'>Item: $nomItem</h1>";
    }
    ?>
    <h2 class="h2 h2Liste">Pour la liste <?php echo $arrListe["nom"] ?></h2>
    <form action="./index.php" method="get" class="formulaireItem">
    <?php
    if($strCodeOperation=="Ajouter" || $strCodeOperation=="Modifier"){
        echo "<input type='text' name='id_liste' id='id_liste' class='id_liste' value='$id_liste' hidden>";
        echo "<input type='text' name='id_item' id='id_item' class='id_item' value='$id_item' hidden>";
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
                    <legend>Couleur de l'item*</legend>
                    <ul class="listeCouleur">
                        <li class="choix_couleur">
                            <?php
                            $strCheckedCouleur = "";
                            if($strCodeOperation=="Ajouter"){
                                $strCheckedCouleur="checked=true";
                            }
                            ?>
                            <input type="radio" name="id_couleur" id="aleatoire" value="aleatoire" class="radioCouleur screen-reader-only"<?php echo $strCheckedCouleur ?> required>
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
                <div class="conteneurForm">
                    <div class="conteneurNom">
                        <label for="nom" class="label">Nom de l'item*</label>
                        <input type="text" name="nom" class="nomForm" id="nom" value="<?php echo $nom_item ?>" required pattern="[A-ZÇÀ-Ÿ][a-zA-ZÀ-ÿ '\-]{1,29}">
                    </div>
                    <div class="conteneurCompletion">
                        <?php
                        echo "<label for='est-complete' class='label'>Statut de l'item*</label>";
                        echo "<select name='est-complete' id='est-complete' class='completionForm' required>";
                        echo "<option value='empty' class='completion-option'></option>";
                        if($arrItem["complete"]==0){
                            $strSelectedF ="selected";
                            $strSelectedT ="";
                        }
                        else{
                            $strSelectedT ="selected";
                            $strSelectedF ="";
                        }
                        echo "<option value='0' $strSelectedF>Non complétée</option>";
                        echo "<option value='1' $strSelectedT>Complétée</option>";
                        ?>
                        </select>
                    </div>
                    <fieldset class="fieldsetDate">
                        <legend>Date d'échéance</legend>
                        <div>
                            <select id="jour" name="jour" class="jourForm">
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
                            <select id="mois" name="mois" class="moisForm">
                                <option value="0" selected></option>
                                <?php
                                for($intCptMois = 1; $intCptMois<=count($arr_mois); $intCptMois++){
                                    $strSelectedMois = "";
                                    if($mois_item==$intCptMois){
                                        $strSelectedMois = "selected";
                                    }
                                    echo "<option value='$intCptMois' $strSelectedMois>".$arr_mois[$intCptMois-1]."</option>";
                                }
                                ?>
                            </select>
                            <select id="annee" name="annee" class="anneeForm">
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
                        </div>
                        <button type="button"  name='echeanceOnOff' id='echeanceOnOff' value=0 class="bouton echeanceOn boutonEcheance">Ajouter une échéance</button>
                    </fieldset>
                </div>
                <div class="boutonsForm">
                    <?php
                    if($strCodeOperation=="Ajouter"){
                        echo "<button type='submit' name='ajouter' id='ajouter' value='ajouter' class='bouton buttonAjouter'>Ajouter l'item</button>";
                    }
                    elseif($strCodeOperation=="Modifier"){
                        echo "<button type='submit' name='modifier' id='modifier' value='modifier' class='bouton buttonModifier'>Modifier l'item</button>";
                    }
                    echo "<a href='../index.php?id_liste=$id_liste' class='lienBouton buttonAnnuler'>Annuler</a>";
                    }
                    else{
                        echo "<p>$strMessage</p>";
                        echo "<a href='../index.php?id_liste=$id_liste' class='lienBouton buttonRetour'>Retourner à la liste</a>";
                    }
                    ?>
                </div>
    </form>
</main>
<?php include($niveau."inc/fragments/footer.php"); ?>
<script src="<?php echo $niveau ?>js/script_clodiane.js"></script>
</body>
</html>
