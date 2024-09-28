<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Retrieve rainfall data from PHP variable
        let rainfallData = <?= json_encode($rainfallData) ?>;

        // Define labels for the months
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // Create Chart.js chart
        var ctx = document.getElementById('rainfallChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months, // Use months as labels
                datasets: [{
                    label: 'Rainfall Count',
                    data: rainfallData, // Use rainfall data fetched from the controller
                    backgroundColor: "rgba(0, 102, 204, 0.7)",  // Deep Blue
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Rainfall Count'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Fetch the monthly water levels data from the PHP view
    let monthlyWaterLevels = <?= json_encode($monthlyWaterLevels) ?>;
    let allMonths = [
        'January', 'February', 'March', 'April', 'May', 'June', 
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    // Initialize arrays to store counts for each water level category
    let waterLevelCounts = {
        'low': [],
        'moderate': [],
        'high': []
    };

    // Populate counts array with water level counts for each month
    allMonths.forEach(function(month) {
        if (monthlyWaterLevels.hasOwnProperty(month)) {
            for (let level in waterLevelCounts) {
                waterLevelCounts[level].push(monthlyWaterLevels[month][level] ?? 0);
            }
        } else {
            // Add 0 counts for each water level category for months without data 
            for (let level in waterLevelCounts) {
                waterLevelCounts[level].push(0);
            }
        }
    });

    // Create a Chart.js chart with dynamic data
    var ctx = document.getElementById('waterlevel').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: allMonths,
            datasets: [
                {
                    label: 'Low',
                    data: waterLevelCounts['low'],
                    backgroundColor: "rgba(255, 255, 0, 1.0)", // Yellow
                    borderColor: "rgba(255, 255, 255, 1.0)",
                    borderWidth: 1
                },
                {
                    label: 'Moderate',
                    data: waterLevelCounts['moderate'],
                    backgroundColor: "rgba(255, 165, 0, 1.0)", // Orange
                    borderColor: "rgba(255, 255, 255, 1.0)",
                    borderWidth: 1
                },
                {
                    label: 'High',
                    data: waterLevelCounts['high'],
                    backgroundColor: "rgba(255, 0, 0, 1.0)", // Red
                    borderColor: "rgba(255, 255, 255, 1.0)",
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    let monthlyWaterLevels = <?= json_encode($monthlyWaterLevels) ?>;
    let allMonths = [
        'January', 'February', 'March', 'April', 'May', 'June', 
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    // Initializing arrays to store counts for each water level
    let waterLevelCounts = {
        '1.00': [],
        '2.00': [],
        '3.00': []
    };

    // Populating counts array with water level counts for each month
    allMonths.forEach(function(month) {
        if (monthlyWaterLevels.hasOwnProperty(month)) {
            for (let level in waterLevelCounts) {
                waterLevelCounts[level].push(monthlyWaterLevels[month][level] ?? 0);
            }
        } else {
            // Adding 0 counts for each water level for months without data 
            for (let level in waterLevelCounts) {
                waterLevelCounts[level].push(0);
            }
        }
    });

    // Creating Chart.js chart with dynamic data
    var ctx = document.getElementById('waterlevel').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: allMonths,
            datasets: [
            {
                label: 'Low',
                data: waterLevelCounts['1.00'],
                backgroundColor: "rgba(255, 255, 0, 1.0)", // Yellow
                borderColor: "rgba(255, 255, 255, 1.0)" ,
                borderWidth: 1
            },
            {
                label: 'Moderate',
                data: waterLevelCounts['2.00'],
                backgroundColor: "rgba(255, 165, 0, 1.0)", // Orange
                borderColor: "rgba(255, 255, 255, 1.0)" ,
                borderWidth: 1
            },
            {
                label: 'High',
                data: waterLevelCounts['3.00'],
                backgroundColor: "rgba(255, 0, 0, 1.0)", // Red
                borderColor: "rgba(255, 255, 255, 1.0)" ,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});
</script> -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Solar Voltage Chart
        var ctxSolarVoltage = document.getElementById('solarVoltageChart').getContext('2d');
        var dailyVoltages = <?php echo json_encode($dailyVoltages); ?>;
        var labels = Object.keys(dailyVoltages);
        var data = Object.values(dailyVoltages);

        new Chart(ctxSolarVoltage, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Voltage (V)',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Voltage (V)'
                        }
                    }
                }
            }
        });
    });
</script>
