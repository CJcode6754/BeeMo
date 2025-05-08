document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("myChart").getContext("2d");
  const harvestDropdown = document.getElementById("harvestCycleList");
  const harvestCycleButton = document.getElementById("harvestCycleDropdown");
  const userHarvestDropdown = document.getElementById("userHarvestCycleList");
  const userHarvestCycleButton = document.getElementById("userHarvestCycleDropdown");
  const filterButton = document.getElementById("monthlyFilter");
  const userFilterButton = document.getElementById("userMonthlyFilter");
  const datePicker = $("#start-date-picker");
  const defaultAdminText = "Admin Cycle";
  const defaultUserText = "Worker Cycle";
  let selectedType = "temperature"; // Default type
  let time_unit = "hour"; // Default time unit for daily average
  let isFullCycle = false;
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
          fill: true,
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
            tooltipFormat: isFullCycle ? "MMMMMMMM yyyy" : "MMMMMMMMM d, yyyy",
            displayFormats: {
              day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy"
            }
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
  
  // Fetch harvest cycles from admin and populate dropdow
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
      
      datePicker.datepicker("setDate", fetchDate);

      // Reset Worker Harvest Cycle to default
      resetUserDropdown(userHarvestCycleButton, defaultUserText);

      // Fetch and display data for the determined date
      fetchDataForDate(formatDate(fetchDate));

      // Populate the month dropdown based on the cycle's date range
      populateMonthDropdown(startDate, endDate, selectedCycleID); // Pass selectedCycleID
    }
  });


    // Fetch harvest cycles from worker and populate dropdow
    fetch("./src/getUserCycles.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((cycle) => {
        const li = document.createElement("li");
        li.classList.add("dropdown-item");
        li.textContent = `Cycle ${cycle.userCycleNumber}`;
        li.dataset.id = cycle.userCycleID;
        li.dataset.start = cycle.user_start_of_cycle;
        li.dataset.end = cycle.user_end_of_cycle;
        li.dataset.cycleId = cycle.userCycleID;
        userHarvestDropdown.appendChild(li);
      });
      fetchDataForDate(formatDate(today)); // Fetch data for the current day by default
    })
    .catch((error) => console.error("Error fetching harvest cycles:", error));

  userHarvestDropdown.addEventListener("click", function (event) {
    if (event.target && event.target.matches("li.dropdown-item")) {
      const selectedCycle = event.target.textContent;
      const startDate = new Date(event.target.dataset.start);
      const endDate = new Date(event.target.dataset.end);

      // Set the selected cycle ID
      userSelectedCycleID = event.target.dataset.id; // Set the selected cycle ID

      // Set the current date
      const currentDate = new Date();

      // Determine which date to use for fetching data
      const fetchDate = currentDate > endDate ? endDate : currentDate;

      // Update button text and enable the date picker
      userHarvestCycleButton.textContent = selectedCycle;
      datePicker.prop("disabled", false);

      // Set date picker range
      datePicker.datepicker("option", "minDate", startDate);
      datePicker.datepicker("option", "maxDate", endDate);
      
      datePicker.datepicker("setDate", fetchDate);

      // Reset Admin Harvest Cycle to default
      resetDropdown(harvestCycleButton, defaultAdminText);

      // Fetch and display data for the determined date
      fetchUserDataForDate(formatDate(fetchDate));

      // // Populate the month dropdown based on the cycle's date range
      populateUserMonthDropdown(startDate, endDate, userSelectedCycleID);
    }
  });

    // Function to reset the admin dropdown when switching to the worker cycle
    function resetDropdown(dropdownButton, defaultText) {
      dropdownButton.textContent = defaultText;
      filterButton.textContent = "Admin Filter";
      
      // Reset the admin month dropdown selection
      const adminMonthDropdown = document.getElementById("monthDropdown");
      if (adminMonthDropdown) {
        adminMonthDropdown.innerHTML = ""; // Clear any selected value without removing options
      }
    
      myChart.options.scales.x.time.unit = "hour"; // Set x-axis to use hours
      myChart.update(); // Update chart with new options
    }
    
    // Function to reset the worker dropdown when switching to the admin cycle
    function resetUserDropdown(dropdownButton, defaultText) {
      dropdownButton.textContent = defaultText;
      userFilterButton.textContent = "Worker Filter";
      
      // Reset the worker month dropdown selection
      const workerMonthDropdown = document.getElementById("userMonthDropdown");
      if (workerMonthDropdown) {
        workerMonthDropdown.innerHTML = ""; // Clear any selected value without removing options
      }
    
      myChart.options.scales.x.time.unit = "hour"; // Set x-axis to use hours
      myChart.update(); // Update chart with new options
    }

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
        isFullCycle = false;
        myChart.update();
        updateDescriptiveAnalytics(result.stats, false, false, result.insights);
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

    // Function to fetch data for the selected date
    function fetchUserDataForDate(selectedDate = null) {
      const requestData = { type: selectedType, time_unit: "hour" }; // Use hourly time unit
  
      if (selectedDate) {
        requestData.selected_date = selectedDate;
      }
  
      fetch("./src/usersReportData.php", {
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

    function fetchDataForMonth(
      month,
      year,
      cycleStartDate,
      cycleEndDate,
      selectedCycleID
    ) {
      // Convert cycle dates to Date objects
      const cycleStart = new Date(cycleStartDate);
      const cycleEnd = new Date(cycleEndDate);
    
      // Ensure valid month and year
      if (isNaN(year) || month < 1 || month > 12) {
        console.error("Invalid month or year provided");
        return;
      }
    
      // Calculate the start and end of the requested month
      let startOfMonth = new Date(Date.UTC(year, month - 1, 1)); // Midnight UTC of the 1st day of the month
      let endOfMonth = new Date(Date.UTC(year, month, 0)); // Last day of the selected month in UTC
    
      // Normalize dates to timestamp for cross-year comparison
      const cycleStartTimestamp = cycleStart.getTime();
      const cycleEndTimestamp = cycleEnd.getTime();
      const startOfMonthTimestamp = startOfMonth.getTime();
      const endOfMonthTimestamp = endOfMonth.getTime();
    
      // Ensure the requested month falls within the cycle range
      const adjustedStart =
        startOfMonthTimestamp < cycleStartTimestamp ? cycleStart : startOfMonth;
      const adjustedEnd = endOfMonthTimestamp > cycleEndTimestamp ? cycleEnd : endOfMonth;
    
      // Stop if the month range does not intersect the cycle
      if (adjustedStart.getTime() > adjustedEnd.getTime()) {
        console.error("Selected month is outside the cycle's range");
        return;
      }
    
      const requestData = {
        type: selectedType,
        time_unit: "day",
        start_date: adjustedStart.toISOString().split("T")[0],
        end_date: adjustedEnd.toISOString().split("T")[0],
        month,
        cycle_id: selectedCycleID,
      };
    
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
          return response.text();
        })
        .then((text) => {
          if (!text || text.trim() === "") {
            throw new Error("Received empty response from the server");
          }
          try {
            const result = JSON.parse(text);
    
            const labels = result.data.map((row) => row.period);
            const data = result.data.map((row) => row[`avg_${selectedType}`]);
            myChart.data.labels = labels;
            myChart.data.datasets[0].data = data;
    
            myChart.data.datasets[0].label =
              selectedType.charAt(0).toUpperCase() + selectedType.slice(1);
            myChart.update();
            // isFullCycle = false;
            // myChart.options.scales.x.time.tooltipFormat = isFullCycle ? "MMMMMMMM yyyy" : "MMMMMMMM d, yyyy";
            // myChart.options.scales.x.time.displayFormats = {
            //   day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy"
            // };
            updateDescriptiveAnalytics(result.stats, true, false, result.insights);
    
            myChart.options.scales.x.time.unit = "day";
            myChart.update();
          } catch (error) {
            console.error("Error parsing JSON:", error);
            document.getElementById("error-display").textContent =
              "Server Error: " + text;
          }
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    }
    
    

  // function fetchFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID) {
  //   const requestData = {
  //     type: selectedType,
  //     time_unit: "day", // Still fetching daily data from the server
  //     start_date: cycleStartDate.toISOString().split("T")[0],
  //     end_date: cycleEndDate.toISOString().split("T")[0],
  //     cycle_id: selectedCycleID,
  //   };

  //   fetch("./src/reportsData.php", {
  //     method: "POST",
  //     body: JSON.stringify(requestData),
  //     headers: {
  //       "Content-Type": "application/json",
  //     },
  //   })
  //     .then((response) => {
  //       if (!response.ok) {
  //         throw new Error("Network response was not ok");
  //       }
  //       return response.json();
  //     })
  //     .then((data) => {
  //       // Aggregate daily data into weekly intervals
  //       const weeklyData = aggregateDataToWeekly(data.data);
  
  //       // Set weekly labels and dataset
  //       const labels = weeklyData.map((row) => row.week);
  //       const dataset = weeklyData.map((row) => row[`avg_${selectedType}`]);
  
  //       // Update the chart with weekly data
  //       myChart.data.labels = labels;
  //       myChart.data.datasets[0].data = dataset;
  //       updateDescriptiveAnalytics(data.stats, false,true,data.insights);
  //       // Set the time unit to 'week' for the x-axis
  //       myChart.options.scales.x.time.unit = "week";
  //       myChart.update();
  //     })
  //     .catch((error) => {
  //       console.error("Error fetching full cycle data:", error);
  //     });
  // }
  
  function fetchFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID) {
    const requestData = {
      type: selectedType,
      time_unit: "day", // Still fetching daily data from the server
      start_date: cycleStartDate.toISOString().split("T")[0],
      end_date: cycleEndDate.toISOString().split("T")[0],
      cycle_id: selectedCycleID,
    };

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
        return response.json();
      })
      .then((data) => {
        // Aggregate daily data into monthly intervals
        const monthlyData = aggregateDataToMonthly(data.data, cycleStartDate, cycleEndDate);
  
        // Set monthly labels and dataset
        const labels = monthlyData.map((row) => row.month);
        const dataset = monthlyData.map((row) => row[`avg_${selectedType}`]);
  
        // Update the chart with monthly data
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = dataset;
        isFullCycle = true;
        myChart.options.scales.x.time.tooltipFormat = isFullCycle ? "MMMMMMMM yyyy" : "MMMMMMMM d, yyyy";
        myChart.options.scales.x.time.displayFormats = {
          day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy"
        };
        updateFullCycleDescriptiveAnalytics(data.stats, false, true, data.fullInsights);
        // Set the time unit to 'month' for the x-axis
        myChart.options.scales.x.time.unit = "month";
        myChart.update();
      })
      .catch((error) => {
        console.error("Error fetching full cycle data:", error);
      });
  }

function aggregateDataToMonthly(dailyData, cycleStartDate, cycleEndDate) {
    const monthlyData = [];
    const endDate = new Date(cycleEndDate);
    const endMonth = endDate.getMonth();
    const endYear = endDate.getFullYear();

    // Group data by month
    const monthGroups = {};

    dailyData.forEach((day) => {
      const date = new Date(day.period);
      
      // Only process data within the cycle dates
      if (date >= new Date(cycleStartDate) && date <= new Date(cycleEndDate)) {
        const monthKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        
        if (!monthGroups[monthKey]) {
          monthGroups[monthKey] = {
            temperature: 0,
            humidity: 0,
            weight: 0,
            count: 0,
          };
        }
        
        monthGroups[monthKey].temperature += parseFloat(day.avg_temperature);
        monthGroups[monthKey].humidity += parseFloat(day.avg_humidity);
        monthGroups[monthKey].weight += parseFloat(day.avg_weight);
        monthGroups[monthKey].count += 1;
      }
    });

    // Convert grouped data to monthly data array
    Object.keys(monthGroups).forEach(monthKey => {
      const [year, month] = monthKey.split('-');
      const monthData = monthGroups[monthKey];
      
      monthlyData.push({
        month: monthKey,
        avg_temperature: monthData.temperature / monthData.count,
        avg_humidity: monthData.humidity / monthData.count,
        avg_weight: monthData.weight / monthData.count
      });
    });

    // Sort the monthly data chronologically
    monthlyData.sort((a, b) => new Date(a.month + '-01') - new Date(b.month + '-01'));

    // Ensure the last data point is for the end month of the cycle
    const lastMonth = `${endYear}-${String(endMonth + 1).padStart(2, '0')}`;
    if (!monthlyData.some(data => data.month === lastMonth)) {
      // If the last month is not in the data, add a dummy entry
      monthlyData.push({
        month: lastMonth,
        avg_temperature: null,
        avg_humidity: null,
        avg_weight: null
      });
    }

    return monthlyData;
  }

  function resetFilterToDaily() {
    const today = new Date();
    fetchDataForDate(today.toISOString().split("T")[0]); // Fetch daily data

    filterButton.textContent = "Admin Filter";
    myChart.options.scales.x.time.unit = "hour"; // Set the time unit back to 'hour'
    myChart.update();
  }

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

    const fullCycleOption = document.createElement("li");
    fullCycleOption.classList.add("dropdown-item");
    fullCycleOption.textContent = "Full Cycle";
    fullCycleOption.dataset.fullCycle = true; // Flag for full cycle selection
    fullCycleOption.dataset.start = startOfCycle.toISOString().split("T")[0]; // Add cycle start date
    fullCycleOption.dataset.end = endOfCycle.toISOString().split("T")[0]; // Add cycle end date
    fullCycleOption.dataset.cycleId = cycleID; // Set cycle ID for dropdown items
    monthDropdown.appendChild(fullCycleOption); // Add to dropdown

    const resetOption = document.createElement("li");
    resetOption.classList.add("dropdown-item");
    resetOption.textContent = "Reset to Daily";
    resetOption.addEventListener("click", () => {
      resetFilterToDaily(); // Reset to daily and update the flag
    });

    monthDropdown.appendChild(resetOption);
  }

  // Function to reset the month dropdown when switching to the worker cycle
function resetMonthDropdown() {
  const monthDropdown = document.getElementById("monthDropdown");
  monthDropdown.innerHTML = ""; // Clear previous options when worker cycle is selected
  filterButton.textContent = "Admin Filter";
}

function resetUserMonthDropdown() {
  const monthDropdown = document.getElementById("userMonthDropdown");
  userMonthDropdown.innerHTML = ""; // Clear previous options when worker cycle is selected
  userFilterButton.textContent = "Worker Filter";
}

// Event listener to reset the month dropdown when the worker cycle dropdown is clicked
const workerCycleDropdown = document.getElementById("userHarvestCycleDropdown"); // Assuming you have this element
workerCycleDropdown.addEventListener("click", () => {
  resetUserMonthDropdown();
});

  document.getElementById("monthDropdown").addEventListener("click", function (event) {
    if (event.target && event.target.matches("li.dropdown-item")) {

      if (event.target.textContent === "Reset to Daily") {
        resetFilterToDaily(); // Call the function to reset the filter
        return; // Exit early to prevent further processing
      }

        const selectedCycleID = parseInt(event.target.dataset.cycleId);
        const cycleStartDate = new Date(event.target.dataset.start);
        const cycleEndDate = new Date(event.target.dataset.end);

        if (event.target.dataset.fullCycle) {
            // Full cycle option selected
            filterButton.textContent = "Full Cycle";  // Update filter button text
            fetchFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID);  // Fetch full cycle data
        } else {
            // Specific month selected (existing daily functionality)
            const selectedMonth = parseInt(event.target.dataset.month);
            const selectedYear = parseInt(event.target.dataset.year);

            let selectedMonthName = new Intl.DateTimeFormat("default", {
                month: "long",
            }).format(new Date(selectedYear, selectedMonth - 1));

            filterButton.textContent = selectedMonthName;

            // Fetch and display data for the selected month and year
            fetchDataForMonth(
                selectedMonth,
                selectedYear,
                cycleStartDate,
                cycleEndDate,
                selectedCycleID // Pass the selected cycle ID
            );
        }
    }
});

  function resetUserFilterToDaily() {
    const today = new Date();
    fetchDataForDate(today.toISOString().split("T")[0]); // Fetch daily data
    userFilterButton.textContent = "Worker Filter";
    myChart.options.scales.x.time.unit = "hour"; // Set the time unit back to 'hour'
    myChart.update();
  }

  function populateUserMonthDropdown(startOfCycle, endOfCycle, cycleID) {
    const userMonthDropdown = document.getElementById("userMonthDropdown");
    userMonthDropdown.innerHTML = ""; // Clear previous options
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
      userMonthDropdown.appendChild(li);
    });

    const fullCycleOption = document.createElement("li");
    fullCycleOption.classList.add("dropdown-item");
    fullCycleOption.textContent = "Full Cycle";
    fullCycleOption.dataset.fullCycle = true; // Flag for full cycle selection
    fullCycleOption.dataset.start = startOfCycle.toISOString().split("T")[0]; // Add cycle start date
    fullCycleOption.dataset.end = endOfCycle.toISOString().split("T")[0]; // Add cycle end date
    fullCycleOption.dataset.cycleId = cycleID; // Set cycle ID for dropdown items
    userMonthDropdown.appendChild(fullCycleOption); // Add to dropdown

    const resetOption = document.createElement("li");
    resetOption.classList.add("dropdown-item");
    resetOption.textContent = "Reset to Daily";
    resetOption.addEventListener("click", () => {
      resetUserFilterToDaily(); // Reset to daily and update the flag
    });

    userMonthDropdown.appendChild(resetOption);
  }

    // Function to fetch data for a specific month within the cycle date range
    function fetchUserDataForMonth(
      month,
      year,
      cycleStartDate,
      cycleEndDate,
      userSelectedCycleID
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
        type: selectedType, // Ensure `selectedType` is properly set
        time_unit: "day",
        start_date: startOfMonth.toISOString().split("T")[0], // Ensure ISO format date
        end_date: endOfMonth.toISOString().split("T")[0],      // Ensure ISO format date
        month: month,
        cycle_id: userSelectedCycleID,
      };
    
      // console.log("Request data:", requestData); // Log the request data to verify it's correct
    
      fetch("./src/usersReportData.php", {
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
            if (result.error) {
              throw new Error(result.error);
            }
    
            const labels = result.data.map((row) => row.period);
            const data = result.data.map((row) => row[`avg_${selectedType}`]);
            myChart.data.labels = labels;
            myChart.data.datasets[0].data = data;
    
            myChart.data.datasets[0].label =
              selectedType.charAt(0).toUpperCase() + selectedType.slice(1);
            myChart.update();
    
            let isMonthly = true;
            updateDescriptiveAnalytics(result.stats, isMonthly);
    
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
    
  
    function fetchUserFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID) {
      const requestData = {
        type: selectedType,
        time_unit: "day", // Still fetching daily data from the server
        start_date: cycleStartDate.toISOString().split("T")[0],
        end_date: cycleEndDate.toISOString().split("T")[0],
        cycle_id: selectedCycleID,
      };

      fetch("./src/usersReportData.php", {
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
          return response.json();
        })
        .then((data) => {
          // Aggregate daily data into weekly intervals
          const weeklyData = aggregateDataToWeekly(data.data);
    
          // Set weekly labels and dataset
          const labels = weeklyData.map((row) => row.week);
          const dataset = weeklyData.map((row) => row[`avg_${selectedType}`]);
    
          // Update the chart with weekly data
          myChart.data.labels = labels;
          myChart.data.datasets[0].data = dataset;
         
          let isMonthlyFilter = true;
          updateDescriptiveAnalytics(data.stats, isMonthlyFilter);

          // Set the time unit to 'week' for the x-axis
          myChart.options.scales.x.time.unit = "week";
          myChart.update();
        })
        .catch((error) => {
          console.error("Error fetching full cycle data:", error);
        });
    }
    
    // Function to aggregate daily data into weekly
    function aggregateDataToWeekly(dailyData) {
      const weeklyData = [];
      let currentWeekStart = null;
      let currentWeekData = {
        temperature: 0,
        humidity: 0,
        weight: 0,
        count: 0,
        week: "",
      };
    
      dailyData.forEach((day) => {
        const date = new Date(day.period);
        const weekStart = getWeekStartDate(date);
    
        if (!currentWeekStart || weekStart.getTime() !== currentWeekStart.getTime()) {
          // If moving to a new week, push the accumulated data of the previous week
          if (currentWeekStart) {
            weeklyData.push({
              week: currentWeekStart.toISOString().split("T")[0],
              avg_temperature: currentWeekData.temperature / currentWeekData.count,
              avg_humidity: currentWeekData.humidity / currentWeekData.count,
              avg_weight: currentWeekData.weight / currentWeekData.count,
            });
          }
    
          // Start accumulating data for the new week
          currentWeekStart = weekStart;
          currentWeekData = {
            temperature: 0,
            humidity: 0,
            weight: 0,
            count: 0,
            week: weekStart.toISOString().split("T")[0],
          };
        }
    
        // Accumulate the data
        currentWeekData.temperature += parseFloat(day.avg_temperature);
        currentWeekData.humidity += parseFloat(day.avg_humidity);
        currentWeekData.weight += parseFloat(day.avg_weight);
        currentWeekData.count += 1;
      });
    
      // Push the last week's data
      if (currentWeekStart) {
        weeklyData.push({
          week: currentWeekStart.toISOString().split("T")[0],
          avg_temperature: currentWeekData.temperature / currentWeekData.count,
          avg_humidity: currentWeekData.humidity / currentWeekData.count,
          avg_weight: currentWeekData.weight / currentWeekData.count,
        });
      }
    
      return weeklyData;
    }
    
    // Function to get the start date of the week for a given date
    function getWeekStartDate(date) {
      const dayOfWeek = date.getDay(); // Get the day of the week (0 is Sunday, 6 is Saturday)
      const startDate = new Date(date);
      startDate.setDate(date.getDate() - dayOfWeek + 1); // Set to Monday of the current week
      startDate.setHours(0, 0, 0, 0); // Set time to the start of the day
      return startDate;
    }
  
    const adminCycleDropdown = document.getElementById("harvestCycleDropdown"); // Assuming you have this element
    adminCycleDropdown.addEventListener("click", () => {
      resetMonthDropdown();
    });
  // Event listener for month selection
  document.getElementById("userMonthDropdown").addEventListener("click", function (event) {
    if (event.target && event.target.matches("li.dropdown-item")) {
        if (event.target.textContent === "Reset to Daily") {
          resetUserFilterToDaily(); // Call the function to reset the filter
          return; // Exit early to prevent further processing
        }

        const selectedCycleID = parseInt(event.target.dataset.cycleId);
        const cycleStartDate = new Date(event.target.dataset.start);
        const cycleEndDate = new Date(event.target.dataset.end);

        if (event.target.dataset.fullCycle) {
            // Full cycle option selected
            userFilterButton.textContent = "Full Cycle";  // Update filter button text
            fetchUserFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID);  // Fetch full cycle data
        } else {
            // Specific month selected (existing daily functionality)
            const selectedMonth = parseInt(event.target.dataset.month);
            const selectedYear = parseInt(event.target.dataset.year);

            let selectedMonthName = new Intl.DateTimeFormat("default", {
                month: "long",
            }).format(new Date(selectedYear, selectedMonth - 1));

            userFilterButton.textContent = selectedMonthName;

            // Fetch and display data for the selected month and year
            fetchUserDataForMonth(
                selectedMonth,
                selectedYear,
                cycleStartDate,
                cycleEndDate,
                selectedCycleID // Pass the selected cycle ID
            );
        }
    }
});

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

      myChart.options.scales.x.time.unit = "hour";
      myChart.update();
    
      // Fetch new data for the currently selected type
      fetchDataForDate(formatDate(datePicker.datepicker("getDate"))); // Fetch data for the selected date
    });
  });

  function updateDescriptiveAnalytics(stats, isMonthly = false, isFullCycle = false, insights = null) {
    // Update the date range label based on the selected filter
    const dateRangeLabel = document.getElementById("date-range-label");
    if (isFullCycle) {
        dateRangeLabel.textContent = "Full Cycle";
    } else if (isMonthly) {
        dateRangeLabel.textContent = "Monthly";
    } else {
        dateRangeLabel.textContent = "Daily";
    }

    // Define units based on the selected type
    let unit;
    switch (selectedType) {
        case 'temperature':
            unit = '°C';
            break;
        case 'humidity':
            unit = '%';
            break;
        case 'weight':
            unit = 'g';
            break;
        default:
            unit = '';
    }

    // Update the average, min, and max values for the selected type with units
    document.getElementById("average-value").textContent =
        stats[selectedType]?.average !== null
            ? `${stats[selectedType].average.toFixed(2)} ${unit}`
            : "-";
    document.getElementById("min-value").textContent =
        stats[selectedType]?.min !== null
            ? `${stats[selectedType].min.toFixed(2)} ${unit}`
            : "-";
    document.getElementById("max-value").textContent =
        stats[selectedType]?.max !== null
            ? `${stats[selectedType].max.toFixed(2)} ${unit}`
            : "-";

    // Update weight-specific values based on cycle type
    if (selectedType === 'weight') {
        const previousWeight = isFullCycle 
            ? stats.weight?.fullcycle_previous 
            : stats.weight?.previous;
        
        const weightGain = isFullCycle 
            ? stats.weight?.fullcycle_gain 
            : stats.weight?.gain;

        // Show weight-specific containers
        document.getElementById('previousWeightContainer').style.display = 'block';
        document.getElementById('weightGainContainer').style.display = 'block';
        
        // Update weight values
        document.getElementById('previousWeight').textContent = 
            previousWeight !== null ? `${previousWeight.toFixed(2)} g` : 'N/A';
        document.getElementById('weightGain').textContent = 
            weightGain !== null ? `${weightGain.toFixed(2)} g` : 'N/A';
            
        // Hide non-weight containers
        document.getElementById('avgContainer').style.display = 'none';
        document.getElementById('rangeContainer').style.display = 'none';
        document.getElementById('rangeContainer1').style.display = 'none';
    } else {
        // Show containers for temperature and humidity
        document.getElementById('previousWeightContainer').style.display = 'none';
        document.getElementById('weightGainContainer').style.display = 'none';
        document.getElementById('avgContainer').style.display = 'block';
        document.getElementById('rangeContainer').style.display = 'block';
        document.getElementById('rangeContainer1').style.display = 'block';
    }

    // Display insights if available
    if (insights) {
        displayInsights(insights, selectedType);
    }
}


function updateFullCycleDescriptiveAnalytics(stats, isMonthly = false, isFullCycle = false, fullInsights = null) {
  // Update the date range label based on the selected filter
  const dateRangeLabel = document.getElementById("date-range-label");
  if (isFullCycle) {
      dateRangeLabel.textContent = "Full Cycle";
  } else if (isMonthly) {
      dateRangeLabel.textContent = "Monthly";
  } else {
      dateRangeLabel.textContent = "Daily";
  }

  // Define units based on the selected type
  let unit;
  switch (selectedType) {
      case 'temperature':
          unit = '°C';
          break;
      case 'humidity':
          unit = '%';
          break;
      case 'weight':
          unit = 'g';
          break;
      default:
          unit = '';
  }

  // Update the average, min, and max values for the selected type with units
  document.getElementById("average-value").textContent =
      stats[selectedType]?.average !== null
          ? `${stats[selectedType].average.toFixed(2)} ${unit}`
          : "-";
  document.getElementById("min-value").textContent =
      stats[selectedType]?.min !== null
          ? `${stats[selectedType].min.toFixed(2)} ${unit}`
          : "-";
  document.getElementById("max-value").textContent =
      stats[selectedType]?.max !== null
          ? `${stats[selectedType].max.toFixed(2)} ${unit}`
          : "-";

  // Update weight-specific values based on cycle type
  if (selectedType === 'weight') {
      const previousWeight = isFullCycle 
          ? stats.weight?.fullcycle_previous 
          : stats.weight?.previous;
      
      const weightGain = isFullCycle 
          ? stats.weight?.fullcycle_gain 
          : stats.weight?.gain;

      // Show weight-specific containers
      document.getElementById('previousWeightContainer').style.display = 'block';
      document.getElementById('weightGainContainer').style.display = 'block';
      
      // Update weight values
      document.getElementById('previousWeight').textContent = 
          previousWeight !== null ? `${previousWeight.toFixed(2)} g` : 'N/A';
      document.getElementById('weightGain').textContent = 
          weightGain !== null ? `${weightGain.toFixed(2)} g` : 'N/A';
          
      // Hide non-weight containers
      document.getElementById('avgContainer').style.display = 'none';
      document.getElementById('rangeContainer').style.display = 'none';
      document.getElementById('rangeContainer1').style.display = 'none';
  } else {
      // Show containers for temperature and humidity
      document.getElementById('previousWeightContainer').style.display = 'none';
      document.getElementById('weightGainContainer').style.display = 'none';
      document.getElementById('avgContainer').style.display = 'block';
      document.getElementById('rangeContainer').style.display = 'block';
      document.getElementById('rangeContainer1').style.display = 'block';
  }

  if(fullInsights){
    displayFullInsights(fullInsights, selectedType);
  }
}

function displayInsights(insights, selectedType) {
    const insightsContainer = document.getElementById('insights-container');
    if (!insightsContainer) {
        console.warn('Insights container not found');
        return;
    }

    // Clear previous insights
    insightsContainer.innerHTML = '';

    // Get relevant insights based on type
    let relevantInsights = [];
    
    // Add type-specific insights for temperature, humidity, and weight
    if (insights[selectedType]) {
        relevantInsights = relevantInsights.concat(insights[selectedType]);
    }

    // Add full-cycle or monthly insights if available
    if (insights['overall']) {
        relevantInsights = relevantInsights.concat(insights['overall']);
    }

    // Display insights if available
    if (relevantInsights.length > 0) {
        const insightsTitle = document.createElement('h6');
        insightsTitle.classList.add('mt-3', 'mb-2');
        insightsContainer.appendChild(insightsTitle);

        relevantInsights.forEach(insight => {
            const insightElement = document.createElement('p');
            insightElement.textContent = insight;
            insightElement.classList.add('small', 'text-muted');
            insightsContainer.appendChild(insightElement);
        });
    }
}

function displayFullInsights(fullInsights, selectedType) {
  const fullInsightsContainer = document.getElementById('fullcycle_insights');
  if (!fullInsightsContainer) {
      console.warn('Insights container not found');
      return;
  }

  // Clear previous insights
  fullInsightsContainer.innerHTML = '';

  // Get relevant insights based on type
  let relevantFullInsights = [];
  
  // Add type-specific insights for temperature, humidity, and weight
  if (fullInsights[selectedType]) {
    relevantFullInsights = relevantFullInsights.concat(fullInsights[selectedType]);
  }

  // Add full-cycle or monthly insights if available
  if (fullInsights['overall']) {
    relevantFullInsights = relevantFullInsights.concat(fullInsights['overall']);
  }

  // Display insights if available
  if (relevantFullInsights.length > 0) {
      const fullInsightsTitle = document.createElement('h6');
      fullInsightsTitle.classList.add('mt-3', 'mb-2');
      fullInsightsContainer.appendChild(fullInsightsTitle);

      relevantFullInsights.forEach(fullInsights => {
          const fullInsightElement = document.createElement('p');
          fullInsightElement.textContent = fullInsights;
          fullInsightElement.classList.add('small', 'text-muted');
          fullInsightsContainer.appendChild(fullInsightElement);
      });
  }
}

});
