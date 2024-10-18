<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .weather-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: auto;
        }
        .weather-info {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="weather-container">
        <?php if (isset($error)): ?>
            <p class="weather-info"><?= esc($error) ?></p>
        <?php else: ?>
            <h2>Weather in <?= esc($location) ?></h2>
            <p class="weather-info">Temperature: <?= esc($temperature) ?> °C</p>
            <p class="weather-info">Humidity: <?= esc($humidity) ?> %</p>
            <p class="weather-info">Wind Speed: <?= esc($wind_speed) ?> m/s</p>
            <p class="weather-info">Description: <?= esc($description) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
