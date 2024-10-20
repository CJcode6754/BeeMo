//NOTIFICATION FETCHING DATA AND RESETING IF THE DATA IS SEEN OR UNSEEN
document.addEventListener("DOMContentLoaded", function () {
  const notificationsContainer = document.getElementById("notifications1");
  const nfCountElement = document.getElementById("nf-count1");
  const nfCountBadgeElement = document.getElementById("nf-count-badge1");
  const nfBtn = document.getElementById("nf-btn1");

  function userShowNotification() {
    fetch("userNotification.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({ action: "fetch" }),
    })
      .then((response) => response.json())
      .then((data) => {
        // Clear previous notifications
        notificationsContainer.innerHTML = "";

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

            // Update message based on type
            let noti_message = "No notifications"; // Default message
            let noti_image = ""; // Default image URL
            switch (noti_type) {
              case "user_new_cycle":
                noti_message = "Successfully added new cycle.";
                noti_image = "img/beemo-ico.ico";
                break;
              case "user_failed_to_add_cycle":
                noti_message = "Failed to add new cycle.";
                noti_image = "img/beemo-ico.ico";
                break;
              default:
                noti_message = "Notification";
                noti_image = "img/beemo-ico.ico";
                break;
            }

            // Set alert class based on notification status
            const alertClass =
              noti_seen === "unseen" ? "alert-success" : "alert-dark";

            // Create and append notification
            const notificationItem = document.createElement("a");
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
      .catch((error) => console.error("Fetch Error:", error)); // Log fetch errors
  }

  function userSeenNotification() {
    fetch("userNotification.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({ action: "seen" }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Marked as seen:", data); // Log response for debugging
        nfCountElement.textContent = 0; // Reset notification count to zero
        nfCountBadgeElement.textContent = 0; // Reset badge count to zero
      })
      .catch((error) => console.error("Fetch Error:", error)); // Log fetch errors
  }

  userShowNotification();
  setInterval(userShowNotification, 5000); // Fetch notifications every 5 seconds

  nfBtn.addEventListener("click", function (e) {
    e.stopPropagation();
    notificationsContainer.classList.toggle("show");
    userSeenNotification(); // Mark notifications as seen when the button is clicked
  });

  document.addEventListener("click", function () {
    notificationsContainer.classList.remove("show");
  });

  notificationsContainer.addEventListener("click", function (e) {
    e.stopPropagation(); // Prevent hiding when clicking inside notifications
  });
});
