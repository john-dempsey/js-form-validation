document.addEventListener("DOMContentLoaded", function (event) {

    let btn = document.querySelector("#btn-submit");
    let form = document.querySelector('#form-credit-card');
    let validMonths = getOptions("#month");
    let validYears = getOptions("#year");

    btn.addEventListener("click", function (event) {
        event.preventDefault();
        let data = getFormData(form);
        let errors = validateFormData(data);
        console.log(data);
        console.log(errors);
    });

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
});
