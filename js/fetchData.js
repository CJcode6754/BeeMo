const eventSource = new EventSource('./src/fetch_data.php');

eventSource.onmessage = function(event) {
    const data = JSON.parse(event.data);

    if (data.temperature !== undefined && 
        data.humidity !== undefined && 
        data.weight !== undefined) {
        
        // Convert weight from grams to kilograms
        const weightInKg = (data.weight / 1000).toFixed(2);

        // Update the HTML elements
        document.querySelector('.temp-degree').textContent = `${data.temperature} °C`;
        document.querySelector('.humid-percent').textContent = `${data.humidity}%`;
        document.querySelector('.weight-value').textContent = `${weightInKg} kg`;
    } else {
        console.error('Invalid data structure received:', data);
    }
};

eventSource.onerror = function(error) {
    console.error('EventSource error:', error);
};
