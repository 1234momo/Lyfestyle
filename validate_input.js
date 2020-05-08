// Validate if email entered is a valid format
function validate_email(form) {
    let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
    if (!regex.test(form.value.toLowerCase())) {
        form.setCustomValidity('Must be a valid email address');
        form.reportValidity();
        form.style.border = '2px solid #ff0000';
    } 
    else {
        form.setCustomValidity('');
        form.style.border = '';
    }
}

// Makes sure weight only has whole number
function validate_weight(form) {
    if (/[^0-9]/.test(form.value) || form.value === '') {
        form.setCustomValidity('Weight must be a whole number');
        form.reportValidity();
        form.style.border = '2px solid #ff0000';
        return false;
    } 
    else {
        form.setCustomValidity('');
        form.style.border = '';
        return true;
    }
}

// Checks if the password and current password is the same
function check_password(form) {
    if (form.value != document.getElementById('password').value) {
        form.setCustomValidity('Password and confirm password must be matching.');
        form.reportValidity();
        form.style.border = '2px solid #ff0000';
        return false;
    } 
    else {
        form.setCustomValidity('');
        form.style.border = '';
        return true;
    }
}

// Validate the first name and second name to only have letters 
function validate_names(form) {
    if (/[^a-zA-Z ]+/.test(form.value)) {
        form.setCustomValidity('Name must contain only letters');
        form.reportValidity();
        form.style.border = '2px solid #ff0000';
        return false;
    } 
    else {
        form.setCustomValidity('');
        form.style.border = '';
        return true;
    }
}

// Validates calories entered 
function validate_second_field(form) {
    if (/[^0-9.]/.test(form.value) || form.value === '') {
        console.log("heeloo");
        form.setCustomValidity('Must contain only whole or decimal values');
        form.reportValidity();
        form.style.border = '2px solid #ff0000';
        return false;
    } 
    else {
        form.setCustomValidity('');
        form.style.border = '';
        return true;
    }
}

// Validate the time input to have ony whole numbers
function validate_time_field(form) {
    if (/[^0-9]./.test(form.value) || form.value === '') {
        console.log("heeloo");
        form.setCustomValidity('Must contain only whole values');
        form.reportValidity();
        form.style.border = '2px solid #ff0000';
        return false;
    } 
    else {
        form.setCustomValidity('');
        form.style.border = '';
        return true;
    }
}

// Validate custom name entered for adding and editing food and exercise to not have a comma
function validate_name_field(form) {
    console.log("name called");
    if (/[,]/.test(form.value)) {
        console.log(form.value);
        form.setCustomValidity('Name must not contain a comma');
        form.reportValidity();
        form.style.border = '2px solid #ff0000';
        return false;
    } 
    else {
        form.setCustomValidity('');
        form.style.border = '';
        return true;
    }
}
