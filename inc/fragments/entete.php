<?php
$nomUtilisateur = "Jean-Serge de Saint-François-du-Port";
$nomDeListe = "Liste quelconque";
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/styles_clodiane.css">
</head>
<div class="header">
    <p class="bienvenue">Bienvenue <?php echo $nomUtilisateur ?></p>
    <a href=""><div class="icon" id="deconnexion"></div>Déconnexion</a>
    <a href=""><div class="icon id="compte></div>Mon compte</a>
    <a class="lienAccueil" href="<?php echo $niveau ?>index.php">
        <p class="titre">TODO</p>
        <p class="slogan">Un gestionnaire de liste adapté à vous</p>
    </a>
    <a class="lienAjouterListe" href="<?php echo $niveau ?>liste/creer_modifier/index.php">Ajouter une liste</a>
    <ul>
        <li>
            <a href="<?php echo $niveau ?>liste/index.php">
                <?php echo $nomDeListe ?>
            </a>
        </li>
    </ul>
</div>
<br><br><br>
