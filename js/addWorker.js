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
       // <--EDIT WORKER-->
       const editWorkerForm = document.getElementById('edit_workerForm');
       const editFullName = document.getElementById('Edit_FullName');
       const editEmail = document.getElementById('Edit_Email');
       const editPhoneNumber = document.getElementById('Edit_PhoneNumber');
       const editPassword = document.getElementById('Edit_Password');
       const editSubmitButton = document.getElementById('Edit_btn');
   
       const validateEditForm = () => {
           const fullNameValid = editFullName.checkValidity();
           const emailValid = editEmail.checkValidity();
           const phoneNumberValid = /^[0-9]{10,15}$/.test(editPhoneNumber.value);
           const passwordValid = editPassword.value.trim().length >= 8 && editPassword.value.trim().length <= 32;
   
           editSubmitButton.disabled = !(fullNameValid && emailValid && phoneNumberValid && passwordValid);
       };
   
       editFullName.addEventListener('input', () => {
           editFullName.classList.toggle('is-valid', editFullName.checkValidity());
           editFullName.classList.toggle('is-invalid', !editFullName.checkValidity());
           validateEditForm();
       });
   
       editEmail.addEventListener('input', () => {
           editEmail.classList.toggle('is-valid', editEmail.checkValidity());
           editEmail.classList.toggle('is-invalid', !editEmail.checkValidity());
           validateEditForm();
       });
   
       editPhoneNumber.addEventListener('input', () => {
           const mobileNumberPattern = /^[0-9]{11}$/;
           const isValid = mobileNumberPattern.test(editPhoneNumber.value);
           editPhoneNumber.classList.toggle('is-valid', isValid);
           editPhoneNumber.classList.toggle('is-invalid', !isValid);
           validateEditForm();
       });
   
       editPassword.addEventListener('input', () => {
           const passwordValid = editPassword.value.trim().length >= 8 && editPassword.value.trim().length <= 32;
           editPassword.classList.toggle('is-valid', passwordValid);
           editPassword.classList.toggle('is-invalid', !passwordValid);
           validateEditForm();
       });
   
       editWorkerForm.addEventListener('submit', function(event) {
           if (!editWorkerForm.checkValidity()) {
               event.preventDefault();
               event.stopPropagation();
           }
           editWorkerForm.classList.add('was-validated');
       });
});