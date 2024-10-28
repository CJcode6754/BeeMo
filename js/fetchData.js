function fetchData() {
    fetch('./src/fetch_data.php')
        .then(response => response.json())
        .then(data => {
            // Default values
            const tempElement = document.querySelector('.temp-degree');
            const humidElement = document.querySelector('.humid-percent');
            const weightElement = document.querySelector('.weight-value');

            if (data.temperature !== null && 
                data.humidity !== null && 
                data.weight !== null) {
                
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
                console.error('No data found');
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

// Fetch data initially when the page loads
fetchData();

// Set the page to reload every 30 seconds
setInterval(fetchData, 30000);
