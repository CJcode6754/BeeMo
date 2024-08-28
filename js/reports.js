document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('myChart').getContext('2d');
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
                    min: 20, // Default min value, will be updated dynamically
                    ticks: {
                        color: 'black',
                        font: {
                            size: 10
                        }
                    },
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

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0];

    // Set the date picker to today's date and disable future dates
    const datePicker = document.getElementById('start-date-picker');
    datePicker.value = today;
    datePicker.max = today;

    function updateChart(dataType, fetchedData) {
        // Map fetched data to labels and data points
        const labels = fetchedData.data.map(item => new Date(item.hour));
        let data;
        let yAxisMin;

        if (dataType === 'temperature') {
            data = fetchedData.data.map(item => item.avg_temperature);
            yAxisMin = 20; // Set the min value for temperature
        } else if (dataType === 'humidity') {
            data = fetchedData.data.map(item => item.avg_humidity);
            yAxisMin = 30; // Set the min value for humidity
        } else if (dataType === 'weight') {
            data = fetchedData.data.map(item => item.avg_weight);
            yAxisMin = 1000; // Set the min value for weight
        }

        myChart.data.labels = labels;
        myChart.data.datasets[0].data = data;
        myChart.data.datasets[0].label = dataType.charAt(0).toUpperCase() + dataType.slice(1);

        // Update Y-axis min value
        myChart.options.scales.y.min = yAxisMin;

        myChart.update();

        // Update descriptive analytics
        document.getElementById('average-value').textContent = fetchedData.stats[dataType].average.toFixed(2);
        document.getElementById('min-value').textContent = fetchedData.stats[dataType].min.toFixed(2);
        document.getElementById('max-value').textContent = fetchedData.stats[dataType].max.toFixed(2);
    }

    function fetchData(date, dataType) {
        fetch('/src/reportsData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ selected_date: date, data_type: dataType })
        })
        .then(response => response.json())
        .then(fetchedData => {
            updateChart(dataType, fetchedData);
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    // Fetch data for the current date and default data type on page load
    const defaultDataType = document.querySelector('.label-current').dataset.type;
    fetchData(today, defaultDataType);

    document.getElementById('start-date-picker').addEventListener('change', function() {
        const selectedDate = this.value;
        const dataType = document.querySelector('.label-current').dataset.type;
        fetchData(selectedDate, dataType);
    });

    document.querySelectorAll('.btn-label').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.label-current').classList.remove('label-current');
            this.classList.add('label-current');

            const selectedDate = document.getElementById('start-date-picker').value;
            const dataType = this.dataset.type;
            fetchData(selectedDate, dataType);
        });
    });
});
