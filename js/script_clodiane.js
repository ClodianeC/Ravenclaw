/* Projet TodoList */
/* @author : Clodiane Charette */

function hideShowEcheance(etat) {
    const inputJour = document.getElementById("jour");
    const inputMois = document.getElementById("mois");
    const inputAnnee = document.getElementById("annee");
    if(etat == 1){
        document.getElementById("echeanceOnOff").value = 0;
        document.getElementById("echeanceOnOff").classList.remove("echeanceOff");
        document.getElementById("echeanceOnOff").classList.add("echeanceOn");
        document.getElementById("echeanceOnOff").innerText= "Ajouter une échéance";
        inputJour.style.display = "none";
        inputMois.style.display = "none";
        inputAnnee.style.display = "none";
    }
    else{
        document.getElementById("echeanceOnOff").value = 1;
        document.getElementById("echeanceOnOff").classList.remove("echeanceOn");
        document.getElementById("echeanceOnOff").classList.add("echeanceOff");
        document.getElementById("echeanceOnOff").innerText= "Ne pas mettre d'échéance";
        inputJour.style.display = "inline-block";
        inputJour.value = 0;
        inputMois.style.display = "inline-block";
        inputMois.value = 0;
        inputAnnee.style.display = "inline-block";
        inputAnnee.value = 0;
    }
}


document.getElementById("echeanceOnOff").addEventListener("click", function(){
    const valeur = document.getElementById("echeanceOnOff").value
    hideShowEcheance(valeur);
});
