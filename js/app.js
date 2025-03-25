import ProfileFormValidator from "./classes/ProfileFormValidator.js";

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded with JavaScript');

    let table = document.querySelector('#table-profiles');
    let tbody = table.querySelector('tbody');
    let rows = tbody.querySelectorAll('tr');

    let form = document.querySelector('#form-profile');
    let btn = form.querySelector('#btn-submit');

    btn.addEventListener('click', function(event) {
        event.preventDefault();

        let formValidator = new ProfileFormValidator(form);
        formValidator.clearErrors();
        let valid = formValidator.validate();
        if (!valid) {
            formValidator.showErrors();
        } 
        else {
            // form.submit();
            insertRow(formValidator.data);
            form.reset();
        }
    });

    function insertRow(data) {
        let row = tbody.insertRow();
        row.dataset.id = rows.length + 1;
        for (let key in data) {
            let cell = row.insertCell();
            switch (key) {
                case "category":
                    let categorySelect = form.querySelector('select#category');
                    let selectOptions = categorySelect.options;
                    let selectedIndex = categorySelect.selectedIndex;
                    let selectedOption = selectOptions[selectedIndex];
                    cell.innerHTML = selectedOption.textContent;
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