// Toggle password visibility
$(document).ready(function() {
    $(".toggle-password").click(function() {
        // Find the associated input field
        const input = $(this).closest(".form-floating").find("input");
        // Toggle the type attribute
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $(this).html('<i class="fa-solid fa-eye"></i>'); // Change icon to eye
        } else {
            input.attr("type", "password");
            $(this).html('<i class="fa-solid fa-eye-slash"></i>'); // Change icon to eye-slash
        }
    });
});