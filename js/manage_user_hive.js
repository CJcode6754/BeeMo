document.addEventListener("DOMContentLoaded", function () {
    const addHiveModal = new bootstrap.Modal(document.getElementById("addHiveModal"));
    const hiveIDInput = document.getElementById("hiveID");
    const hiveNumberInput = document.getElementById("hiveNum");
    const addHiveForm = document.getElementById("add-hive-form");

    // Fetch the next hive ID and hive number
    async function fetchNextHiveData() {
        try {
            const response = await fetch("manage_hive1.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ action: "fetchNextHiveData" })
            });
            const data = await response.json();
            if (data.success) {
                hiveIDInput.value = data.nextHiveID;
                hiveNumberInput.value = data.nextHiveNumber;
            } else {
                alert(data.error || "Error fetching hive data.");
            }
        } catch (error) {
            console.error("Error fetching next hive data:", error);
        }
    }

    document.getElementById("add-hive-button").addEventListener("click", fetchNextHiveData);

    // Handle form submission to add hive
    addHiveForm.addEventListener("submit", async function (e) {
        e.preventDefault();
        try {
            const formData = new URLSearchParams(new FormData(addHiveForm));
            formData.append("action", "addHive");
            const response = await fetch("manage_hive1.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                addHiveModal.hide();
                updateHiveButtons();
            } else {
                alert(data.error || "Error adding hive. Please try again.");
            }
        } catch (error) {
            console.error("Error adding hive:", error);
        }
    });

// Update the hive buttons dynamically
    async function updateHiveButtons() {
        try {
            const container = document.getElementById("hive-button-container");
            const response = await fetch("manage_hive1.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ action: "fetchHiveButtons" })
            });
            const data = await response.json();
            if (data.success) {
                container.innerHTML = data.hives.map(hive =>
                    `<form method="post" action="/setHive1">
                        <input type="hidden" name="hiveID" value="${hive.hiveID}">
                        <button type="submit" class="hive-button mt-4 px-5 fs-5 fw-semibold">Hive ${hive.hiveNum}</button>
                    </form>`
                ).join("");
            } else {
                console.error("Error fetching hive buttons:", data.error);
            }
        } catch (error) {
            console.error("Error updating hive buttons:", error);
        }
    }


    updateHiveButtons(); // Initial call to populate buttons
});
