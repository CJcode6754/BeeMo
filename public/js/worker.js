// document.addEventListener('DOMContentLoaded', function() {
//     const workerForm = document.getElementById('workerForm');
//     const fullName = document.getElementById('FullName');
//     const email = document.getElementById('Email');
//     const phoneNumber = document.getElementById('PhoneNumber');
//     const password = document.getElementById('Password');
//     const submitButton = document.getElementById('btn');

//     // Full name validation (only alphabet characters and spaces)
//     const validateFullName = () => /^[A-Za-z\s]+$/.test(fullName.value.trim());

//     // Email validation (using HTML5 email validation)
//     const validateEmail = () => email.checkValidity();

//     // Phone number validation (starts with 09 and is 11 digits)
//     const validatePhoneNumber = () => /^09\d{9}$/.test(phoneNumber.value.trim());

//     // Password validation (8+ chars, one uppercase, one lowercase, one digit, no special chars)
//     const validatePassword = () => {
//         const passwordValue = password.value.trim();
//         return (
//             /^[A-Za-z\d]{8,32}$/.test(passwordValue) && // Only letters and digits
//             /[A-Z]/.test(passwordValue) &&             // At least one uppercase letter
//             /[a-z]/.test(passwordValue) &&             // At least one lowercase letter
//             /\d/.test(passwordValue)                   // At least one digit
//         );
//     };

//     // General form validation
//     const validateForm = () => {
//         const isFormValid =
//             validateFullName() &&
//             validateEmail() &&
//             validatePhoneNumber() &&
//             validatePassword();

//         submitButton.disabled = !isFormValid; // Disable submit button if invalid
//     };

//     // Event listeners for input validation
//     fullName.addEventListener('input', () => {
//         fullName.classList.toggle('is-valid', validateFullName());
//         fullName.classList.toggle('is-invalid', !validateFullName());
//         validateForm();
//     });

//     email.addEventListener('input', () => {
//         email.classList.toggle('is-valid', validateEmail());
//         email.classList.toggle('is-invalid', !validateEmail());
//         validateForm();
//     });

//     phoneNumber.addEventListener('input', () => {
//         const isValid = validatePhoneNumber();
//         phoneNumber.classList.toggle('is-valid', isValid);
//         phoneNumber.classList.toggle('is-invalid', !isValid);
//         validateForm();
//     });

//     password.addEventListener('input', () => {
//         const isValid = validatePassword();
//         password.classList.toggle('is-valid', isValid);
//         password.classList.toggle('is-invalid', !isValid);
//         validateForm();
//     });

//     // Prevent form submission if invalid
//     workerForm.addEventListener('submit', function(event) {
//         if (!workerForm.checkValidity()) {
//             event.preventDefault();
//             event.stopPropagation();
//         }
//         workerForm.classList.add('was-validated');
//     });
// });


document.addEventListener('DOMContentLoaded', function () {
    const toggleIcons = document.querySelectorAll('.toggle-password');

    toggleIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);

            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="fa-solid fa-eye"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
            }
        });
    });
});
