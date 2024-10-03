function fetchData() {
    fetch('./src/fetch_data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.temperature && data.humidity && data.weight) {
                // Convert weight from grams to kilograms
                const weightInKg = (data.weight / 1000).toFixed(2);

                // Check if elements exist before updating
                const tempElement = document.querySelector('.temp-degree');
                const humidElement = document.querySelector('.humid-percent');
                const weightElement = document.querySelector('.weight-value');
                if (tempElement) {
                    tempElement.textContent = `${data.temperature} °C`;
                }
                if (humidElement) {
                    humidElement.textContent = `${data.humidity}%`;
                }
                if (weightElement) {
                    weightElement.textContent = `${weightInKg} kg`;
                }
            } else {
                console.error('Invalid data structure received:', data);
            }
        })
        .catch(error => console.error('Error fetching data:', error));
}

setInterval(fetchData, 10000);
window.onload = fetchData; // Fetch data on initial load

