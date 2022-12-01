<?php
$niveau = "../";

include($niveau."inc/config.inc.php");

$arr_mois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");



$nomDeListe = "Liste quelconque";
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TodoList - <?php echo $nomDeListe ?></title>
    <?php include($niveau."inc/fragments/head.php"); ?>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/styles_clodiane.css">
</head>
<main>
    <?php include($niveau."inc/fragments/entete.php"); ?>
    <a href="../index.php"><div class="icon" id="retourAccueil">Retour à l'accueil</div></a>
    <h1 class="h1 h1Liste"><?php echo $nomDeListe?></h1>
    <form action="creer_modifier/index.php" method="get">
        <button type="submit" name="add" id="add" value="add" class="bouton"></div>Ajouter un item</button>
    </form>
    <ul class="liste">
        <li class="itemListe">
            <h2 class="h2 h2Item">Nom de l'item</h2>
            <p>Date due: 12 nov 2022</p>
            <p>Charactéristique 01: Rond</p>
            <p>Charactéristique 02: Bleu</p>
            <p>Charactéristique 03: Karaté</p>
            <p>Actif</p>
            <p hidden>Complété</p>
            <input type="range" name="actif_complete" id="actif_complete" min="0" max="1">
            <form action="creer_modifier/index.php" method="get">
                <button type="submit" name="edit" id="edit" value="edit"><div class="icon"></div>Éditer</button>
                <button type="submit" name="delete" id="delete" value="delete"><div class="icon"></div>Supprimer</button>
            </form>
        </li>
    </ul>
</main>
<footer>
    <?php include($niveau."inc/fragments/footer.php"); ?>
</footer>
</html>
