function fetchData() {
    fetch('./src/fetchData.php')
        .then(response => response.json())
        .then(data => {
            // Select elements
            const tempElement = document.querySelector('.temp-degree');
            const humidElement = document.querySelector('.humid-percent');
            const weightElement = document.querySelector('.weight-value');

            // Check if elements exist
            if (!tempElement || !humidElement || !weightElement) {
                console.error('One or more elements not found in the DOM');
                return;
            }

            if (data.temperature !== null && data.humidity !== null && data.weight !== null) {
                // Convert weight from grams to kilograms
                const weightInKg = (data.weight / 1000).toFixed(2);

                // Update temperature
                tempElement.textContent = `${data.temperature} °C`;
                if (data.temperature >= 32 && data.temperature <= 36) {
                    tempElement.style.color = 'green';
                } else if (data.temperature > 36) {
                    tempElement.style.color = 'red';
                } else {
                    tempElement.style.color = 'blue';
                }

                // Update humidity
                humidElement.textContent = `${data.humidity}%`;
                if (data.humidity >= 50 && data.humidity <= 60) {
                    humidElement.style.color = 'green';
                } else if (data.humidity > 60) {
                    humidElement.style.color = 'red';
                } else {
                    humidElement.style.color = 'blue';
                }

                // Update weight
                weightElement.textContent = `${weightInKg} kg`;
            } else {
                console.error('Data fetch returned null values');
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

// Fetch data initially when the page loads
fetchData();

// Reload the page every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);

