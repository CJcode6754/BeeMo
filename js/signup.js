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
        const namePattern = /^[A-Za-z\s]+$/;
        if (!namePattern.test(fullNameInput.value.trim())) {
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
        const mobileNumberPattern = /^[0-9]{11}$/;
        if (!mobileNumberPattern.test(mobileNumberInput.value)) {
            if (mobileNumberTouched) {
                mobileNumberInput.classList.add('is-invalid');
            }
        } else {
            mobileNumberInput.classList.remove('is-invalid');
        }
    };

    const errorMessageElement = document.getElementById('passwordErrorMessage');

    const validatePassword = () => {
        const passwordValue = passwordInput.value.trim();
        let errors = [];

        if (passwordValue) {
            const specialCharPattern = /[^A-Za-z0-9]/;
            if (specialCharPattern.test(passwordValue)) {
                errors.push("Password must not contain special characters.");
            }

            if (passwordValue.length < 8 || passwordValue.length > 32) {
                errors.push("Password must be 8-32 characters long.");
            }

            if (!/[A-Z]/.test(passwordValue)) {
                errors.push("Password must contain at least one uppercase letter.");
            }

            if (!/[a-z]/.test(passwordValue)) {
                errors.push("Password must contain at least one lowercase letter.");
            }

            if (!/\d/.test(passwordValue)) {
                errors.push("Password must contain at least one number.");
            }
        }

        if (errors.length > 0) {
            passwordInput.classList.add('is-invalid');
            errorMessageElement.textContent = errors.join(' ');
        } else {
            passwordInput.classList.remove('is-invalid');
            errorMessageElement.textContent = '';
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

        // Manually check the form validity and button state
        let isFormValid = true;

        if (fullNameInput.classList.contains('is-invalid') ||
            emailInput.classList.contains('is-invalid') ||
            mobileNumberInput.classList.contains('is-invalid') ||
            passwordInput.classList.contains('is-invalid') ||
            confirmPasswordInput.classList.contains('is-invalid') ||
            termsCheckbox.classList.contains('is-invalid')) {
            isFormValid = false;
        }

        // Disable/enable the submit button
        if (isFormValid) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    };

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
        validatePassword();
        validateForm();
    });

    confirmPasswordInput.addEventListener('input', () => {
        confirmPasswordTouched = true;
        validateConfirmPassword();
        validateForm();
    });

    termsCheckbox.addEventListener('change', () => {
        termsTouched = true;
        validateForm();
    });

    // Initial form validation
    validateForm();
});
