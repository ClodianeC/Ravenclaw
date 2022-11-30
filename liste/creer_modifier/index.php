<?php
$niveau = "../../";

include($niveau."inc/config.inc.php");

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
    <h1 class="h1">Modifier un item</h1>
    <h1 class="h1">Nouvel item</h1>
    <h2 class="h2">Pour <?php echo $nomDeListe ?></h2>
    <p class="avertissement">Les champs accompagnés d'un astérisque (*) sont obligatoires</p>
    <form action="./index.php" method="get">
        <fieldset>
            <legend>Couleur de l'item</legend>
            <label for="aleatoire">Aléatoire</label>
            <input type="radio" name="couleur" id="aleatoire" value="aleatoire">
            <label for="blanc">Blanc</label>
            <input type="radio" name="couleur" id="blanc" value="blanc">
            <label for="tPale">Gris très pâle</label>
            <input type="radio" name="couleur" id="tPale" value="tPale">
            <label for="pale">Gris pâle</label>
            <input type="radio" name="couleur" id="pale" value="pale">
            <label for="moyen">Gris moyen</label>
            <input type="radio" name="couleur" id="moyen" value="moyen">
            <label for="fonce">Gris foncé</label>
            <input type="radio" name="couleur" id="fonce" value="fonce">
            <label for="tFonce">Gris très foncé</label>
            <input type="radio" name="couleur" id="tFonce" value="tFonce">
        </fieldset>

        <label for="nom">Nom de l'item</label>
        <input type="text" name="nom" id="nom" value="">
        <fieldset name="date">
            <legend>Date due</legend>
            <select id="jour" name="jour">
                <option value="0" selected></option>
                <option value="01">01</option>
            </select>
            <select id="mois" name="mois">
                <option value="0" selected></option>
                <option value="01">Janvier</option>
            </select>
            <select id="annee" name="annee">
                <option value="0" selected></option>
                <option value="01">2022</option>
            </select>

        </fieldset>
        <label for="char01">Charactéristique 01</label>
        <input type="text" name="char01" id="char01" value="">
        <label for="char02">Charactéristique 02</label>
        <input type="text" name="char02" id="char02" value="">
        <label for="char03">Charactéristique 03</label>
        <input type="text" name="char03" id="char03" value="">
        <button type="submit" name="modifier" id="modifier" value="modifier">Modifier l'item</button>
        <a href="../index.php">Annuler</a>
    </form>
</main>
<footer>
    <?php include($niveau."inc/fragments/footer.php"); ?>
</footer>
</html>
