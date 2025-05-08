document.addEventListener('DOMContentLoaded', function() {
    const notificationsContainer = document.getElementById('notifications1');
    const nfCountElement = document.getElementById('nf-count1');
    const nfCountBadgeElement = document.getElementById('nf-count-badge1');
    const nfBtn = document.getElementById('nf-btn1');

    // Fetch notifications
    function showNotification() {
        fetch('userNotification.php', {
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
                        user_new_cycle: 'Successfully added new cycle.',
                        user_failed_to_add_cycle: 'Failed to add new cycle.',
                        highTemp: 'Temperature exceeds optimal range.',
                        lowTemp: 'Temperature below optimal range.',
                        highHumid: 'Humidity exceeds optimal range.',
                        lowHumid: 'Humidity below optimal range.',
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
        fetch('userNotification.php', {
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

    // Show notifications on page load and periodically
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
