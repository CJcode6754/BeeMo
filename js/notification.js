//NOTIFICATION FETCHING DATA AND RESETING IF THE DATA IS SEEN OR UNSEEN
document.addEventListener('DOMContentLoaded', function() {
    const notificationsContainer = document.getElementById('notifications');
    const nfCountElement = document.getElementById('nf-count');
    const nfCountBadgeElement = document.getElementById('nf-count-badge');
    const nfBtn = document.getElementById('nf-btn');

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

            // Check if the data array is not empty
            if (data.length > 0) {
                // Set the notification count
                nfCountElement.textContent = data[0].total;
                nfCountBadgeElement.textContent = data[0].total;

                // Iterate over notifications
                for (let i = 1; i < data.length; i++) {
                    const noti = data[i];
                    const noti_date = new Date(noti.noti_date).toLocaleDateString(); // Format date
                    const noti_seen = noti.noti_seen;
                    const noti_type = noti.noti_type;
                    const noti_uniqueid = noti.noti_uniqueid;
                    const noti_url = noti.noti_url;

                    const cycleNumber = noti.cycleNumber || 'N/A';
                    // Update message based on type
                    let noti_message = 'No notifications'; // Default message
                    let noti_image = ''; // Default image URL
                    switch (noti_type) {
                        case 'add_user':
                            noti_message = 'User was added successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'delete_user':
                            noti_message = 'User was deleted successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'failed_to_delete_user':
                            noti_message = 'Failed to delete user.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'edit_user':
                            noti_message = 'User was updated successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'new_cycle':
                            noti_message = 'Successfully added new cycle.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'failed_to_add_cycle':
                            noti_message = 'Failed to add new cycle.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'delete_cycle':
                            noti_message = 'Cycle deleted successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'failed_to_delete_cycle':
                            noti_message = 'Failed to delete cycle.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'edit_cycle_success':
                            noti_message = 'Cycle edited successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'edit_cycle_failed':
                            noti_message = 'Failed to edit cycle info.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'notMatchPass':
                            noti_message = 'Password does not match.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'newPass':
                            noti_message = 'Password changed successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'errorPass':
                            noti_message = 'Error updating!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'error':
                            noti_message = 'Error fetching!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'profileUpdate':
                            noti_message = 'Profile updated successfully!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'errorProfile':
                            noti_message = 'Error updating profile!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'emailVerification':
                            noti_message = 'Email verified successfully!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'Failed_to_add_user':
                            noti_message = 'Failed to send Otp.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'addUser':
                            noti_message = 'New User Added!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'emailExist':
                            noti_message = 'Email Already Exist!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'email_verification':
                            noti_message = 'Verify email with OTP sent.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'email_verified':
                            noti_message = 'Email verified successfully!';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'emptyHiveNum':
                            noti_message = 'Hive not recorded.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'user_new_cycle':
                            noti_message = 'Cycle added successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'user_failed_to_add_cycle':
                            noti_message = 'Failed to add new cycle.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'highTemp':
                            noti_message = 'Temperature exceeds optimal range.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'lowTemp':
                            noti_message = 'Temperature below optimal range.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'highHumid':
                            noti_message = 'Humidity exceeds optimal range.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'lowHumid':
                            noti_message = 'Humidity below optimal range.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'hiveDelete':
                            noti_message = 'Hive deleted successfully.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'hiveDeletePartial':
                            noti_message = 'Hive deleted, but failed to drop its table.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'hiveDeleteError':
                            noti_message = 'Error preparing to drop the table.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'hiveDeleteFailure':
                            noti_message = 'Invalid Hive ID';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case 'complete_cycle':
                            noti_message = 'A cycle has been completed.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        case '3days_away':
                            noti_message = 'There is a cycle that will be completed in 3 days.';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                        default:
                            noti_message = 'Notification';
                            noti_image = 'img/beemo-ico.ico';
                            break;
                    }

                    // Set alert class based on notification status
                    const alertClass = noti_seen === 'unseen' ? 'alert-success' : 'alert-dark';

                    // Create and append notification
                    const notificationItem = document.createElement('a');
                    // notificationItem.href = `${noti_url}?notification=${noti_uniqueid}`;
                    notificationItem.innerHTML = `
                        <div class="notification-item alert ${alertClass}" role="alert" title="${noti_date}">
                            <img src="${noti_image}" alt="Notification Icon" class="notification-icon">
                            <div class="notification-content">
                                <p class="notif_message">${noti_message}</p>
                                <small class="notif_date">${noti_date}</small>
                            </div>
                        </div>
                    `;
                    notificationsContainer.appendChild(notificationItem);
                }
            } else {
                notificationsContainer.innerHTML = "<p>No Notifications</p>";
                nfCountElement.textContent = 0; // Set count to zero if no notifications
                nfCountBadgeElement.textContent = 0; // Set badge count to zero
            }
        })
        .catch(error => console.error("Fetch Error:", error)); // Log fetch errors
    }

    function seenNotification() {
        fetch('notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ action: 'seen' })
        })
        .then(response => response.json())
        .then(data => {
            nfCountElement.textContent = 0; // Reset notification count to zero
            nfCountBadgeElement.textContent = 0; // Reset badge count to zero
        })
        .catch(error => console.error("Fetch Error:", error)); // Log fetch errors
    }

    showNotification();
    setInterval(showNotification, 5000); // Fetch notifications every 5 seconds

    nfBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationsContainer.classList.toggle('show');
        seenNotification(); // Mark notifications as seen when the button is clicked
    });

    document.addEventListener('click', function() {
        notificationsContainer.classList.remove('show');
    });

    notificationsContainer.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent hiding when clicking inside notifications
    });
});

