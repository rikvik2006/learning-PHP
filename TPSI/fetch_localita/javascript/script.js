document.addEventListener("DOMContentLoaded", function () {
    // Controlla se siamo nella pagina di aggiunta comune
    const formAggiungi = document.getElementById("form-aggiungi-comune");
    if (formAggiungi) {
        formAggiungi.addEventListener("submit", aggiungiComune);
    }

    // Popola automaticamente lo slug quando l'utente digita il nome del comune
    const nameInput = document.getElementById("name");
    const slugInput = document.getElementById("slug");

    if (nameInput && slugInput) {
        nameInput.addEventListener("input", function () {
            // Converti in minuscolo e sostituisci gli spazi con trattini
            slugInput.value = nameInput.value
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^a-z0-9-]/g, "");
        });
    }
});

// Funzione per caricare i comuni e visualizzarli nella tabella
const fetchComuni = async () => {
    const messageEl = document.getElementById("message");
    const tableEl = document.getElementById("comuni-table");

    try {
        messageEl.textContent = "Caricamento dei comuni in corso...";
        messageEl.classList.add("info");
        messageEl.classList.remove("error", "success", "hidden");

        const response = await fetch("api/localita.php", {
            method: "GET",
        });

        if (!response.ok) {
            throw new Error(`Errore HTTP: ${response.status}`);
        }

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        renderComuni(data);
        messageEl.classList.add("hidden");
        tableEl.classList.remove("hidden");
    } catch (error) {
        console.error("Errore nel caricamento dei comuni:", error);
        messageEl.textContent = `Errore: ${error.message}`;
        messageEl.classList.add("error");
        messageEl.classList.remove("info", "success", "hidden");
        tableEl.classList.add("hidden");
    }
};

// Funzione per visualizzare i comuni nella tabella
const renderComuni = (comuni) => {
    const tbody = document.getElementById("comuni-tbody");
    tbody.innerHTML = "";

    comuni.forEach((comune) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${comune.ID}</td>
            <td>${comune.name}</td>
            <td>${comune.lat}</td>
            <td>${comune.lng}</td>
            <td>${comune.codice_provincia_istat}</td>
            <td>${comune.capoluogo_provincia === "1" ? "Sì" : "No"}</td>
        `;

        tbody.appendChild(row);
    });
};

// Funzione per aggiungere un nuovo comune
const aggiungiComune = async (event) => {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const messageEl = document.getElementById("message");

    // Aggiunge valori di default per i checkbox se non selezionati
    if (!formData.has("capoluogo_provincia")) {
        formData.append("capoluogo_provincia", "0");
    }
    if (!formData.has("capoluogo_regione")) {
        formData.append("capoluogo_regione", "0");
    }

    try {
        messageEl.textContent = "Aggiunta del comune in corso...";
        messageEl.classList.add("info");
        messageEl.classList.remove("error", "success", "hidden");

        const response = await fetch("api/aggiungi.php", {
            method: "POST",
            body: formData,
        });

        const data = await response.json();

        if (data.error) {
            messageEl.textContent = `Errore: ${data.error}`;
            messageEl.classList.add("error");
            messageEl.classList.remove("info", "success", "hidden");
        } else {
            messageEl.textContent = `Comune aggiunto con successo! ID: ${data.id}`;
            messageEl.classList.add("success");
            messageEl.classList.remove("error", "info", "hidden");
            form.reset();
        }
    } catch (error) {
        console.error("Errore nell'aggiunta del comune:", error);
        messageEl.textContent = `Errore di connessione: ${error.message}`;
        messageEl.classList.add("error");
        messageEl.classList.remove("info", "success", "hidden");
    }
};
