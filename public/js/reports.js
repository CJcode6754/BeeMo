document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("myChart").getContext("2d");
  const harvestDropdown = document.getElementById("harvestCycleList");
  const harvestCycleButton = document.getElementById("harvestCycleDropdown");
  const userHarvestDropdown = document.getElementById("userHarvestCycleList");
  const userHarvestCycleButton = document.getElementById(
    "userHarvestCycleDropdown"
  );
  const filterButton = document.getElementById("monthlyFilter");
  const userFilterButton = document.getElementById("userMonthlyFilter");
  const defaultAdminText = "Admin Cycle";
  const defaultUserText = "Worker Cycle";
  let selectedType = "temperature"; // Default type
  let time_unit = "day"; // Default time unit for daily average
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
              day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy",
            },
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
              yMax: 36,
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
              yMin: 36,
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

  // Global variables to track current state
  let selectedCycleID = null;
  let userSelectedCycleID = null;
  let currentView = null;

  // Constants for default text
  const DEFAULT_ADMIN_TEXT = "Admin Cycle";
  const DEFAULT_WORKER_TEXT = "Worker Cycle";

  // Function to reset dropdowns
  function resetDropdown(button, defaultText) {
    button.textContent = defaultText;
  }

  // Admin cycles fetch and setup
  fetch("/getCycles.php")
    .then((response) => response.json())
    .then((data) => {
      let currentCycle = null;
      const currentDate = new Date();

      // Find the current cycle and populate dropdown
      data.forEach((cycle) => {
        const cycleEndDate = new Date(cycle.end_of_cycle);
        if (
          currentCycle === null ||
          cycleEndDate > new Date(currentCycle.end_of_cycle)
        ) {
          currentCycle = cycle;
        }

        const li = document.createElement("li");
        li.classList.add("dropdown-item");
        li.textContent = `Cycle ${cycle.cycle_number}`;
        li.dataset.id = cycle.id;
        li.dataset.start = cycle.start_of_cycle;
        li.dataset.end = cycle.end_of_cycle;
        harvestDropdown.appendChild(li);
      });

      // Set default cycle
      if (currentCycle) {
        currentView = "admin";
        selectedCycleID = currentCycle.id;

        const startDate = new Date(currentCycle.start_of_cycle);
        const endDate = new Date(currentCycle.end_of_cycle);

        harvestCycleButton.textContent = `Cycle ${currentCycle.cycle_number}`;

        // Set initial data
        if (endDate < currentDate) {
          const lastMonth = new Date(endDate);
          fetchDataForMonth(
            lastMonth.getMonth() + 1,
            lastMonth.getFullYear(),
            startDate,
            endDate,
            currentCycle.id
          );
        } else {
          fetchDataForMonth(
            currentDate.getMonth() + 1,
            currentDate.getFullYear(),
            startDate,
            endDate,
            currentCycle.id
          );
        }

        populateMonthDropdown(startDate, endDate, currentCycle.id);
      }
    })
    .catch((error) => console.error("Error fetching harvest cycles:", error));

  // Worker cycles fetch and setup
  fetch("/getUserCycles.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((cycle) => {
        const li = document.createElement("li");
        li.classList.add("dropdown-item");
        li.textContent = `${cycle.user_name} - Cycle ${cycle.userCycleNumber}`;
        li.dataset.id = cycle.userCycleID;
        li.dataset.start = cycle.user_start_of_cycle;
        li.dataset.end = cycle.user_end_of_cycle;
        userHarvestDropdown.appendChild(li);
      });

      userHarvestCycleButton.textContent = DEFAULT_WORKER_TEXT;
    })
    .catch((error) => console.error("Error fetching user cycles:", error));

  // Admin cycle selection handler
  harvestDropdown.addEventListener("click", function (event) {
    if (event.target && event.target.matches("li.dropdown-item")) {
      currentView = "admin";
      userSelectedCycleID = null; // Reset worker selection
      resetDropdown(userHarvestCycleButton, DEFAULT_WORKER_TEXT);

      const startDate = new Date(event.target.dataset.start);
      const endDate = new Date(event.target.dataset.end);
      selectedCycleID = event.target.dataset.id;

      harvestCycleButton.textContent = event.target.textContent;

      // Fetch appropriate data
      const currentDate = new Date();
      if (endDate < currentDate) {
        const lastMonth = new Date(endDate);
        fetchDataForMonth(
          lastMonth.getMonth() + 1,
          lastMonth.getFullYear(),
          startDate,
          endDate,
          selectedCycleID
        );
      } else {
        fetchDataForMonth(
          currentDate.getMonth() + 1,
          currentDate.getFullYear(),
          startDate,
          endDate,
          selectedCycleID
        );
      }

      populateMonthDropdown(startDate, endDate, selectedCycleID);
    }
  });

  // Worker cycle selection handler
  userHarvestDropdown.addEventListener("click", function (event) {
    if (event.target && event.target.matches("li.dropdown-item")) {
      currentView = "worker";
      selectedCycleID = null; // Reset admin selection
      resetDropdown(harvestCycleButton, DEFAULT_ADMIN_TEXT);

      const startDate = new Date(event.target.dataset.start);
      const endDate = new Date(event.target.dataset.end);
      userSelectedCycleID = event.target.dataset.id;

      userHarvestCycleButton.textContent = event.target.textContent;

      // Fetch appropriate data
      const currentDate = new Date();
      if (endDate < currentDate) {
        const lastMonth = new Date(endDate);
        fetchUserDataForMonth(
          lastMonth.getMonth() + 1,
          lastMonth.getFullYear(),
          startDate,
          endDate,
          userSelectedCycleID
        );
      } else {
        fetchUserDataForMonth(
          currentDate.getMonth() + 1,
          currentDate.getFullYear(),
          startDate,
          endDate,
          userSelectedCycleID
        );
      }

      populateUserMonthDropdown(startDate, endDate, userSelectedCycleID);
    }
  });

  // Type selection handler (temperature, humidity, weight)
  document.querySelectorAll(".btn-label").forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      selectedType = this.dataset.type;

      // Update button styles
      document.querySelectorAll(".btn-label").forEach((btn) => {
        btn.classList.remove("label-current");
      });
      this.classList.add("label-current");

      // Update chart
      myChart.options.scales.x.time.unit = "day";
      myChart.update();

      // Refresh data based on current view
      if (currentView === "worker" && userSelectedCycleID) {
        const selectedItem = userHarvestDropdown.querySelector(
          `li[data-id="${userSelectedCycleID}"]`
        );
        if (selectedItem) {
          const startDate = new Date(selectedItem.dataset.start);
          const endDate = new Date(selectedItem.dataset.end);
          const currentDate = new Date();

          if (endDate < currentDate) {
            const lastMonth = new Date(endDate);
            fetchUserDataForMonth(
              lastMonth.getMonth() + 1,
              lastMonth.getFullYear(),
              startDate,
              endDate,
              userSelectedCycleID
            );
          } else {
            fetchUserDataForMonth(
              currentDate.getMonth() + 1,
              currentDate.getFullYear(),
              startDate,
              endDate,
              userSelectedCycleID
            );
          }
        }
      } else if (currentView === "admin" && selectedCycleID) {
        const selectedItem = harvestDropdown.querySelector(
          `li[data-id="${selectedCycleID}"]`
        );
        if (selectedItem) {
          const startDate = new Date(selectedItem.dataset.start);
          const endDate = new Date(selectedItem.dataset.end);
          const currentDate = new Date();

          if (endDate < currentDate) {
            const lastMonth = new Date(endDate);
            fetchDataForMonth(
              lastMonth.getMonth() + 1,
              lastMonth.getFullYear(),
              startDate,
              endDate,
              selectedCycleID
            );
          } else {
            fetchDataForMonth(
              currentDate.getMonth() + 1,
              currentDate.getFullYear(),
              startDate,
              endDate,
              selectedCycleID
            );
          }
        }
      }
    });
  });

  // Function to populate month dropdown
  function populateMonthDropdown(startOfCycle, endOfCycle, cycleID) {
    const monthDropdown = document.getElementById("monthDropdown");
    monthDropdown.innerHTML = ""; // Clear previous options

    const months = [];
    const startYear = startOfCycle.getFullYear();
    const endYear = endOfCycle.getFullYear();

    // Loop through years and months between start and end dates
    for (let year = startYear; year <= endYear; year++) {
      const startMonth = year === startYear ? startOfCycle.getMonth() : 0;
      const endMonth = year === endYear ? endOfCycle.getMonth() : 11;

      for (let m = startMonth; m <= endMonth; m++) {
        months.push(new Date(year, m, 1));
      }
    }

    months.forEach((month) => {
      const li = document.createElement("li");
      li.classList.add("dropdown-item");
      li.textContent = month.toLocaleString("default", { month: "long" });
      li.dataset.month = month.getMonth() + 1;
      li.dataset.year = month.getFullYear();
      li.dataset.start = startOfCycle.toISOString().split("T")[0];
      li.dataset.end = endOfCycle.toISOString().split("T")[0];
      li.dataset.cycleId = cycleID;
      monthDropdown.appendChild(li);
    });

    // Add "Full Cycle" option at the bottom
    const fullCycleOption = document.createElement("li");
    fullCycleOption.classList.add("dropdown-item");
    fullCycleOption.textContent = "Full Cycle";
    fullCycleOption.dataset.fullCycle = true;
    fullCycleOption.dataset.start = startOfCycle.toISOString().split("T")[0];
    fullCycleOption.dataset.end = endOfCycle.toISOString().split("T")[0];
    fullCycleOption.dataset.cycleId = cycleID;
    monthDropdown.appendChild(fullCycleOption);
  }

  // Fetch data for the selected month
  function fetchDataForMonth(
    month,
    year,
    cycleStartDate,
    cycleEndDate,
    selectedCycleID
  ) {
    const cycleStart = new Date(cycleStartDate);
    const cycleEnd = new Date(cycleEndDate);

    // Create the start and end dates for the selected month
    let startOfMonth = new Date(Date.UTC(year, month - 1, 1));
    let endOfMonth = new Date(Date.UTC(year, month, 0));

    const cycleStartTimestamp = cycleStart.getTime();
    const cycleEndTimestamp = cycleEnd.getTime();
    const startOfMonthTimestamp = startOfMonth.getTime();
    const endOfMonthTimestamp = endOfMonth.getTime();

    // Adjust the start and end dates based on the cycle's range
    const adjustedStart =
      startOfMonthTimestamp < cycleStartTimestamp ? cycleStart : startOfMonth;
    const adjustedEnd =
      endOfMonthTimestamp > cycleEndTimestamp ? cycleEnd : endOfMonth;

    // Ensure the adjusted start is not after the adjusted end
    if (adjustedStart.getTime() > adjustedEnd.getTime()) {
      console.error("Selected month is outside the cycle's range");
      return;
    }

    const requestData = {
      type: selectedType,
      time_unit: "month",
      start_date: adjustedStart.toISOString().split("T")[0],
      end_date: adjustedEnd.toISOString().split("T")[0],
      month,
      cycle_id: selectedCycleID,
    };

    fetch("/reportsData.php", {
      method: "POST",
      body: JSON.stringify(requestData),
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.text())
      .then((text) => {
        if (!text || text.trim() === "") {
          console.error("Received empty response from the server");
          return;
        }

        try {
          const result = JSON.parse(text);
          const labels = result.data.map((row) => row.period);
          const data = result.data.map((row) => row[`avg_${selectedType}`]);

          // Set the full cycle flag first
          isFullCycle = false;

          // Update chart options before setting data
          myChart.options.scales.x.time.tooltipFormat = isFullCycle
            ? "MMMMMMMM yyyy"
            : "MMMMMMMM d, yyyy";
          myChart.options.scales.x.time.displayFormats = {
            day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy",
          };

          // Update chart data
          myChart.data.labels = labels;
          myChart.data.datasets[0].data = data;
          myChart.data.datasets[0].label =
            selectedType.charAt(0).toUpperCase() + selectedType.slice(1);

          // Single update after all changes
          myChart.update();

          updateDescriptiveAnalytics(
            result.stats,
            true,
            false,
            result.insights
          );
        } catch (error) {
          console.error("Error parsing JSON:", error);
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  }

  function fetchFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID) {
    const requestData = {
      type: selectedType,
      time_unit: "day",
      start_date: cycleStartDate.toISOString().split("T")[0],
      end_date: cycleEndDate.toISOString().split("T")[0],
      cycle_id: selectedCycleID,
    };

    fetch("/reportsData.php", {
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
        const monthlyData = aggregateDataToMonthly(
          data.data,
          cycleStartDate,
          cycleEndDate
        );

        let dataset;
        // Only include weight data if selectedType is 'weight'
        if (selectedType === "weight") {
          const startCycleWeight = findWeightForExactDate(
            data.data,
            cycleStartDate
          );
          const endCycleWeight = findWeightForExactDate(
            data.data,
            cycleEndDate
          );

          dataset = [
            startCycleWeight,
            ...monthlyData
              .slice(1, monthlyData.length - 1)
              .map((row) => row.avg_weight),
            endCycleWeight,
          ];
        } else {
          // For temperature or humidity, use the average values directly
          const metricKey =
            selectedType === "temperature" ? "avg_temperature" : "avg_humidity";
          dataset = monthlyData.map((row) => row[metricKey]);
        }

        // Labels: Include all months for temperature/humidity, or exact dates for weight
        const labels =
          selectedType === "weight"
            ? [
                cycleStartDate.toISOString().split("T")[0],
                ...monthlyData
                  .slice(1, monthlyData.length - 1)
                  .map((row) => row.month),
                `${cycleEndDate.getFullYear()}-${String(
                  cycleEndDate.getMonth() + 1
                ).padStart(2, "0")}`,
              ]
            : monthlyData.map((row) => row.month);

        // Update the chart with the correct data
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = dataset;

        // Set the time unit to 'month' for the x-axis
        myChart.options.scales.x.time.unit = "month";
        myChart.update();

        isFullCycle = true;
        myChart.options.scales.x.time.tooltipFormat = isFullCycle
          ? "MMMMMMMM yyyy"
          : "MMMMMMMM d, yyyy";
        myChart.options.scales.x.time.displayFormats = {
          day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy",
        };

        updateFullCycleDescriptiveAnalytics(
          data.stats,
          false,
          true,
          data.fullInsights
        );
      })
      .catch((error) => {
        console.error("Error fetching full cycle data:", error);
      });
  }

  // Function to find the weight on the exact start or end date
  function findWeightForExactDate(dailyData, targetDate) {
    const targetDateObj = new Date(targetDate);

    const targetDayData = dailyData.find((day) => {
      const dayDate = new Date(day.period);
      return (
        dayDate.toISOString().split("T")[0] ===
        targetDateObj.toISOString().split("T")[0]
      );
    });

    return targetDayData ? parseFloat(targetDayData.avg_weight) : 0;
  }

  function aggregateDataToMonthly(dailyData, cycleStartDate, cycleEndDate) {
    const monthlyData = [];
    const endDate = new Date(cycleEndDate);
    const startDate = new Date(cycleStartDate);

    // Group data by month
    const monthGroups = {};

    dailyData.forEach((day) => {
      const date = new Date(day.period);

      if (date >= startDate && date <= endDate) {
        const monthKey = `${date.getFullYear()}-${String(
          date.getMonth() + 1
        ).padStart(2, "0")}`;

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

    Object.keys(monthGroups).forEach((monthKey) => {
      const monthData = monthGroups[monthKey];

      monthlyData.push({
        month: monthKey,
        avg_temperature: monthData.temperature / monthData.count,
        avg_humidity: monthData.humidity / monthData.count,
        avg_weight: monthData.weight / monthData.count,
      });
    });

    monthlyData.sort(
      (a, b) => new Date(a.month + "-01") - new Date(b.month + "-01")
    );

    return monthlyData;
  }

  function resetMonthDropdown() {
    const monthDropdown = document.getElementById("monthDropdown");
    monthDropdown.innerHTML = "";
    filterButton.textContent = "Admin Filter";
  }

  function resetUserMonthDropdown() {
    const monthDropdown = document.getElementById("userMonthDropdown");
    monthDropdown.innerHTML = "";
    userFilterButton.textContent = "Worker Filter";
  }

  // Event listener to reset month dropdown when worker cycle is clicked
  const workerCycleDropdown = document.getElementById(
    "userHarvestCycleDropdown"
  );
  workerCycleDropdown.addEventListener("click", () => {
    resetUserMonthDropdown();
  });

  const adminCycleDropdown = document.getElementById("harvestCycleDropdown");
  adminCycleDropdown.addEventListener("click", () => {
    resetMonthDropdown();
  });

  // Admin month dropdown event listener
  document
    .getElementById("monthDropdown")
    .addEventListener("click", function (event) {
      if (event.target && event.target.matches("li.dropdown-item")) {
        const selectedCycleID = parseInt(event.target.dataset.cycleId);
        const cycleStartDate = new Date(event.target.dataset.start);
        const cycleEndDate = new Date(event.target.dataset.end);

        if (event.target.dataset.fullCycle) {
          // Full cycle option selected
          filterButton.textContent = "Full Cycle";
          fetchFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID);
        } else {
          // Specific month selected
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
            selectedCycleID
          );
        }
      }
    });

  function populateUserMonthDropdown(startOfCycle, endOfCycle, cycleID) {
    const userMonthDropdown = document.getElementById("userMonthDropdown");
    userMonthDropdown.innerHTML = "";

    const months = [];
    const startYear = startOfCycle.getFullYear();
    const endYear = endOfCycle.getFullYear();

    // Loop through years and months between start and end dates
    for (let year = startYear; year <= endYear; year++) {
      const startMonth = year === startYear ? startOfCycle.getMonth() : 0;
      const endMonth = year === endYear ? endOfCycle.getMonth() : 11;

      for (let m = startMonth; m <= endMonth; m++) {
        months.push(new Date(year, m, 1));
      }
    }

    months.forEach((month) => {
      const li = document.createElement("li");
      li.classList.add("dropdown-item");
      li.textContent = month.toLocaleString("default", { month: "long" });
      li.dataset.month = month.getMonth() + 1;
      li.dataset.year = month.getFullYear();
      li.dataset.start = startOfCycle.toISOString().split("T")[0];
      li.dataset.end = endOfCycle.toISOString().split("T")[0];
      li.dataset.cycleId = cycleID;
      userMonthDropdown.appendChild(li);
    });

    // Add "Full Cycle" option at the bottom
    const fullCycleOption = document.createElement("li");
    fullCycleOption.classList.add("dropdown-item");
    fullCycleOption.textContent = "Full Cycle";
    fullCycleOption.dataset.fullCycle = true;
    fullCycleOption.dataset.start = startOfCycle.toISOString().split("T")[0];
    fullCycleOption.dataset.end = endOfCycle.toISOString().split("T")[0];
    fullCycleOption.dataset.cycleId = cycleID;
    userMonthDropdown.appendChild(fullCycleOption);
  }

  function fetchUserDataForMonth(
    month,
    year,
    cycleStartDate,
    cycleEndDate,
    userSelectedCycleID
  ) {
    const cycleStart = new Date(cycleStartDate);
    const cycleEnd = new Date(cycleEndDate);

    // Create the start and end dates for the selected month
    let startOfMonth = new Date(Date.UTC(year, month - 1, 1));
    let endOfMonth = new Date(Date.UTC(year, month, 0));

    const cycleStartTimestamp = cycleStart.getTime();
    const cycleEndTimestamp = cycleEnd.getTime();
    const startOfMonthTimestamp = startOfMonth.getTime();
    const endOfMonthTimestamp = endOfMonth.getTime();

    // Adjust the start and end dates based on the cycle's range
    const adjustedStart =
      startOfMonthTimestamp < cycleStartTimestamp ? cycleStart : startOfMonth;
    const adjustedEnd =
      endOfMonthTimestamp > cycleEndTimestamp ? cycleEnd : endOfMonth;

    // Ensure the adjusted start is not after the adjusted end
    if (adjustedStart.getTime() > adjustedEnd.getTime()) {
      console.error("Selected month is outside the cycle's range");
      return;
    }

    const requestData = {
      type: selectedType,
      time_unit: "month",
      start_date: adjustedStart.toISOString().split("T")[0],
      end_date: adjustedEnd.toISOString().split("T")[0],
      month,
      cycle_id: userSelectedCycleID,
    };

    fetch("./src/usersReportData.php", {
      method: "POST",
      body: JSON.stringify(requestData),
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.text())
      .then((text) => {
        if (!text || text.trim() === "") {
          console.error("Received empty response from the server");
          return;
        }

        try {
          const result = JSON.parse(text);
          const labels = result.data.map((row) => row.period);
          const data = result.data.map((row) => row[`avg_${selectedType}`]);

          // Set the full cycle flag first
          isFullCycle = false;

          // Update chart options before setting data
          myChart.options.scales.x.time.tooltipFormat = isFullCycle
            ? "MMMMMMMM yyyy"
            : "MMMMMMMM d, yyyy";
          myChart.options.scales.x.time.displayFormats = {
            day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy",
          };

          // Update chart data
          myChart.data.labels = labels;
          myChart.data.datasets[0].data = data;
          myChart.data.datasets[0].label =
            selectedType.charAt(0).toUpperCase() + selectedType.slice(1);

          // Single update after all changes
          myChart.update();

          updateDescriptiveAnalytics(
            result.stats,
            true,
            false,
            result.insights
          );
        } catch (error) {
          console.error("Error parsing JSON:", error);
          console.error("Server response:", text);
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  }

  function fetchUserFullCycleData(
    cycleStartDate,
    cycleEndDate,
    selectedCycleID
  ) {
    const requestData = {
      type: selectedType,
      time_unit: "day",
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
        // Aggregate daily data into monthly intervals
        const monthlyData = aggregateDataToMonthly(
          data.data,
          cycleStartDate,
          cycleEndDate
        );

        let dataset;
        // Only include weight data if selectedType is 'weight'
        if (selectedType === "weight") {
          const startCycleWeight = findWeightForExactDate(
            data.data,
            cycleStartDate
          );
          const endCycleWeight = findWeightForExactDate(
            data.data,
            cycleEndDate
          );

          dataset = [
            startCycleWeight,
            ...monthlyData
              .slice(1, monthlyData.length - 1)
              .map((row) => row.avg_weight),
            endCycleWeight,
          ];
        } else {
          // For temperature or humidity, use the average values directly
          const metricKey =
            selectedType === "temperature" ? "avg_temperature" : "avg_humidity";
          dataset = monthlyData.map((row) => row[metricKey]);
        }

        // Labels: Include all months for temperature/humidity, or exact dates for weight
        const labels =
          selectedType === "weight"
            ? [
                cycleStartDate.toISOString().split("T")[0],
                ...monthlyData
                  .slice(1, monthlyData.length - 1)
                  .map((row) => row.month),
                `${cycleEndDate.getFullYear()}-${String(
                  cycleEndDate.getMonth() + 1
                ).padStart(2, "0")}`,
              ]
            : monthlyData.map((row) => row.month);

        // Update the chart with the correct data
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = dataset;

        isFullCycle = true;
        myChart.options.scales.x.time.tooltipFormat = isFullCycle
          ? "MMMMMMMM yyyy"
          : "MMMMMMMM d, yyyy";
        myChart.options.scales.x.time.displayFormats = {
          day: isFullCycle ? "MMMMMMMM yyyy" : "MMM d, yyyy",
        };

        // Set the time unit to 'month' for the x-axis
        myChart.options.scales.x.time.unit = "month";
        myChart.update();

        updateFullCycleDescriptiveAnalytics(
          data.stats,
          false,
          true,
          data.fullInsights
        );
      })
      .catch((error) => {
        console.error("Error fetching full cycle data:", error);
      });
  }

  // Worker month dropdown event listener
  document
    .getElementById("userMonthDropdown")
    .addEventListener("click", function (event) {
      if (event.target && event.target.matches("li.dropdown-item")) {
        const selectedCycleID = parseInt(event.target.dataset.cycleId);
        const cycleStartDate = new Date(event.target.dataset.start);
        const cycleEndDate = new Date(event.target.dataset.end);

        if (event.target.dataset.fullCycle) {
          // Full cycle option selected
          userFilterButton.textContent = "Full Cycle";
          fetchUserFullCycleData(cycleStartDate, cycleEndDate, selectedCycleID);
        } else {
          // Specific month selected
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
            selectedCycleID
          );
        }
      }
    });

  function updateDescriptiveAnalytics(
    stats,
    isMonthly = false,
    isFullCycle = false,
    insights = null
  ) {
    // Update the date range label based on the selected filter
    const dateRangeLabel = document.getElementById("date-range-label");
    if (isFullCycle) {
      dateRangeLabel.textContent = "Full Cycle";
    } else if (isMonthly) {
      dateRangeLabel.textContent = "Monthly";
    }

    // Define units based on the selected type
    let unit;
    switch (selectedType) {
      case "temperature":
        unit = "°C";
        break;
      case "humidity":
        unit = "%";
        break;
      case "weight":
        unit = "g";
        break;
      default:
        unit = "";
    }

    // Safe conversion function
    function safeToFixed(value, decimals = 2) {
      if (value === null || value === undefined || isNaN(value)) {
        return "-";
      }
      return Number(value).toFixed(decimals) + " " + unit;
    }

    // Update the average, min, and max values for the selected type with units
    document.getElementById("average-value").textContent = safeToFixed(
      stats[selectedType]?.average
    );
    document.getElementById("min-value").textContent = safeToFixed(
      stats[selectedType]?.min
    );
    document.getElementById("max-value").textContent = safeToFixed(
      stats[selectedType]?.max
    );

    // Update weight-specific values based on cycle type
    if (selectedType === "weight") {
      const previousWeight = isFullCycle
        ? stats.weight?.fullcycle_previous
        : stats.weight?.previous;

      const weightGain = isFullCycle
        ? stats.weight?.fullcycle_gain
        : stats.weight?.gain;

      // Show weight-specific containers
      document.getElementById("previousWeightContainer").style.display =
        "block";
      document.getElementById("weightGainContainer").style.display = "block";

      // Update weight values with safe conversion
      document.getElementById("previousWeight").textContent =
        previousWeight !== null && previousWeight !== undefined
          ? `${Number(previousWeight).toFixed(2)} g`
          : "N/A";
      document.getElementById("weightGain").textContent =
        weightGain !== null && weightGain !== undefined
          ? `${Number(weightGain).toFixed(2)} g`
          : "N/A";

      // Hide non-weight containers
      document.getElementById("avgContainer").style.display = "none";
      document.getElementById("rangeContainer").style.display = "none";
      document.getElementById("rangeContainer1").style.display = "none";
    } else {
      // Show containers for temperature and humidity
      document.getElementById("previousWeightContainer").style.display = "none";
      document.getElementById("weightGainContainer").style.display = "none";
      document.getElementById("avgContainer").style.display = "block";
      document.getElementById("rangeContainer").style.display = "block";
      document.getElementById("rangeContainer1").style.display = "block";
    }

    // Display insights if available
    if (insights) {
      displayInsights(insights, selectedType);
    }
  }

  function displayInsights(insights, selectedType) {
    document.getElementById("insights-container").innerHTML = "";
    document.getElementById("fullcycle_insights").innerHTML = "";
    const insightsContainer = document.getElementById("insights-container");
    if (!insightsContainer) {
      console.warn("Insights container not found");
      return;
    }

    // Get relevant insights based on type
    let relevantInsights = [];

    // Add type-specific insights
    if (insights[selectedType]) {
      relevantInsights = relevantInsights.concat(insights[selectedType]);
    }

    // Add overall insights if available
    if (insights.overall) {
      relevantInsights = relevantInsights.concat(insights.overall);
    }

    // Display insights if available
    if (relevantInsights.length > 0) {
      const insightsTitle = document.createElement("h6");
      insightsTitle.classList.add("mt-3", "mb-2");
      insightsContainer.appendChild(insightsTitle);

      relevantInsights.forEach((insight) => {
        const insightElement = document.createElement("p");
        insightElement.textContent = insight;
        insightElement.classList.add("small", "text-muted");
        insightsContainer.appendChild(insightElement);
      });
    }
  }

  function updateFullCycleDescriptiveAnalytics(
    stats,
    isMonthly = false,
    isFullCycle = false,
    fullInsights = null
  ) {
    // Update the date range label based on the selected filter
    const dateRangeLabel = document.getElementById("date-range-label");
    if (isFullCycle) {
      dateRangeLabel.textContent = "Full Cycle";
    } else if (isMonthly) {
      dateRangeLabel.textContent = "Monthly";
    }

    // Define units based on the selected type
    let unit;
    switch (selectedType) {
      case "temperature":
        unit = "°C";
        break;
      case "humidity":
        unit = "%";
        break;
      case "weight":
        unit = "g";
        break;
      default:
        unit = "";
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
    if (selectedType === "weight") {
      const previousWeight = isFullCycle
        ? stats.weight?.fullcycle_previous
        : stats.weight?.previous;

      const weightGain = isFullCycle
        ? stats.weight?.fullcycle_gain
        : stats.weight?.gain;

      // Show weight-specific containers
      document.getElementById("previousWeightContainer").style.display =
        "block";
      document.getElementById("weightGainContainer").style.display = "block";

      // Update weight values
      document.getElementById("previousWeight").textContent =
        previousWeight !== null ? `${previousWeight.toFixed(2)} g` : "N/A";
      document.getElementById("weightGain").textContent =
        weightGain !== null ? `${weightGain.toFixed(2)} g` : "N/A";

      // Hide non-weight containers
      document.getElementById("avgContainer").style.display = "none";
      document.getElementById("rangeContainer").style.display = "none";
      document.getElementById("rangeContainer1").style.display = "none";
    } else {
      // Show containers for temperature and humidity
      document.getElementById("previousWeightContainer").style.display = "none";
      document.getElementById("weightGainContainer").style.display = "none";
      document.getElementById("avgContainer").style.display = "block";
      document.getElementById("rangeContainer").style.display = "block";
      document.getElementById("rangeContainer1").style.display = "block";
    }

    if (fullInsights) {
      displayFullInsights(fullInsights, selectedType);
    }
  }

  function displayFullInsights(fullInsights, selectedType) {
    document.getElementById("insights-container").innerHTML = "";
    document.getElementById("fullcycle_insights").innerHTML = "";
    const fullInsightsContainer = document.getElementById("fullcycle_insights");
    if (!fullInsightsContainer) {
      console.warn("Full insights container not found");
      return;
    }

    // Clear previous insights
    fullInsightsContainer.innerHTML = "";

    // Get relevant insights based on type
    let relevantFullInsights = [];

    // Add type-specific insights for temperature, humidity, and weight
    if (fullInsights[selectedType]) {
      relevantFullInsights = relevantFullInsights.concat(
        fullInsights[selectedType]
      );
    }

    // Add full-cycle or monthly insights if available
    if (fullInsights["overall"]) {
      relevantFullInsights = relevantFullInsights.concat(
        fullInsights["overall"]
      );
    }

    // Display insights if available
    if (relevantFullInsights.length > 0) {
      const fullInsightsTitle = document.createElement("h6");
      fullInsightsTitle.classList.add("mt-3", "mb-2");
      fullInsightsContainer.appendChild(fullInsightsTitle);

      relevantFullInsights.forEach((fullInsight) => {
        const fullInsightElement = document.createElement("p");
        fullInsightElement.textContent = fullInsight;
        fullInsightElement.classList.add("small", "text-muted");
        fullInsightsContainer.appendChild(fullInsightElement);
      });
    }
  }
});
