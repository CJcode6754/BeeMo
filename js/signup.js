
// <--SIGNUP FORM VALIDATION-->
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPasswordField = document.querySelector('#confirmPassword');
    const registerForm = document.getElementById('registerForm');
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const mobileNumberInput = document.getElementById('mobileNumber');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('termsCheckbox');
    const btn = document.getElementById('btn');

    let fullNameTouched = false;
    let emailTouched = false;
    let mobileNumberTouched = false;
    let passwordTouched = false;
    let confirmPasswordTouched = false;
    let termsTouched = false;

    const toggleVisibility = (field, toggle) => {
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        toggle.querySelector('i').classList.toggle('fa-eye');
        toggle.querySelector('i').classList.toggle('fa-eye-slash');
    };

    togglePassword.addEventListener('click', function() {
        toggleVisibility(passwordField, togglePassword);
    });

    toggleConfirmPassword.addEventListener('click', function() {
        toggleVisibility(confirmPasswordField, toggleConfirmPassword);
    });

    const validateFullName = () => {
        if (!fullNameInput.checkValidity()) {
            if (fullNameTouched) {
                fullNameInput.classList.add('is-invalid');
            }
        } else {
            fullNameInput.classList.remove('is-invalid');
        }
    };

    const validateEmail = () => {
        if (!emailInput.checkValidity()) {
            if (emailTouched) {
                emailInput.classList.add('is-invalid');
            }
        } else {
            emailInput.classList.remove('is-invalid');
        }
    };

    const validateMobileNumber = () => {
        const mobileNumberPattern = /^[0-9]{11}$/; // Adjust this pattern as needed
        if (!mobileNumberPattern.test(mobileNumberInput.value)) {
            if (mobileNumberTouched) {
                mobileNumberInput.classList.add('is-invalid');
            }
        } else {
            mobileNumberInput.classList.remove('is-invalid');
        }
    };

    const validatePassword = () => {
        const passwordValue = passwordInput.value.trim();
        if (passwordValue.length < 8 || passwordValue.length > 32) {
            if (passwordTouched) {
                passwordInput.classList.add('is-invalid');
            }
        } else {
            passwordInput.classList.remove('is-invalid');
        }
    };

    const validateConfirmPassword = () => {
        if (passwordInput.value !== confirmPasswordInput.value || !confirmPasswordInput.checkValidity()) {
            if (confirmPasswordTouched) {
                confirmPasswordInput.classList.add('is-invalid');
            }
        } else {
            confirmPasswordInput.classList.remove('is-invalid');
        }
    };

    const validateTermsCheckbox = () => {
        if (!termsCheckbox.checked) {
            if (termsTouched) {
                termsCheckbox.classList.add('is-invalid');
            }
        } else {
            termsCheckbox.classList.remove('is-invalid');
        }
    };

    const validateForm = () => {
        validateFullName();
        validateEmail();
        validateMobileNumber();
        validatePassword();
        validateConfirmPassword();
        validateTermsCheckbox();

        // Enable or disable button based on form validity
        if (registerForm.checkValidity() && termsCheckbox.checked) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    };

    // <--Add input event listeners for individual validation functions-->
    fullNameInput.addEventListener('input', () => {
        fullNameTouched = true;
        validateForm();
    });

    emailInput.addEventListener('input', () => {
        emailTouched = true;
        validateForm();
    });

    mobileNumberInput.addEventListener('input', () => {
        mobileNumberTouched = true;
        validateForm();
    });

    passwordInput.addEventListener('input', () => {
        passwordTouched = true;
        validateForm();
    });

    confirmPasswordInput.addEventListener('input', () => {
        confirmPasswordTouched = true;
        validateForm();
    });

    termsCheckbox.addEventListener('change', () => {
        termsTouched = true;
        validateForm();
    });

    // Initial form validation on page load
    validateForm();
});