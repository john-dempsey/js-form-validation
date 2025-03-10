<?php
class CreditCardFormValidator extends FormValidator {
    public function __construct($data=[]) {
        parent::__construct($data);
    }

    public function validate() {
        // validate the form fields placing any error messages in the 
        // $this->errors array
        if (!$this->isPresent("name")) {
            $this->errors['name'] = "Name is required";
        }
        else if (!$this->minLength("name", 6)) {
            $this->errors['name'] = "Name must be at least 6 characters";
        }

        if (!$this->isPresent("number")) {
            $this->errors['number'] = "Number is required";
        }
        else if (!$this->isMatch("number", '/^[0-9]{16}$/')) {
            $this->errors['number'] = "Number must be exactly 16 digits";
        }

        $validCardTypes = ["visa", "mcrd", "amex", "disc"];
        if (!$this->isPresent("issuer")) {
            $this->errors['issuer'] = "Card type is required";
        }
        else if (!$this->isElement("issuer", $validCardTypes)) {
            $this->errors['issuer'] = "Card type is invalid";
        }

        $validMonths = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", 
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        if (!$this->isPresent("month")) {
            $this->errors['month'] = "Month is required";
        }
        else if (!$this->isElement("month", $validMonths)) {
            $this->errors['month'] = "Month is invalid";
        }

        $validYears = ["2025", "2026", "2027", "2028", "2029"];
        if (!$this->isPresent("year")) {
            $this->errors['year'] = "Year is required";
        }
        else if (!$this->isElement("year", $validYears)) {
            $this->errors['year'] = "Year is invalid";
        }

        if (!$this->isPresent("cvv")) {
            $this->errors['cvv'] = "CVV is required";
        }
        else if (!$this->isMatch("cvv", '/^[0-9]{3}$/')) {
            $this->errors['cvv'] = "CVV must be exactly 3 digits";
        }

        if ($this->isPresent("save") && $this->data['save'] !== "Yes") {
            $this->errors['save'] = "Invalid save option";
        }

        if (!$this->isPresent("accept") || $this->data['accept'] !== "Yes") {
            $this->errors['accept'] = "You must accept the terms and conditions";
        }
        
        return count($this->errors) === 0;
    }
}
?>