const open_popup_inscription = document.getElementById("open_popup_inscription");
const popup_inscription = document.getElementById("popup_inscription");
const close_popup_inscription = document.getElementById("close_popup_inscription");
const open_popup_connexion = document.getElementById("open_popup_connexion");
const popup_connexion = document.getElementById("popup_connexion");
const close_popup_connexion = document.getElementById("close_popup_connexion");

open_popup_inscription.onclick = function(){
    popup_inscription.style.display = "flex";
}

open_popup_connexion.onclick = function(){
    popup_connexion.style.display = "flex";
}

open_popup_connexion.onclick = function(){
    popup_connexion.style.display = "flex";
}

close_popup_inscription.onclick = function(){
    popup_inscription.style.display = "none";
}

close_popup_connexion.onclick = function(){
    popup_connexion.style.display = "none";
}