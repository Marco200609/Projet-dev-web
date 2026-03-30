function setupAutocomplete(idEntre, idDatalist, url, paramGetNom) {
    const input = document.getElementById(idEntre);
    if (!input) {
        return;
    }

    input.addEventListener("input", function(event) {
        const datalist = document.getElementById(idDatalist);
        datalist.innerHTML = "";
        if (input.value.length >= 3) {
            fetch(`${url}?${paramGetNom}=${encodeURIComponent(input.value)}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(item => {
                        const option = document.createElement("option");
                        option.value = item;
                        datalist.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Erreur lors de la récupération :", error);
                });
        }
    });
}

setupAutocomplete("ville_ent", "villes_ent", "/VilleEntreprises", "ville");
setupAutocomplete("entreprise_ent", "entreprises_ent", "/EntreprisesEntreprises", "entreprise");

setupAutocomplete("ville_off", "villes_off", "/VilleOffres", "ville");
setupAutocomplete("entreprise_off", "entreprises_off", "/EntreprisesOffres", "entreprise");