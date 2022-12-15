<?php

$strRequeteNomListes = 'SELECT t_liste.id_liste, nom_liste, hexadecimale, id_utilisateur
FROM t_liste INNER JOIN t_couleur ON t_liste.id_couleur = t_couleur.id_couleur
ORDER BY nom_liste';

$arrInfosListes = array();
$pdosResultat = $pdoConnexion -> prepare($strRequeteNomListes);
$pdosResultat -> execute();

for ($cpt = 0; $ligne = $pdosResultat -> fetch(); $cpt++){
    $arrInfosListes[$cpt]['id_liste'] = $ligne['id_liste'];
    $arrInfosListes[$cpt]['nom_liste'] = $ligne['nom_liste'];
    $arrInfosListes[$cpt]['hexadecimale'] = $ligne['hexadecimale'];
    $arrInfosListes[$cpt]['id_utilisateur'] = $ligne['id_utilisateur'];
}







?>