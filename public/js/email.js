//<--EMAIL OTP-->
document.addEventListener('DOMContentLoaded', function() {
    // Function to get URL parameter (you might need to adjust this based on your URL structure)
    function getUrlParameter(name) {
        name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    const userEmail = getUrlParameter('email'); // Assuming 'email' is the parameter name

    // Update the email in the confirmation message
    const userEmailSpan = document.getElementById('userEmail');
    if (userEmail) {
        userEmailSpan.textContent = userEmail;
    }

    // Disable the button and start the timer (if needed)
    const resendButton = document.getElementById('btn');
    resendButton.disabled = true;

    const timerElement = document.getElementById('timer');
    let secondsLeft = 180; // Set your desired timer duration in seconds

    function startTimer() {
        const interval = setInterval(function() {
            secondsLeft--;
            timerElement.textContent = secondsLeft + ' seconds';

            if (secondsLeft === 0) {
                clearInterval(interval);
                resendButton.disabled = false;
            }
        }, 1000);
    }

    // Start timer on page load
    startTimer();
});