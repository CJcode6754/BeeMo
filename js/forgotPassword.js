// <--FORGOT PASSWORD JS-->
document.addEventListener('DOMContentLoaded', function() {
    const forgotForm = document.getElementById('forgotForm');
    const emailInput = document.getElementById('floatingInput1');
    emailInput.addEventListener('input', function() {
        if (emailInput.validity.valid) {
            emailInput.classList.remove('is-invalid');
        } else {
            emailInput.classList.add('is-invalid');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const forgotForm = document.getElementById('forgotForm');
    const emailInput = document.getElementById('floatingInput1');
    const submitButton = document.getElementById('btn'); // Select the button

    // Function to validate form
    function validateForm() {
        if (emailInput.value === '') {
            // Don't apply invalid state if the field is empty initially
            emailInput.classList.remove('is-invalid');
            submitButton.disabled = true;
        } else if (emailInput.validity.valid) {
            submitButton.disabled = false;
            emailInput.classList.remove('is-invalid');
        } else {
            submitButton.disabled = true;
            emailInput.classList.add('is-invalid');
        }
    }

    // Event listener for input change
    emailInput.addEventListener('input', validateForm);

    // Initial validation check
    validateForm();
});