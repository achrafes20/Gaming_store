<form id="api-form">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="description">Description:</label>
    <input type="text" id="description" name="description">
    <br>
    <label for="imagepath">Image Path:</label>
    <input type="text" id="imagepath" name="imagepath">
    <br>
    <button type="submit">Submit</button>
</form>

<div id="result-container"></div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const resultContainer = document.getElementById("result-container");
    // Correction de l'URL - utiliser le port standard Laravel
    const apiUrl = "http://localhost:8000/api/getproductsdata";

    // Afficher un message de chargement
    resultContainer.innerHTML = "Chargement des données...";

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data.products)) {
                resultContainer.innerHTML = renderResult(data.products);
            } else {
                resultContainer.innerHTML = "Format de données inattendu";
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            resultContainer.innerHTML = `Erreur lors du chargement des données: ${error.message}`;
        });

    function renderResult(data) {
        if (data.length === 0) {
            return "<p>Aucun produit trouvé</p>";
        }

        return data.map(item => `
            <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
                <p><strong>ID:</strong> ${item.id}</p>
                <p><strong>Name:</strong> ${item.name || 'N/A'}</p>
                <p><strong>Description:</strong> ${item.description || 'N/A'}</p>
                <p><strong>Image Path:</strong> ${item.imagepath || 'N/A'}</p>
            </div>
        `).join("");
    }

    const apiForm = document.getElementById("api-form");
    apiForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = {
            name: document.getElementById('name').value,
            description: document.getElementById('description').value,
            imagepath: document.getElementById('imagepath').value,
        };

        // Afficher un message de chargement
        resultContainer.innerHTML = "Envoi des données...";

        fetch("http://localhost:8000/api/xsubmit", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(formData),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Response:", data);
            if (data.message) {
                resultContainer.innerHTML = `<p style="color: green;">${data.message}</p>`;
                // Recharger les données après soumission
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            console.error("Error submitting data:", error);
            resultContainer.innerHTML = `<p style="color: red;">Erreur lors de l'envoi: ${error.message}</p>`;
        });
    });
});
</script>
