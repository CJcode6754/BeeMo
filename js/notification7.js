document.addEventListener('DOMContentLoaded', function() {
    const notificationsContainer = document.getElementById('notifications');
    const nfCountElement = document.getElementById('nf-count');
    const nfCountBadgeElement = document.getElementById('nf-count-badge');
    const nfBtn = document.getElementById('nf-btn');

// Fetch notifications
function showNotification() {
    fetch('notification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ action: 'fetch' })
    })
    .then(response => response.json())
    .then(data => {
        // Clear previous notifications
        notificationsContainer.innerHTML = '';

        if (data && data.length > 0) {
            // Set notification count
            nfCountElement.textContent = data[0].total;
            nfCountBadgeElement.textContent = data[0].total;

            // Iterate over notifications
            data.slice(1).forEach(noti => {
                const notiDate = new Date(noti.noti_date).toLocaleDateString();
                const alertClass = noti.noti_seen === 'unseen' ? 'alert-success' : 'alert-dark';
                const notiImage = 'img/beemo-ico.ico'; // Default icon for all notifications

                // Define message based on type
                const messages = {
                    add_user: 'User was added successfully.',
                    delete_user: 'User was deleted successfully.',
                    failed_to_delete_user: 'Failed to delete user.',
                    edit_user: 'User was updated successfully.',
                    new_cycle: 'Successfully added new cycle.',
                    failed_to_add_cycle: 'Failed to add new cycle.',
                    delete_cycle: 'Cycle deleted successfully.',
                    failed_to_delete_cycle: 'Failed to delete cycle.',
                    edit_cycle_success: 'Cycle edited successfully.',
                    edit_cycle_failed: 'Failed to edit cycle info.',
                    notMatchPass: 'Password does not match.',
                    newPass: 'Password changed successfully.',
                    errorPass: 'Error updating!',
                    error: 'Error fetching!',
                    profileUpdate: 'Profile updated successfully!',
                    errorProfile: 'Error updating profile!',
                    emailVerification: 'Email verified successfully!',
                    Failed_to_add_user: 'Failed to send Otp.',
                    addUser: 'New User Added!',
                    emailExist: 'Email Already Exist!',
                    email_verification: 'Verify email with OTP sent.',
                    email_verified: 'Email verified successfully!',
                    emptyHiveNum: 'Hive not recorded.',
                    user_new_cycle: 'Cycle added successfully.',
                    user_failed_to_add_cycle: 'Failed to add new cycle.',
                    highTemp: 'Temperature exceeds optimal range.',
                    lowTemp: 'Temperature below optimal range.',
                    highHumid: 'Humidity exceeds optimal range.',
                    lowHumid: 'Humidity below optimal range.',
                    hiveDelete: 'Hive deleted successfully.',
                    hiveDeletePartial: 'Hive deleted, but failed to drop its table.',
                    hiveDeleteError: 'Error preparing to drop the table.',
                    hiveDeleteFailure: 'Invalid Hive ID',
                    complete_cycle: 'A cycle has been completed.',
                    three_days_away: 'There is a cycle that will be completed in 3 days.',
                    wifi_connected: 'Device is connected to Wi-Fi.',
                    dht22_error: 'DHT22 sensor is not working.',
                    load_error: 'Load cell is not working.',
                    not_connected: 'Device is not connected to Wi-Fi.',
                    error: 'No data found for the hive. Please check the device.',
                    harvestReady: 'Hive is in recommended weight range for harvest (2000g or 2kg).'
                };

                const notiMessage = messages[noti.noti_type] || 'Notification';

                // Create notification item
                const notificationItem = document.createElement('a');
                notificationItem.innerHTML = `
                    <div class="notification-item alert ${alertClass}" role="alert" title="${notiDate}">
                        <img src="${notiImage}" alt="Notification Icon" class="notification-icon">
                        <div class="notification-content">
                            <p class="notif_message">${notiMessage}</p>
                            <small class="notif_date">${notiDate}</small>
                        </div>
                    </div>
                `;
                notificationsContainer.appendChild(notificationItem);
            });
        } else {
            notificationsContainer.innerHTML = "<p>No Notifications</p>";
            nfCountElement.textContent = 0;
            nfCountBadgeElement.textContent = 0;
        }
    })
    .catch(error => console.error("Fetch Error:", error));
}


    // Mark notifications as seen
    function seenNotification() {
        fetch('notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ action: 'seen' })
        })
        .then(response => response.json())
        .then(() => {
            nfCountElement.textContent = 0;
            nfCountBadgeElement.textContent = 0;
        })
        .catch(error => console.error("Fetch Error:", error));
    }

    showNotification();
    setInterval(showNotification, 5000);

    // Toggle notifications display
    nfBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationsContainer.classList.toggle('show');
        seenNotification();
    });

    // Close notifications when clicking outside
    document.addEventListener('click', function() {
        notificationsContainer.classList.remove('show');
    });

    // Prevent closing when clicking inside notifications
    notificationsContainer.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
