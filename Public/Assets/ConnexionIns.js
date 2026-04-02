//afficher une erreur
function afficherErreur(input, message) {
    let erreur = input.parentElement.querySelector(".erreur");

    if (!erreur) {
        erreur = document.createElement("span");
        erreur.classList.add("erreur");
        erreur.style.color = "red";
        erreur.style.fontSize = "0.9em";
        input.parentElement.appendChild(erreur);
    }

    erreur.textContent = message;
}

//supprimer erreur
function supprimerErreur(input) {
    const erreur = input.parentElement.querySelector(".erreur");
    if (erreur) {
        erreur.remove();
    }
}

//pour les champs vide
function champVide(input) {
    if (input.value.trim() === "") {
        afficherErreur(input, "Champ obligatoire");
        return false;
    }
    supprimerErreur(input);
    return true;
}

function urlValide(input) {
    try {
        new URL(input.value);
        return true;
    } catch {
        return false;
    }
}

function linkedinValide(input) {
    if (input.value.trim() === "") return true; //input.value.trim() permet d'enlever les espaces avant et après

    if (!urlValide(input)) {
        afficherErreur(input, "URL invalide");
        return false;
    }

    //avoir linkedin.com/in indique normalement que c'est l'url d'un profil utilisateur linkedin
    if (!input.value.includes("linkedin.com/in")) {
        afficherErreur(input, "Lien LinkedIn invalide");
        return false;
    }

    supprimerErreur(input);
    return true;
}


function emailValide(input) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(input.value)) {
        afficherErreur(input, "Email invalide");
        return false;
    }
    supprimerErreur(input);
    return true;
}

function motDePasseValide(input) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

    if (!regex.test(input.value)) {
        afficherErreur(input, "Mot de passe invalide (8 caractères, majuscule, minuscule, spécial)");
        return false;
    }
    supprimerErreur(input);
    return true;
}

function validerFormulaire(form) {
    let valide = true;

    const nom = form.querySelector("#nom");
    const prenom = form.querySelector("#prenom");
    const email = form.querySelector("#email");
    const password = form.querySelector("#current-password");
    const groupe = form.querySelector("#groupe");
    const codeEntreprise = form.querySelector("#codeEntreprise");
    const linkedin = form.querySelector("#linkedin");

    if (nom) {
        if (!champVide(nom)) valide = false;
    }

    if (prenom) {
        if (!champVide(prenom)) valide = false;
    }

    if (email) {
        if (!champVide(email)) valide = false;
        else if (!emailValide(email)) valide = false;
    }

    if (linkedin) {
        if (!linkedinValide(linkedin)) valide = false;
    }

    if (password) {
        if (!champVide(password)) valide = false;
        else if (!motDePasseValide(password)) valide = false;
    }

    if (groupe) {
        if (!champVide(groupe)) valide = false;
    }

    if (codeEntreprise) {
        if (!champVide(codeEntreprise)) valide = false;
    }

    return valide;
}

document.addEventListener("DOMContentLoaded", function () {

    const formulaires = document.querySelectorAll("form");

    formulaires.forEach(function (form) {

        form.addEventListener("submit", function (e) {

            const estValide = validerFormulaire(form);

            if (!estValide) {
                e.preventDefault();
            }
        });

    });

});