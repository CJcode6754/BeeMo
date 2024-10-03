document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("myChart").getContext("2d");
    const harvestDropdown = document.getElementById("harvestCycleList");
    const harvestCycleButton = document.getElementById("harvestCycleDropdown");
    const datePicker = $("#start-date-picker");
    const averageLabel = document.getElementById("average-label"); // Label to indicate average type
    let selectedType = "temperature"; // Default type
    let timeUnit = "hour"; // Default time unit

    // Initialize Chart.js
    const myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: [],
            datasets: [
                {
                    label: selectedType.charAt(0).toUpperCase() + selectedType.slice(1),
                    data: [],
                    borderColor: "rgba(75, 192, 192, 1)",
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        color: "black",
                        font: {
                            size: 10,
                        },
                    },
                    beginAtZero: false,
                    min: function () {
                        if (selectedType === "temperature") {
                            return 20;
                        } else if (selectedType === "humidity") {
                            return 30;
                        } else if (selectedType === "weight") {
                            return 1000; // Adjust as needed
                        }
                    },
                },
                x: {
                    ticks: {
                        color: "black",
                        font: {
                            size: 10,
                        },
                    },
                    type: "time",
                    time: {
                        unit: timeUnit,
                        tooltipFormat: "MMM d, yyyy h:mm a",
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
                annotation: {
                    annotations: {
                        temperatureOptimalRange: {
                            type: "box",
                            yMin: 32,
                            yMax: 34,
                            backgroundColor: "rgba(0, 255, 0, 0.2)",
                            borderColor: "rgba(0, 255, 0, 0.4)",
                            borderWidth: 1,
                            display: (ctx) =>
                                ctx.chart.data.datasets[0].label === "Temperature",
                        },
                        temperatureLowRange: {
                            type: "box",
                            yMin: 0,
                            yMax: 32,
                            backgroundColor: "rgba(255, 127, 127, 0.4)",
                            borderColor: "rgba(255, 127, 127, 0.6)",
                            borderWidth: 1,
                            display: (ctx) =>
                                ctx.chart.data.datasets[0].label === "Temperature",
                        },
                        temperatureHighRange: {
                            type: "box",
                            yMin: 34,
                            yMax: 50,
                            backgroundColor: "rgba(255, 127, 127, 0.4)",
                            borderColor: "rgba(255, 127, 127, 0.6)",
                            borderWidth: 1,
                            display: (ctx) =>
                                ctx.chart.data.datasets[0].label === "Temperature",
                        },
                        humidityOptimalRange: {
                            type: "box",
                            yMin: 50,
                            yMax: 60,
                            backgroundColor: "rgba(0, 255, 0, 0.2)",
                            borderColor: "rgba(0, 255, 0, 0.4)",
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === "Humidity",
                        },
                        humidityLowRange: {
                            type: "box",
                            yMin: 0,
                            yMax: 50,
                            backgroundColor: "rgba(255, 127, 127, 0.4)",
                            borderColor: "rgba(255, 127, 127, 0.6)",
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === "Humidity",
                        },
                        humidityHighRange: {
                            type: "box",
                            yMin: 60,
                            yMax: 100,
                            backgroundColor: "rgba(255, 127, 127, 0.4)",
                            borderColor: "rgba(255, 127, 127, 0.6)",
                            borderWidth: 1,
                            display: (ctx) => ctx.chart.data.datasets[0].label === "Humidity",
                        },
                    },
                },
            },
        },
    });

    const today = new Date();
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        return `${year}-${month}-${day}`;
    };

    datePicker
        .datepicker({
            dateFormat: "yy-mm-dd",
            defaultDate: today,
            onSelect: function (dateText) {
                fetchDataForDate(dateText);
            },
        })
        .datepicker("setDate", today)
        .prop("disabled", true);

    fetch("./src/getCycles.php")
        .then((response) => response.json())
        .then((data) => {
            data.forEach((cycle) => {
                const li = document.createElement("li");
                li.classList.add("dropdown-item");
                li.textContent = `Cycle ${cycle.cycle_number}`;
                li.dataset.id = cycle.id;
                li.dataset.start = cycle.start_of_cycle;
                li.dataset.end = cycle.end_of_cycle;
                harvestDropdown.appendChild(li);
            });
            fetchDataForDate(formatDate(today)); // Fetch data for the current day by default
        })
        .catch((error) => console.error("Error fetching harvest cycles:", error));

    harvestDropdown.addEventListener("click", function (event) {
        if (event.target && event.target.matches("li.dropdown-item")) {
            const selectedCycle = event.target.textContent;
            const startDate = new Date(event.target.dataset.start);
            const endDate = new Date(event.target.dataset.end);

            harvestCycleButton.textContent = selectedCycle;

            datePicker.prop("disabled", false);
            fetchDataForDate(formatDate(today)); // Fetch data for the current day by default

            // Set date picker range
            datePicker.datepicker('option', 'minDate', startDate);
            datePicker.datepicker('option', 'maxDate', endDate);

            // Fetch and display data for the start date
            fetchDataForDate(formatDate(startDate));

        }
    });

    // Update chart and analytics based on selected type
    function updateChart() {
        fetchDataForDate(formatDate(new Date())); // Fetch current day's data
    }

    function fetchDataForDate(selectedDate) {
        fetch("./src/reportsData.php", {
            method: "POST",
            body: JSON.stringify({ selected_date: selectedDate, type: selectedType, time_unit: timeUnit }), // Include time unit
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((result) => {
                const labels = result.data.map((row) => row.hour);
                const data = result.data.map((row) => row[`avg_${selectedType}`]);

                myChart.data.labels = labels;
                myChart.data.datasets[0].data = data;
                myChart.data.datasets[0].label = selectedType.charAt(0).toUpperCase() + selectedType.slice(1);
                myChart.update();

                // Update descriptive analytics
                updateDescriptiveAnalytics(result.stats);
            })
            .catch((error) => console.error("Error fetching data:", error));
    }

    function updateDescriptiveAnalytics(stats) {
        document.getElementById("date-range-label").textContent = "Daily Average" || "-";
        document.getElementById("average-value").textContent =
            stats[selectedType].average.toFixed(2) || "-";
        document.getElementById("min-value").textContent =
            stats[selectedType].min.toFixed(2) || "-";
        document.getElementById("max-value").textContent =
            stats[selectedType].max.toFixed(2) || "-";
    }

    document.querySelectorAll(".btn-label").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default anchor behavior
            selectedType = this.dataset.type; // Update selectedType based on the clicked button

            // Update button styles to indicate active selection
            document.querySelectorAll(".btn-label").forEach(btn => {
                btn.classList.remove("label-current");
            });
            this.classList.add("label-current");

            updateChart(); // Fetch data and update the chart
        });
    });

    document.getElementById("reset-filter").addEventListener("click", function () {
        timeUnit = "hour"; // Reset to daily average by default
        myChart.data.labels = [];
        myChart.data.datasets[0].data = [];
        myChart.update();
        datePicker.datepicker("setDate", today);
        fetchDataForDate(formatDate(today));
        averageLabel.textContent = "Average: Daily Average"; // Reset average label
    });
});
