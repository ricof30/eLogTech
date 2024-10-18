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


<!-- Highchart js code -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Retrieve rainfall data from PHP variable
    let rainfallData = <?= json_encode($rainfallData) ?>;

    // Define labels for the months
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    // Highcharts for Rainfall
    Highcharts.chart('rainfallChart', {
        chart: {
            type: 'column', // Bar chart in Highcharts is called 'column'
            backgroundColor: '#EEF7FF' // Set background color to bg-secondary color
        },
        title: {
            text: 'Rainfall Chart',
            style: {
                color: '#000000' // Set title color to white
            }
        },
        xAxis: {
            categories: months, // Use months as categories
            title: {
                text: 'Month',
                style: {
                    color: '#000000' // Set axis title color to white
                }
            },
            labels: {
                style: {
                    color: '#000000' // Set axis labels color to white
                }
            }
        },
        yAxis: {
            min: 0,
            labels: {
                style: {
                    color: '#000000' // Set y-axis labels color to white
                }
            }
        },
        series: [{
            name: 'Rainfall Count',
            data: rainfallData, // Use the fetched rainfall data
            color: 'rgba(0, 102, 204, 0.7)' // Set color for bars
        }],
        tooltip: {
            valueSuffix: ' mm' // Adjust based on the unit of your rainfall data
        },
        plotOptions: {
            column: {
                borderRadius: 5,
                pointPadding: 0.2,
                borderWidth: 1
            }
        },
        exporting: {
            enabled: false // Disable the export options (PDF, Excel, etc.)
        },
        credits: {
            enabled: false // Disable the "highcharts.com" credits text
        },
        legend: {
            itemStyle: {
                color: '#000000' // Set legend series names to white
            }
        }
    });

    // Fetch the monthly water levels data from the PHP view
    let monthlyWaterLevels = <?= json_encode($monthlyWaterLevels) ?>;

    // Initialize arrays to store counts for each water level category
    let waterLevelCounts = {
        'low': [],
        'moderate': [],
        'high': []
    };

    // Populate counts array with water level counts for each month
    months.forEach(function(month) {
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

    // Highcharts for Water Level
    Highcharts.chart('waterlevel', {
        chart: {
            type: 'column', // Bar chart in Highcharts is called 'column'
            backgroundColor: '#EEF7FF' // Set background color to bg-secondary color
        },
        title: {
            text: 'Water Level Chart',
            style: {
                color: '#000000' // Set title color to white
            }
        },
        xAxis: {
            categories: months, // Use months as categories
            title: {
                text: 'Month',
                style: {
                    color: '#000000' // Set x-axis title color to white
                }
            },
            labels: {
                style: {
                    color: '#000000' // Set x-axis labels color to white
                }
            }
        },
        yAxis: {
            min: 0,
            labels: {
                style: {
                    color: '#000000' // Set y-axis labels color to white
                }
            }
        },
        series: [
            {
                name: 'Low',
                data: waterLevelCounts['low'],
                color: 'rgba(255, 255, 0, 1.0)' // Yellow
            },
            {
                name: 'Moderate',
                data: waterLevelCounts['moderate'],
                color: 'rgba(255, 165, 0, 1.0)' // Orange
            },
            {
                name: 'High',
                data: waterLevelCounts['high'],
                color: 'rgba(255, 0, 0, 1.0)' // Red
            }
        ],
        tooltip: {
            shared: true,
            valueSuffix: ' readings' // Adjust this based on the unit of water level data
        },
        plotOptions: {
            column: {
                stacking: 'normal', // Enable stacking
                borderRadius: 5,
                pointPadding: 0.2,
                borderWidth: 1
            }
        },
        exporting: {
            enabled: false // Disable the export options (PDF, Excel, etc.)
        },
        credits: {
            enabled: false // Disable the "highcharts.com" credits text
        },
        legend: {
            itemStyle: {
                color: '#000000' // Set legend series names to white
            }
        }
    });
});
</script>

<!-- Pie Chart js -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Data for the pie chart
    const data = [
        {
            name: 'Number of Family',
            y: 57,
            sliced: true,
            selected: true,
            color: 'rgba(0, 123, 255, 0.8)' // Bootstrap primary color
        },
        {
            name: 'Number of Houses',
            y: 57,
            color: 'rgba(40, 167, 69, 0.8)' // Bootstrap success color
        },
        {
            name: 'Number of Individuals',
            y: 189,
            color: 'rgba(255, 193, 7, 0.8)' // Bootstrap warning color
        }
    ];

    // Highcharts for Pie Chart
    Highcharts.chart('pieChart', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            backgroundColor: '#EEF7FF' // Set background color to bg-secondary color
        },
        title: {
            text: 'Population Distribution',
            style: {
                color: '#000000' // Set title color to white
            },
            align: 'center'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false // Disable data labels
                },
                showInLegend: true // Show legend
            }
        },
        series: [{
            name: 'Population',
            colorByPoint: true,
            data: data
        }],
        credits: {
            enabled: false // Disable the "highcharts.com" credits text
        },
        exporting: {
            enabled: false // Disable the export options (PDF, Excel, etc.)
        },
        legend: {
            itemStyle: {
                color: '#000000' // Set legend series names to white
            }
        }
    });
});
</script>

<script>
    // Toggle chat visibility
function toggleReportFloodChat() {
    const chatBox = document.getElementById('reportFloodChat');
    chatBox.classList.toggle('d-none');
}

// Fetch flood reports when chat is opened
function fetchFloodReports() {
    fetch('/flood-report/fetchReports')
        .then(response => response.json())
        .then(data => {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '';

            data.forEach(report => {
                const messageElement = document.createElement('div');
                messageElement.classList.add('chat-message');
                messageElement.innerHTML = `
                    <strong>User ${report.user_id}:</strong> ${report.message}<br>
                    ${report.image ? `<img src="/uploads/flood_reports/${report.image}" alt="Flood Image" style="max-width: 100%;">` : ''}
                    <small>${new Date(report.created_at).toLocaleString()}</small>
                `;
                chatMessages.appendChild(messageElement);
            });

            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
}

// Handle form submission
document.getElementById('floodReportForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/flood-report/submitReport', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            fetchFloodReports(); // Reload chat
            document.getElementById('floodReportForm').reset(); // Clear form
        }
    });
});

// Fetch flood reports when the chat is opened
document.getElementById('reportFloodButton').addEventListener('click', fetchFloodReports);

</script>


 