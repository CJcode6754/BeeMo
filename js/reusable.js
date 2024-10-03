document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const togglePasswordIcons = document.querySelectorAll('.toggle-password');
    const saveButton = document.getElementById('save-btn1');

    let isPasswordTouched = false;
    let isNewPasswordTouched = false;
    let isConfirmPasswordTouched = false;

    // Toggle show/hide for password fields
    togglePasswordIcons.forEach(function(icon) {
        icon.addEventListener('click', function() {
            const input = this.closest('.form-floating').querySelector('input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fa-solid fa-eye-slash"></i>' : '<i class="fa-solid fa-eye"></i>';
        });
    });

    // Form validation
    function validateForm() {
        let isValid = true;

        if (isPasswordTouched) {
            // Check if current password is not empty
            if (passwordInput.value.trim() === '') {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordInput.classList.remove('is-invalid');
            }
        }

        if (isNewPasswordTouched) {
            // Check if new password is valid
            if (newPasswordInput.value.trim() === '' || newPasswordInput.value.length < 8 || newPasswordInput.value.length > 32) {
                newPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                newPasswordInput.classList.remove('is-invalid');
            }
        }

        if (isConfirmPasswordTouched) {
            // Check if new password matches confirm password
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
            }
        }

        saveButton.disabled = !isValid;
        return isValid;
    }

    // Event listeners for input fields
    passwordInput.addEventListener('input', function() {
        isPasswordTouched = true;
        validateForm();
    });

    newPasswordInput.addEventListener('input', function() {
        isNewPasswordTouched = true;
        validateForm();
    });

    confirmPasswordInput.addEventListener('input', function() {
        isConfirmPasswordTouched = true;
        validateForm();
    });

    // Initial validation check
    validateForm();

    // Form submission
    const resetPwForm = document.querySelector('form');
    resetPwForm.addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
