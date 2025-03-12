document.addEventListener("DOMContentLoaded", function (event) {

    let btn = document.querySelector("#btn-submit");
    let form = document.querySelector('#form-credit-card');
    let table = document.querySelector("#table-credit-cards");
    let tbody = table.querySelector("tbody");
    let validMonths = getOptions("#month");
    let validYears = getOptions("#year");

    btn.addEventListener("click", async function (event) {
        event.preventDefault();
        clearErrors();
        let data = getFormData(form);
        let errors = validateFormData(data);
        let numErrors = Object.keys(errors).length;
        if (numErrors === 0) {
            try {
                let response = await storeCard(data);
                if (response.status == true) {
                    insertRow(data);
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
            showErrors(errors);
        }
    });

    function clearErrors() {
        let divs = form.querySelectorAll(".error");
        for (let i = 0; i != divs.length; i++) {
            let div = divs[i];
            div.innerHTML = "";
        }
    }

    function getFormData(form) {
        let data = new FormData(form);
        return {
            "issuer" : data.get("issuer"),
            "name" : data.get("name"),
            "number" : data.get("number"),
            "month" : data.get("month"),
            "year" : data.get("year"),
            "cvv" : data.get("cvv"),
            "save" : data.get("save"),
            "accept" : data.get("accept"),
        };
    }

    function getOptions(id) {
        let result = [];
        let select = form.querySelector(id);
        let options = select.options;
        for (let i = 1; i < options.length; i++) {
            let option = options[i];
            result.push(option.value);
        }
        return result;
    }

    function validateFormData(data) {
        let errors = {};

        
        let radioBtns = form.querySelectorAll("input[name='issuer']")
        let validIssuers = [];
        for (let i = 0; i != radioBtns.length; i++) {
            let radioBtn = radioBtns[i];
            validIssuers.push(radioBtn.value);
        }
        if (data.issuer === null) {
            errors["issuer"] = "Please choose card type";
        }
        else if (validIssuers.includes(data.issuer) === false) {
            errors["issuer"] = "Invalid card type";
        }

        if (data.name.length == 0) {
            errors["name"] = "Name is required";
        }
        else if (data["name"].length < 6) {
            errors.name = "Name must be at least 6 characters";
        }

        if (data.number.length == 0) {
            errors["number"] = "Number is required";
        }
        else if (!data.number.match(/^[0-9]{16}$/)) {
            errors["number"] = "Number is must be exactly 16 digits";
        }

        if (validMonths.includes(data.month) === false) {
            errors["month"] = "Please choose a valid month";
        }

        if (validYears.includes(data.year) === false) {
            errors["year"] = "Please choose a valid year";
        }

        if (data.cvv.length == 0) {
            errors["cvv"] = "CVV is required";
        }
        else if (!data.cvv.match(/^[0-9]{3}$/)) {
            errors["cvv"] = "CVV is must be exactly 3 digits";
        }

        if (data.save !== null && data.save !== "Yes") {
            errors["save"] = "Invalid value for save";
        }

        if (data.accept !== "Yes") {
            errors["accept"] = "You must accept the terms and conditions";
        }

        return errors;
    }

    function showErrors(errors) {
        for (const field in errors) {
            let error = errors[field];
            let id = "#error-" + field;
            let div = form.querySelector(id);
            div.innerHTML = error;
        }
    }

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
