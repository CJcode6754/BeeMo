// <--ADD WORKER-->
document.addEventListener('DOMContentLoaded', function() {
    const workerForm = document.getElementById('workerForm');
    const fullName = document.getElementById('FullName');
    const email = document.getElementById('Email');
    const phoneNumber = document.getElementById('PhoneNumber');
    const password = document.getElementById('Password');
    const submitButton = document.getElementById('btn');

    const validateForm = () => {
        const fullNameValid = fullName.checkValidity();
        const emailValid = email.checkValidity();
        const phoneNumberValid = /^[0-9]{10,15}$/.test(phoneNumber.value);
        const passwordValid = password.value.trim().length >= 8 && password.value.trim().length <= 32;

        if (fullNameValid && emailValid && phoneNumberValid && passwordValid) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    };

    fullName.addEventListener('input', () => {
        fullNameTouched = true;
        if (fullName.checkValidity()) {
            fullName.classList.remove('is-invalid');
            fullName.classList.add('is-valid');
        } else {
            fullName.classList.remove('is-valid');
            fullName.classList.add('is-invalid');
        }
        validateForm();
    });

    email.addEventListener('input', () => {
        emailTouched = true;
        if (email.checkValidity()) {
            email.classList.remove('is-invalid');
            email.classList.add('is-valid');
        } else {
            email.classList.remove('is-valid');
            email.classList.add('is-invalid');
        }
        validateForm();
    });

    phoneNumber.addEventListener('input', () => {
        phoneNumberTouched = true;
        const mobileNumberPattern = /^[0-9]{11}$/; // Adjust this pattern as needed
        if (!mobileNumberPattern.test(phoneNumber.value)) {
            phoneNumber.classList.remove('is-valid');
            phoneNumber.classList.add('is-invalid');
        } else {
            phoneNumber.classList.remove('is-invalid');
            phoneNumber.classList.add('is-valid');
        }
        validateForm();
    });

    password.addEventListener('input', () => {
        passwordTouched = true;
        const passwordValue = password.value.trim();
        if (passwordValue.length < 8 || passwordValue.length > 32) {
            password.classList.remove('is-valid');
            password.classList.add('is-invalid');
        } else {
            password.classList.remove('is-invalid');
            password.classList.add('is-valid');
        }
        validateForm();
    });

    workerForm.addEventListener('submit', function(event) {
        if (!workerForm.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        workerForm.classList.add('was-validated');
    }, false);
});

// <--EDIT WORKER-->
document.addEventListener('DOMContentLoaded', function() {
    const workerForm = document.getElementById('edit_workerForm');
    const fullName = document.getElementById('Edit_FullName');
    const email = document.getElementById('Edit_Email');
    const phoneNumber = document.getElementById('Edit_PhoneNumber');
    const password = document.getElementById('Edit_Password');
    const submitButton = document.getElementById('Edit_btn');

    const validateForm = () => {
        const fullNameValid = fullName.checkValidity();
        const emailValid = email.checkValidity();
        const phoneNumberValid = /^[0-9]{10,15}$/.test(phoneNumber.value);
        const passwordValid = password.value.trim().length >= 8 && password.value.trim().length <= 32;

        if (fullNameValid && emailValid && phoneNumberValid && passwordValid) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    };

    fullName.addEventListener('input', () => {
        fullNameTouched = true;
        if (fullName.checkValidity()) {
            fullName.classList.remove('is-invalid');
            fullName.classList.add('is-valid');
        } else {
            fullName.classList.remove('is-valid');
            fullName.classList.add('is-invalid');
        }
        validateForm();
    });

    email.addEventListener('input', () => {
        emailTouched = true;
        if (email.checkValidity()) {
            email.classList.remove('is-invalid');
            email.classList.add('is-valid');
        } else {
            email.classList.remove('is-valid');
            email.classList.add('is-invalid');
        }
        validateForm();
    });

    phoneNumber.addEventListener('input', () => {
        phoneNumberTouched = true;
        const mobileNumberPattern = /^[0-9]{11}$/; // Adjust this pattern as needed
        if (!mobileNumberPattern.test(phoneNumber.value)) {
            phoneNumber.classList.remove('is-valid');
            phoneNumber.classList.add('is-invalid');
        } else {
            phoneNumber.classList.remove('is-invalid');
            phoneNumber.classList.add('is-valid');
        }
        validateForm();
    });

    password.addEventListener('input', () => {
        passwordTouched = true;
        const passwordValue = password.value.trim();
        if (passwordValue.length < 8 || passwordValue.length > 32) {
            password.classList.remove('is-valid');
            password.classList.add('is-invalid');
        } else {
            password.classList.remove('is-invalid');
            password.classList.add('is-valid');
        }
        validateForm();
    });

    workerForm.addEventListener('submit', function(event) {
        if (!workerForm.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        workerForm.classList.add('was-validated');
    }, false);
});