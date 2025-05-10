// <-- Reset Password -->
document.addEventListener('DOMContentLoaded', function() {
    const resetPwForm = document.getElementById('ResetpwForm');
    const passwordInput = document.getElementById('floatingPassword2');
    const confirmPasswordInput = document.getElementById('floatingConfirmPassword2');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const submitButton = document.getElementById('btn');

    let isPasswordTouched = false;
    let isConfirmPasswordTouched = false;

    // Show/Hide Password
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fa-solid fa-eye-slash"></i>' : '<i class="fa-solid fa-eye"></i>';
    });

    // Show/Hide Confirm Password
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fa-solid fa-eye-slash"></i>' : '<i class="fa-solid fa-eye"></i>';
    });

    // Form Validation
    function validateForm() {
        let isValid = true;

        if (isPasswordTouched) {
            // Check if password is valid
            if (passwordInput.value.trim() === '' || passwordInput.value.length < 8 || passwordInput.value.length > 32) {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordInput.classList.remove('is-invalid');
            }
        }

        if (isConfirmPasswordTouched) {
            // Check if passwords match
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
            }
        }

        submitButton.disabled = !isValid;
        return isValid;
    }

    // Event listener for input change
    passwordInput.addEventListener('input', function() {
        isPasswordTouched = true;
        validateForm();
    });

    confirmPasswordInput.addEventListener('input', function() {
        isConfirmPasswordTouched = true;
        validateForm();
    });

    // Initial validation check (optional)
    validateForm();

    // Form submission
    resetPwForm.addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});