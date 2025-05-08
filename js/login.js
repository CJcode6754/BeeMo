// <--INDEX (LOGIN PAGE)-->
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('floatingInput');
    const passwordInput = document.getElementById('floatingPassword');
    const submitButton = document.querySelector('#login');

    let emailTouched = false;
    let passwordTouched = false;

    const validateEmail = () => {
        const emailValid = emailInput.checkValidity();
        if (emailTouched) {
            emailInput.classList.toggle('is-invalid', !emailValid);
        }
        return emailValid;
    };

    const validatePassword = () => {
        const passwordValid = passwordInput.checkValidity();
        if (passwordTouched) {
            passwordInput.classList.toggle('is-invalid', !passwordValid);
        }
        return passwordValid;
    };

    const validateForm = () => {
        const emailValid = validateEmail();
        const passwordValid = validatePassword();
        // Enable or disable button based on form validity
        submitButton.disabled = !(emailValid && passwordValid);
    };

    emailInput.addEventListener('input', () => {
        emailTouched = true;
        validateForm();
    });

    passwordInput.addEventListener('input', () => {
        passwordTouched = true;
        validateForm();
    });

    loginForm.addEventListener('submit', function(event) {

        const emailValid = validateEmail();
        const passwordValid = validatePassword();

        if (emailValid && passwordValid) {
            this.submit();
        } else {
            if (!emailTouched) {
                emailTouched = true;
                validateEmail();
            }

            if (!passwordTouched) {
                passwordTouched = true;
                validatePassword();
            }
        }
    });

    // Initial validation to ensure the button state is correct on page load
    validateForm();
});

document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#floatingPassword');
    const toggleVisibility = function(field, toggle) {
        // Toggle the type attribute for the field
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        // Toggle the eye icon
        toggle.querySelector('i').classList.toggle('fa-eye');
        toggle.querySelector('i').classList.toggle('fa-eye-slash');
    };

    togglePassword.addEventListener('click', function() {
        toggleVisibility(passwordField, togglePassword);
    });
});