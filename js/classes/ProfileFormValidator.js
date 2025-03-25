import FormValidator from "./FormValidator.js";

class ProfileFormValidator extends FormValidator {
    constructor(_form) {
        super(_form);

        let categories = this.form.querySelectorAll('select#category option');
        this.categories = Array.from(categories).map(function(category) {
            return category.value;
        });
        let experiences = this.form.querySelectorAll('input[name="experience"]');
        this.experiences = Array.from(experiences).map(function(experience) {
            return experience.value;
        });
        let languages = this.form.querySelectorAll('input[name="languages[]"]');
        this.languages = Array.from(languages).map(function(language) {
            return language.value;
        });
    }

    validate() {
        if (!this.isPresent("name")) {
            this.errors["name"] = 'Name is required';
        }
        else if (!this.minLength("name", 6)) {
            this.errors["name"] = 'Name must be over 6 characters';
        }

        if (!this.isPresent("age")) {
            this.errors["age"] = 'Age is required';
        }
        else {
            if (!this.isInteger("age")) {
                this.errors["age"] = 'Age must be an integer';
            }
            else if (!this.min("age", 21)) {
                this.errors["age"] = 'Age must be 21 or greater';
            }
        }

        if (!this.isPresent("category")) {
            this.errors["category"] = 'Category is required';
        }
        else if (!this.isElement("category", this.categories)) {
            this.errors["category"] = 'Category is invalid';
        }

        if (!this.isPresent("experience")) {
            this.errors["experience"] = 'Experience is required';
        }
        else if (!this.isElement("experience", this.experiences)) {
            this.errors["experience"] = 'Experience is invalid';
        }

        if (!this.isPresent("languages[]")) {
            this.errors["languages"] = 'Choose at least one language';
        }
        else if (!this.maxLength("languages[]", 2)) {
            this.errors["languages"] = 'Choose no more than two languages';
        }
        else if (!this.isSubset("languages[]", this.languages)) {
            this.errors["languages"] = 'Languages is invalid';
        }

        return Object.keys(this.errors).length === 0;
    }
}

export default ProfileFormValidator;
