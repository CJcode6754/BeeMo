function fetchData() {
    fetch('./src/fetch_data.php')
        .then(response => response.json())
        .then(data => {
            // Default values
            const tempElement = document.querySelector('.temp-degree');
            const humidElement = document.querySelector('.humid-percent');
            const weightElement = document.querySelector('.weight-value');
            const tempInterpretation = document.querySelector('.temp-interpretation');
            const humidInterpretation = document.querySelector('.humid-interpretation');
            if (data.temperature !== null && 
                data.humidity !== null && 
                data.weight !== null) {
                
                const weightInKg = (data.weight / 1000).toFixed(2);
                // Update temperature
                tempElement.textContent = `${data.temperature} °C`;
                if (data.temperature >= 32 && data.temperature <= 36) {
                    tempElement.style.color = 'green';
                    tempInterpretation.textContent = "Normal Hive Temperature";
                    tempInterpretation.style.color = 'green';
                } else if (data.temperature > 36 && data.temperature <= 39) {
                    tempElement.style.color = 'orange';
                    tempInterpretation.textContent = "Slightly High Temperature";
                    tempInterpretation.style.color = 'orange';
                } else if (data.temperature > 39) {
                    tempElement.style.color = 'red';
                    tempInterpretation.textContent = "Hive Temperature is Dangerously High!";
                    tempInterpretation.style.color = 'red';
                } else if (data.temperature >= 28 && data.temperature < 32) {
                    tempElement.style.color = 'blue';
                    tempInterpretation.textContent = "Temperature Below Optimal.";
                    tempInterpretation.style.color = 'blue';
                } else {
                    tempElement.style.color = 'darkblue';
                    tempInterpretation.textContent = "Warning! Temperature is Critically Low!";
                    tempInterpretation.style.color = 'darkblue';
                }

                // Update humidity
                humidElement.textContent = `${data.humidity}%`;
                if (data.humidity >= 50 && data.humidity <= 60) {
                    humidElement.style.color = 'green';
                    humidInterpretation.textContent = "Normal Hive Humidity";
                    humidInterpretation.style.color = 'green';
                } else if (data.humidity > 60 && data.humidity <= 70) {
                    humidElement.style.color = 'orange';
                    humidInterpretation.textContent = "Slightly High Humidity";
                    humidInterpretation.style.color = 'orange';
                } else if (data.humidity > 70) {
                    humidElement.style.color = 'red';
                    humidInterpretation.textContent = "Warning! Excessive humidity!";
                    humidInterpretation.style.color = 'red';
                } else if (data.humidity >= 40 && data.humidity < 50) {
                    humidElement.style.color = 'blue';
                    humidInterpretation.textContent = "Humidity Below Optimal.";
                    humidInterpretation.style.color = 'blue';

                } else {
                    humidElement.style.color = 'darkblue';
                    humidInterpretation.textContent = "Warning! Humidity are dangerously low!";
                    humidInterpretation.style.color = 'darkblue';
                }

                // Update weight
                weightElement.textContent = `${weightInKg - 1} kg`;
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

// Fetch data initially when the page loads
fetchData();

// Fetch data every 30 seconds without reloading the page
setInterval(fetchData, 10000);
