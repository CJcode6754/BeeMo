document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("myChart").getContext("2d");
  const harvestDropdown = document.getElementById("harvestCycleList");
  const harvestCycleButton = document.getElementById("harvestCycleDropdown");
  const filterButton = document.getElementById("monthlyFilter");
  const datePicker = $("#start-date-picker");
  const averageLabel = document.getElementById("average-label");
  let selectedType = "temperature"; // Default type
  let time_unit = "hour"; // Default time unit for daily average

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
            unit: time_unit,
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
              yMin: 20,
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
              yMin: 30,
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

  function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, "0"); // Months are zero-based
    const day = String(d.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
  }

  // Fetch initial data
  const today = new Date();
  datePicker
    .datepicker({
      dateFormat: "yy-mm-dd",
      defaultDate: today,
      onSelect: function (dateText) {
        fetchDataForDate(dateText);
      },
    })
    .datepicker("setDate", today)
    .prop("disabled", true); // Initially disabled

  let selectedCycleID; // Declare this variable at the top to make it accessible

  // Fetch harvest cycles and populate dropdown
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
        li.dataset.cycleId = cycle.id;
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

      // Set the selected cycle ID
      selectedCycleID = event.target.dataset.id; // Set the selected cycle ID

      // Set the current date
      const currentDate = new Date();

      // Determine which date to use for fetching data
      const fetchDate = currentDate > endDate ? endDate : currentDate;

      // Update button text and enable the date picker
      harvestCycleButton.textContent = selectedCycle;
      datePicker.prop("disabled", false);

      // Set date picker range
      datePicker.datepicker("option", "minDate", startDate);
      datePicker.datepicker("option", "maxDate", endDate);

      // Fetch and display data for the determined date
      fetchDataForDate(formatDate(fetchDate));

      // Populate the month dropdown based on the cycle's date range
      populateMonthDropdown(startDate, endDate, selectedCycleID); // Pass selectedCycleID
    }
  });

  // Function to fetch data for the selected date
  function fetchDataForDate(selectedDate = null) {
    const requestData = { type: selectedType, time_unit: "hour" }; // Use hourly time unit

    if (selectedDate) {
      requestData.selected_date = selectedDate;
    }

    fetch("./src/reportsData.php", {
      method: "POST",
      body: JSON.stringify(requestData),
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((result) => {
        const labels = result.data.map((row) => row.hour);
        const data = result.data.map((row) => row[`avg_${selectedType}`]); // Ensure we access the correct key

        myChart.data.labels = labels;
        myChart.data.datasets[0].data = data;

        // Update the dataset label based on selected type
        myChart.data.datasets[0].label =
          selectedType.charAt(0).toUpperCase() + selectedType.slice(1);

        myChart.update();
        updateDescriptiveAnalytics(result.stats);
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  // Function to fetch data for a specific month within the cycle date range
  // Function to fetch data for a specific month within the cycle date range
  function fetchDataForMonth(
    month,
    year,
    cycleStartDate,
    cycleEndDate,
    selectedCycleID
  ) {
    // Ensure the month is between 1 and 12, and the year is valid
    if (month < 1 || month > 12 || isNaN(year)) {
      console.error("Invalid month or year provided");
      return;
    }

    // Get the first day of the selected month
    let startOfMonth = new Date(year, month - 1, 1);
    let endOfMonth = new Date(year, month, 0);

    // Convert cycle dates to Date objects
    cycleStartDate = new Date(cycleStartDate);
    cycleEndDate = new Date(cycleEndDate);

    // Trim the startOfMonth and endOfMonth to the cycle boundaries
    if (startOfMonth < cycleStartDate) {
      startOfMonth = cycleStartDate;
    }
    if (endOfMonth > cycleEndDate) {
      endOfMonth = cycleEndDate;
    }

    // Check if the adjusted date range is valid
    if (startOfMonth > endOfMonth) {
      console.error("Start date cannot be after end date");
      return;
    }

    const requestData = {
      type: selectedType,
      time_unit: "day",
      start_date: startOfMonth.toISOString().split("T")[0],
      end_date: endOfMonth.toISOString().split("T")[0],
      month: month,
      cycle_id: selectedCycleID,
    };

    // console.log("Request data:", requestData);

    fetch("./src/reportsData.php", {
      method: "POST",
      body: JSON.stringify(requestData),
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.text(); // Get the raw text response first
      })
      .then((text) => {
        // console.log("Raw response text:", text); // Log the raw response
        if (!text || text.trim() === "") {
          throw new Error("Received empty response from the server");
        }
        try {
          const result = JSON.parse(text);
        //   console.log("Parsed JSON:", result);

          const labels = result.data.map((row) => row.period);
          const data = result.data.map((row) => row[`avg_${selectedType}`]);
          myChart.data.labels = labels;
          myChart.data.datasets[0].data = data;

          myChart.data.datasets[0].label =
            selectedType.charAt(0).toUpperCase() + selectedType.slice(1);
          myChart.update();

          let isMonthlyFilter = true;
          updateDescriptiveAnalytics(result.stats, isMonthlyFilter);

          myChart.options.scales.x.time.unit = "day"; // Set x-axis to use days
          myChart.update(); // Update chart with new options
        } catch (error) {
          console.error("Error parsing JSON:", error);
          document.getElementById("error-display").textContent =
            "Server Error: " + text;
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error); // Display error message
      });
  }

  // Event listener for month selection
  document
    .getElementById("monthDropdown")
    .addEventListener("click", function (event) {
      if (event.target && event.target.matches("li.dropdown-item")) {
        const selectedMonth = parseInt(event.target.dataset.month);
        const selectedYear = parseInt(event.target.dataset.year);
        const selectedCycleID = parseInt(event.target.dataset.cycleId); // Ensure this is defined

        const cycleStartDate = new Date(event.target.dataset.start); // Use the cycle start date
        const cycleEndDate = new Date(event.target.dataset.end); // Use the cycle end date

        // Convert selected month to its name
        let selectedMonthName = new Intl.DateTimeFormat("default", {
          month: "long",
        }).format(new Date(selectedYear, selectedMonth - 1));

        filterButton.textContent = selectedMonthName;

        // Fetch and display data for the selected month and year, passing cycle dates
        fetchDataForMonth(
          selectedMonth,
          selectedYear,
          cycleStartDate,
          cycleEndDate,
          selectedCycleID // Pass the selected cycle ID
        );
      }
    });

  // Function to populate month dropdown
  function populateMonthDropdown(startOfCycle, endOfCycle, cycleID) {
    const monthDropdown = document.getElementById("monthDropdown");
    monthDropdown.innerHTML = ""; // Clear previous options
    const months = [];

    for (let m = startOfCycle.getMonth(); m <= endOfCycle.getMonth(); m++) {
      months.push(new Date(startOfCycle.getFullYear(), m, 1));
    }

    months.forEach((month) => {
      const li = document.createElement("li");
      li.classList.add("dropdown-item");
      li.textContent = month.toLocaleString("default", { month: "long" });
      li.dataset.month = month.getMonth() + 1; // Get month number (1-12)
      li.dataset.year = month.getFullYear(); // Get year
      li.dataset.start = startOfCycle.toISOString().split("T")[0]; // Add cycle start date for dropdown items
      li.dataset.end = endOfCycle.toISOString().split("T")[0]; // Add cycle end date for dropdown items
      li.dataset.cycleId = cycleID; // Set cycle ID for dropdown items
      monthDropdown.appendChild(li);
    });
  }
  // Add event listeners for buttons to change data type
  document.querySelectorAll(".btn-label").forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      selectedType = this.dataset.type; // Get the selected type from the button's data attribute

      // Update button styles
      document.querySelectorAll(".btn-label").forEach((btn) => {
        btn.classList.remove("label-current");
      });
      this.classList.add("label-current"); // Highlight the selected button

      // Fetch new data for the currently selected type
      fetchDataForDate(formatDate(datePicker.datepicker("getDate"))); // Fetch data for the selected date
    });
  });

  // Function to update descriptive analytics
  function updateDescriptiveAnalytics(stats, isMonthly) {
    // Update the date range label based on the selected filter
    document.getElementById("date-range-label").textContent = isMonthly
      ? "Monthly Average"
      : "Daily Average";

    // Update the average, min, and max values
    document.getElementById("average-value").textContent =
      stats[selectedType].average !== null
        ? stats[selectedType].average.toFixed(2)
        : "-";
    document.getElementById("min-value").textContent =
      stats[selectedType].min !== null
        ? stats[selectedType].min.toFixed(2)
        : "-";
    document.getElementById("max-value").textContent =
      stats[selectedType].max !== null
        ? stats[selectedType].max.toFixed(2)
        : "-";
  }
});
