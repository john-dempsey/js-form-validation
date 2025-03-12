document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded with JavaScript');

    let table = document.querySelector('#table-profiles');
    let tbody = table.querySelector('tbody');
    let rows = tbody.querySelectorAll('tr');

    // rows.forEach(function(row) {
    //     let cells = row.querySelectorAll('td');
    //     console.log(row.dataset.id, cells[0].textContent);
    // });

    let form = document.querySelector('#form-profile');
    let btn = form.querySelector('#btn-submit');
    let categories = form.querySelectorAll('select#category option');

    // let categories2 = [];
    // for (let i = 0; i != categories.length; i++) {
    //     let object = {
    //         value: categories[i].value,
    //         text: categories[i].textContent
    //     };
    //     categories2.push(object);
    // }
    // categories = categories2;

    categories = Array.from(categories).map(function(category) {
        return {
            value: category.value,
            text: category.textContent
        };
    });
    let experiences = form.querySelectorAll('input[name="experience"]');
    experiences = Array.from(experiences).map(function(experience) {
        return experience.value;
    });
    let languages = form.querySelectorAll('input[name="languages[]"]');
    languages = Array.from(languages).map(function(language) {
        return language.value;
    });

    btn.addEventListener('click', function(event) {
        event.preventDefault();

        clearFormErrors();

        let data = getFormData(form);

        let errors = validateFormData(data);

        let numErrors = Object.keys(errors).length;
        if (numErrors > 0) {
            showFormErrors(errors);
        } 
        else {
            // form.submit();
            insertRow(data);
            form.reset();
        }
    });

    function clearFormErrors() {
        let form = document.querySelector('#form-profile');
        let divs = form.querySelectorAll('.error');

        divs.forEach(function(div) {
            div.textContent = '';
        });
    }

    function getFormData(form) {
        let formData = new FormData(form);
        return {
            name:       formData.get('name'),
            age:        formData.get('age'),
            category:   formData.get('category'),
            experience: formData.get('experience'),
            languages:  formData.getAll('languages[]')
        };
    }

    function validateFormData(data) {
        let errors = {};

        if (data.name.length === 0) {
            errors["name"] = 'Name is required';
        }
        else if (data.name.length < 6) {
            errors["name"] = 'Name must be at least 6 characters';
        }

        if (data.age.length === 0) {
            errors["age"] = 'Age is required';
        }
        else {
            let age = Number(data.age);
            if (isNaN(age) || Number.isInteger(age) === false) {
                errors["age"] = 'Age must be an integer';
            }
            else if (age < 21) {
                errors["age"] = 'Age must be 21 or greater';
            }
        }

        if (data.category.length === 0) {
            errors["category"] = 'Category is required';
        }
        else if (categories.some((category) => data.category === category.value) === false) {
            errors["category"] = 'Category is invalid';
        }

        if (data.experience === null) {
            errors["experience"] = 'Experience is required';
        }
        else if (experiences.includes(data.experience) === false) {
            errors["experience"] = 'Experience is invalid';
        }

        if (data.languages.length === 0) {
            errors["languages"] = 'Languages is required';
        }
        else if (data.languages.length > 2) {
            errors["languages"] = 'Choose 1 or 2 languages';
        }
        else if (data.languages.every((language) => languages.includes(language)) === false) {
            errors["languages"] = 'Languages is invalid';
        }

        return errors;
    }

    function showFormErrors(errors) {
        let form = document.querySelector('#form-profile');

        for (let key in errors) {
            let error = errors[key];
            let div = form.querySelector("#error-" + key);
            div.textContent = error;
        }
    }

    function insertRow(data) {
        let row = tbody.insertRow();
        row.dataset.id = rows.length + 1;
        for (let key in data) {
            let cell = row.insertCell();
            switch (key) {
                case "category":
                    cell.innerHTML = categories.find(category => category.value === data[key]).text;
                    break;
                case "languages":
                    cell.innerHTML = data[key].join(',');
                    break;
                default:
                    cell.innerHTML = data[key];
            }
        }
    }
});