<!DOCTYPE html>
<html>
<head>
    <title>Rainfall Prediction</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Rainfall Prediction for This Month</h1>
    <canvas id="predictionChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('predictionChart').getContext('2d');
        var predictionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Predicted Rainfall'],
                datasets: [{
                    label: 'Rainfall (mm)',
                    data: [<?= $prediction ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
