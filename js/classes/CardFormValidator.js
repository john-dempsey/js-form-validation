import FormValidator from "./FormValidator.js";

class CardFormValidator extends FormValidator {
    constructor(_form) {
        super(_form);
    }

    validate() {
        let radioBtns = this.form.querySelectorAll("input[name='issuer']")
        let validIssuers = [];
        for (let i = 0; i != radioBtns.length; i++) {
            let radioBtn = radioBtns[i];
            validIssuers.push(radioBtn.value);
        }
        if (!this.isPresent("issuer")) {
            this.errors["issuer"] = "Please choose card type";
        }
        else if (!this.isElement("issuer", validIssuers)) {
            this.errors["issuer"] = "Invalid card type";
        }

        if (!this.isPresent("name")) {
            this.errors["name"] = "Name is required";
        }
        else if (!this.minLength("name", 6)) {
            this.errors["name"] = "Name must be at least 6 characters";
        }

        if (!this.isPresent("number")) {
            this.errors["number"] = "Number is required";
        }
        else if (!this.isMatch("number", /^[0-9]{16}$/)) {
            this.errors["number"] = "Number is must be exactly 16 digits";
        }

        let validMonths = this.getOptions("#month");
        if (!this.isPresent("month")) {
            this.errors["month"] = "Month is required";
        }
        else if (!this.isElement("month", validMonths)) {
            this.errors["month"] = "Please choose a valid month";
        }

        let validYears = this.getOptions("#year");
        if (!this.isPresent("year")) {
            this.errors["year"] = "Year is required";
        }
        else if (!this.isElement("year", validYears)) {
            this.errors["year"] = "Please choose a valid year";
        }

        if (!this.isPresent("cvv")) {
            this.errors["cvv"] = "CVV is required";
        }
        else if (!this.isMatch("cvv", /^[0-9]{3}$/)) {
            this.errors["cvv"] = "CVV is must be exactly 3 digits";
        }

        if (this.isPresent("save") && !this.isMatch("save", /Yes/)) {
            this.errors["save"] = "Invalid value for save";
        }

        if (!this.isPresent("accept") || !this.isMatch("accept", /Yes/)) {
            this.errors["accept"] = "You must accept the terms and conditions";
        }

        return Object.keys(this.errors).length === 0;
    }
}

export default CardFormValidator;
