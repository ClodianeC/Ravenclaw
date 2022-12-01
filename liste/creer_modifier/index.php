<?php
$niveau = "../../";

include($niveau."inc/config.inc.php");

$arr_mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

$action = "Ajouter";
$nomDeListe = "Liste quelconque"
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TodoList - <?php echo $action." ".$nomDeListe ?></title>
    <?php include($niveau."inc/fragments/head.php"); ?>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/styles_clodiane.css">
</head>
<main>
    <?php include($niveau."inc/fragments/entete.php"); ?>
    <a href="../index.php"><div class="icon" id="retourListe">Retour à la liste</div></a>
    <h1 class="h1 h1Liste">Modifier un item</h1>
    <h1 class="h1 h1Liste">Nouvel item</h1>
    <h2 class="h2 h2Liste">Pour <?php echo $nomDeListe ?></h2>
    <p class="avertissement">Les champs accompagnés d'un astérisque (*) sont obligatoires</p>
    <form action="./index.php" method="get" class="formulaireItem">
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
        <label for="char01" class="label">Charactéristique 01</label>
        <input type="text" name="char01" id="char01" value="">
        <label for="char02" class="label">Charactéristique 02</label>
        <input type="text" name="char02" id="char02" value="">
        <label for="char03" class="label">Charactéristique 03</label>
        <input type="text" name="char03" id="char03" value="">
        <button type="submit" name="modifier" id="modifier" value="modifier" class="bouton">Modifier l'item</button>
        <a href="../index.php" class="lienBouton">Annuler</a>
    </form>
</main>
<footer>
    <?php include($niveau."inc/fragments/footer.php"); ?>
</footer>
</html>
