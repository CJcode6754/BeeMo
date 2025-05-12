document.addEventListener('DOMContentLoaded', function () {
    const notificationsContainer = document.getElementById('notifications');
    const nfCountElement = document.getElementById('nf-count');
    const nfCountBadgeElement = document.getElementById('nf-count-badge');
    const nfBtn = document.getElementById('nf-btn');
    const notifDropdownMenu = document.querySelector('.notif-container');

    // Fetch notifications
    function showNotification() {
        fetch('notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ action: 'fetch' }),
        })
            .then((response) => response.json())
            .then((data) => {
                notificationsContainer.innerHTML = '';

                if (data && data.length > 0) {
                    const totalNotifications = data[0].total;

                    const countText = totalNotifications > 99 ? '+99' : totalNotifications;
                    nfCountElement.textContent = countText;
                    nfCountBadgeElement.textContent = countText;

                    data.slice(1).forEach((noti) => {
                        const notiDate = new Date(noti.noti_date).toLocaleDateString();
                        const alertClass = noti.noti_seen === 'unseen' ? 'alert-success' : 'alert-dark';
                        const notiImage = 'img/beemo-ico.ico';
                        const notiMessage = noti.message;

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
                    notificationsContainer.innerHTML = '<p>No Notifications</p>';
                    nfCountElement.textContent = 0;
                    nfCountBadgeElement.textContent = 0;
                }
            })
            .catch((error) => console.error('Fetch Error:', error));
    }

    // Mark notifications as seen
    function seenNotification() {
        fetch('notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ action: 'seen' }),
        })
            .then((response) => response.json())
            .then(() => {
                nfCountElement.textContent = 0;
                nfCountBadgeElement.textContent = 0;
            })
            .catch((error) => console.error('Fetch Error:', error));
    }

    // Load notifications initially and set up auto-refresh
    showNotification();
    setInterval(showNotification, 5000);

    // Handle bell icon click
    nfBtn?.addEventListener('click', function (e) {
        seenNotification();
        // Don't hide dropdown on click
        e.stopPropagation();
    });

    // Prevent closing when clicking inside dropdown
    notifDropdownMenu.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    // Hide dropdown only when clicking outside
    document.addEventListener('click', function (e) {
        if (!notifDropdownMenu.contains(e.target) && !nfBtn.contains(e.target)) {
            notifDropdownMenu.classList.remove('show');
        }
    });
});
