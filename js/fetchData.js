// <--FETCH DATA TO DATABASE TO SHOW THE PARAMETER DATA-->
function fetchData() {
    fetch('fetch_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error fetching data:', data.error);
                return;
            }

            // Convert weight from grams to kilograms
            const weightInKg = (data.weight / 1000).toFixed(2);

            // Update the elements with new data
            document.querySelector('.temp-degree').textContent = `${data.temperature} °C`;
            document.querySelector('.humid-percent').textContent = `${data.humidity}%`;
            document.querySelector('.weight-value').textContent = `${weightInKg} kg`;
        })
        .catch(error => console.error('Error fetching data:', error));
}

setInterval(fetchData, 10000);
window.onload = fetchData; // Fetch data on initial load