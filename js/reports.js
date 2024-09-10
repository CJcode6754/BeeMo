document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('myChart').getContext('2d');
    const harvestDropdown = document.getElementById('harvestCycleList');
    const harvestCycleButton = document.getElementById('harvestCycleDropdown');
    const datePicker = $('#start-date-picker');
    let selectedType = 'temperature'; // Default type

    // Initialize Chart.js
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [], // Dates or time intervals will go here
            datasets: [
                {
                    label: '', // The label will be updated dynamically
                    data: [], // Data will go here
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        color: 'black',
                        font: {
                            size: 10
                        }
                    },
                    beginAtZero: false, // Ensure the chart does not always start at zero
                    min: function() {
                        // Set the minimum y value based on the selected type
                        if (selectedType === 'temperature') {
                            return 20;
                        } else if (selectedType === 'humidity') {
                            return 30;
                        } else {
                            return undefined; // Default behavior for other types
                        }
                    }
                },
                x: {
                    ticks: {
                        color: 'black',
                        font: {
                            size: 10
                        }
                    },
                    type: 'time',
                    time: {
                        unit: 'hour', // Display hourly intervals
                        tooltipFormat: 'MMM d, YYYY h:mm a'
                    },
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                annotation: {
                    annotations: {
                        temperatureOptimalRange: {
                            type: 'box',
                            yMin: 32,
                            yMax: 34,
                            backgroundColor: 'rgba(0, 255, 0, 0.2)',
                            borderColor: 'rgba(0, 255, 0, 0.4)',
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === 'Temperature'
                        },
                        temperatureLowRange: {
                            type: 'box',
                            yMin: 0,
                            yMax: 32,
                            backgroundColor: 'rgba(255, 127, 127, 0.4)',
                            borderColor: 'rgba(255, 127, 127, 0.6)',
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === 'Temperature'
                        },
                        temperatureHighRange: {
                            type: 'box',
                            yMin: 34,
                            yMax: 50,
                            backgroundColor: 'rgba(255, 127, 127, 0.4)',
                            borderColor: 'rgba(255, 127, 127, 0.6)',
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === 'Temperature'
                        },
                        humidityOptimalRange: {
                            type: 'box',
                            yMin: 50,
                            yMax: 60,
                            backgroundColor: 'rgba(0, 255, 0, 0.2)',
                            borderColor: 'rgba(0, 255, 0, 0.4)',
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === 'Humidity'
                        },
                        humidityLowRange: {
                            type: 'box',
                            yMin: 0,
                            yMax: 50,
                            backgroundColor: 'rgba(255, 127, 127, 0.4)',
                            borderColor: 'rgba(255, 127, 127, 0.6)',
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === 'Humidity'
                        },
                        humidityHighRange: {
                            type: 'box',
                            yMin: 60,
                            yMax: 100,
                            backgroundColor: 'rgba(255, 127, 127, 0.4)',
                            borderColor: 'rgba(255, 127, 127, 0.6)',
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === 'Humidity'
                        }
                    }
                }
            }
        }
    });

    // Initialize date picker with the current date
    const today = new Date();
    const formatDate = date => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    datePicker.datepicker({
        dateFormat: 'yy-mm-dd',
        defaultDate: today,
        onSelect: function(dateText) {
            fetchDataForDate(dateText);
        }
    }).datepicker('setDate', today);

    // Fetch and populate harvest cycles
    fetch('./src/getCycles.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(cycle => {
                const li = document.createElement('li');
                li.classList.add('dropdown-item');
                li.textContent = `Cycle ${cycle.id}`;
                li.dataset.id = cycle.id;
                li.dataset.start = cycle.start_of_cycle;
                li.dataset.end = cycle.end_of_cycle;
                harvestDropdown.appendChild(li);
            });

            // Fetch and display data for the current date after harvest cycles are populated
            fetchDataForDate(formatDate(today));
        })
        .catch(error => console.error('Error fetching harvest cycles:', error));

    // Handle harvest cycle selection
    harvestDropdown.addEventListener('click', function(event) {
        if (event.target && event.target.matches('li.dropdown-item')) {
            const selectedCycle = event.target.textContent;
            const startDate = new Date(event.target.dataset.start);
            const endDate = new Date(event.target.dataset.end);

            harvestCycleButton.textContent = selectedCycle;

            // Set date picker range
            datePicker.datepicker('option', 'minDate', startDate);
            datePicker.datepicker('option', 'maxDate', endDate);

            // Fetch and display data for the start date
            fetchDataForDate(formatDate(startDate));
        }
    });

    function fetchDataForDate(selectedDate) {
        fetch('./src/reportsData.php', {
            method: 'POST',
            body: JSON.stringify({ selected_date: selectedDate }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(result => {
            console.log('Data fetched:', result);
    
            const labels = result.data.map(row => row.hour);
            const data = result.data.map(row => row[`avg_${selectedType}`]);
    
            console.log('Labels:', labels);
            console.log('Data:', data);
    
            myChart.data.labels = labels;
            myChart.data.datasets[0].data = data;
            myChart.data.datasets[0].label = selectedType.charAt(0).toUpperCase() + selectedType.slice(1);
            myChart.update();
    
            // Update descriptive statistics
            document.getElementById('date-range-label').textContent = '24 Hours';
            document.getElementById('average-value').textContent = result.stats[selectedType].average.toFixed(2);
            document.getElementById('min-value').textContent = result.stats[selectedType].min.toFixed(2);
            document.getElementById('max-value').textContent = result.stats[selectedType].max.toFixed(2);
        })
        .catch(error => console.error('Error fetching data for date:', error));
    }
    

    // Handle label button clicks
    document.querySelectorAll('.btn-label').forEach(button => {
        button.addEventListener('click', function() {
            selectedType = this.dataset.type;
            document.querySelectorAll('.btn-label').forEach(btn => btn.classList.remove('label-current'));
            this.classList.add('label-current');
            
            // Fetch and update chart for the selected type
            const selectedDate = datePicker.datepicker('getDate');
            if (selectedDate) {
                fetchDataForDate(formatDate(selectedDate));
            }
        });
    });
});
