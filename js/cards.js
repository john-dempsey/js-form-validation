import CardFormValidator from "./classes/CardFormValidator.js";

document.addEventListener("DOMContentLoaded", function (event) {

    let btn = document.querySelector("#btn-submit");
    let form = document.querySelector('#form-credit-card');
    let table = document.querySelector("#table-credit-cards");
    let tbody = table.querySelector("tbody");
    // let validMonths = getOptions("#month");
    // let validYears = getOptions("#year");

    btn.addEventListener("click", async function (event) {
        event.preventDefault();

        let validator = new CardFormValidator(form);
        validator.clearErrors();
        let valid = validator.validate();
        if (valid) {
            try {
                let response = await storeCard(validator.data);
                if (response.status == true) {
                    insertRow(validator.data);
                    form.reset();
                }
                else {
                    throw new Exception("Error storing card");
                }
            }
            catch (e) {
                console.log(e.getMessage());
                alert("Error storing card");
            }
        }
        else {
            validator.showErrors();
        }
    });

    function insertRow(data) {
        let row = tbody.insertRow();
        for (let i = 0; i != 5; i++) {
            let cell = row.insertCell();
            let text = null;
            switch (i) {
                case 0: text = data.name; break;
                case 1: text = data.number; break;
                case 2: text = data.issuer; break;
                case 3: text = data.month + "/" + data.year; break;
                case 4: text = data.cvv; break;
            }
            cell.innerHTML = text;
        }
    }

    async function storeCard(data) {
        const url = "card_store.php";
        const response = await fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json",
            }
        });
        if (!response.ok) {
            throw new Exception(`Response status: ${response.status}`);
        }
        const json = await response.json();
        return json;
    }
});
