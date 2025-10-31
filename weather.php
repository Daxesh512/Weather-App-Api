<?php
$apiKey = "17d35253d25f4b96bf042235251406";
$city = htmlspecialchars($_GET['city']);
$theme = isset($_GET['theme']) ? htmlspecialchars($_GET['theme']) : 'light';

// Get weather data
$currentUrl = "http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$city}";
$forecastUrl = "http://api.weatherapi.com/v1/forecast.json?key={$apiKey}&q={$city}&days=7";

$currentData = json_decode(file_get_contents($currentUrl), true);
$forecastData = json_decode(file_get_contents($forecastUrl), true);

// Error check
if (isset($currentData['error'])) {
  die("<div class='alert alert-danger text-center mt-5'><h2>❌ Error: {$currentData['error']['message']}</h2><a href='index.php' class='btn btn-primary mt-3'>Try Again</a></div>");
}

$current = $currentData['current'];
$location = $currentData['location'];
$forecastDays = $forecastData['forecast']['forecastday'];

// Set theme styles
$themes = [
   'nature' => ['bg' => 'bg-success bg-opacity-10', 'text' => 'text-dark', 'card' => 'bg-white bg-opacity-75'],
  'ocean' => ['bg' => 'bg-info bg-opacity-10', 'text' => 'text-dark', 'card' => 'bg-white bg-opacity-75']
];

$currentTheme = $themes[$theme] ?? $themes['nature'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather in <?= $location['name'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    body {
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
    }
    .bg-nature {
      background-image: url('https://source.unsplash.com/1600x900/?nature,weather');
    }
    .bg-ocean {
      background-image: url('https://source.unsplash.com/1600x900/?ocean,water');
    }
    .weather-card {
      border-radius: 20px;
      backdrop-filter: blur(5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }
    .weather-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    .forecast-day {
      border-radius: 15px;
      transition: all 0.3s ease;
    }
    .forecast-day:hover {
      transform: scale(1.03);
    }
  </style>
</head>
<body class="<?= $currentTheme['bg'] ?> <?= $theme === 'nature' ? 'bg-nature' : '' ?> <?= $theme === 'ocean' ? 'bg-ocean' : '' ?>">
  <div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5 <?= $currentTheme['text'] ?>">
      <h1 class="display-4 fw-bold"><i class="bi bi-cloud-sun"></i> Weather in <?= $location['name'] ?>, <?= $location['country'] ?></h1>
      <p class="lead">Local Time: <?= $location['localtime'] ?></p>
      <a href="index.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Search Again</a>
    </div>

    <!-- Current Weather -->
    <div class="weather-card p-4 mb-5 <?= $currentTheme['card'] ?>">
      <div class="row align-items-center">
        <div class="col-md-4 text-center">
          <img src="<?= str_replace('64x64', '128x128', $current['condition']['icon']) ?>" alt="Weather Icon" class="img-fluid mb-3">
          <h3 class="fw-bold"><?= $current['condition']['text'] ?></h3>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-6">
              <h2 class="display-3 fw-bold"><?= $current['temp_c'] ?>°C</h2>
              <p class="fs-5"><i class="bi bi-thermometer-half"></i> Feels like: <?= $current['feelslike_c'] ?>°C</p>
              <p class="fs-5"><i class="bi bi-droplet"></i> Humidity: <?= $current['humidity'] ?>%</p>
            </div>
            <div class="col-md-6">
              <p class="fs-5"><i class="bi bi-wind"></i> Wind: <?= $current['wind_kph'] ?> km/h <?= $current['wind_dir'] ?></p>
              <p class="fs-5"><i class="bi bi-eye"></i> Visibility: <?= $current['vis_km'] ?> km</p>
              <p class="fs-5"><i class="bi bi-cloud"></i> Cloud Cover: <?= $current['cloud'] ?>%</p>
              <p class="fs-5"><i class="bi bi-umbrella"></i> Precipitation: <?= $current['precip_mm'] ?> mm</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 7-Day Forecast -->
    <h2 class="text-center mb-4 <?= $currentTheme['text'] ?>"><i class="bi bi-calendar3"></i> 7-Day Forecast</h2>
    <div class="row g-4">
      <?php foreach ($forecastDays as $day): ?>
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
          <div class="forecast-day p-3 h-100 <?= $currentTheme['card'] ?>">
            <h5 class="text-center fw-bold"><?= date('l, M j', strtotime($day['date'])) ?></h5>
            <div class="text-center mb-3">
              <img src="<?= str_replace('64x64', '128x128', $day['day']['condition']['icon']) ?>" alt="Weather Icon">
              <p><?= $day['day']['condition']['text'] ?></p>
            </div>
            <div class="d-flex justify-content-between">
              <div>
                <p><i class="bi bi-thermometer-high"></i> High: <?= $day['day']['maxtemp_c'] ?>°C</p>
                <p><i class="bi bi-thermometer-low"></i> Low: <?= $day['day']['mintemp_c'] ?>°C</p>
              </div>
              <div>
                <p><i class="bi bi-droplet"></i> Humid: <?= $day['day']['avghumidity'] ?>%</p>
                <p><i class="bi bi-umbrella"></i> Rain: <?= $day['day']['totalprecip_mm'] ?> mm</p>
              </div>
            </div>
            <div class="mt-2">
              <p class="small"><i class="bi bi-sunrise"></i> <?= $day['astro']['sunrise'] ?> / <i class="bi bi-sunset"></i> <?= $day['astro']['sunset'] ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Hourly Forecast (Next 24 hours) -->
    <h2 class="text-center mt-5 mb-4 <?= $currentTheme['text'] ?>"><i class="bi bi-clock"></i> Hourly Forecast</h2>
    <div class="weather-card p-4 mb-5 <?= $currentTheme['card'] ?>">
      <div class="row g-3">
        <?php 
        $hourly = $forecastDays[0]['hour'];
        // Show current hour + next 23 hours
        $currentHour = date('H', strtotime($location['localtime']));
        for ($i = $currentHour; $i < $currentHour + 24; $i++):
          $hourIndex = $i % 24;
          $hour = $hourly[$hourIndex];
          $time = date('H:i', strtotime($hour['time']));
        ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
          <div class="text-center p-2 rounded <?= $hourIndex == $currentHour ? 'bg-primary text-white' : '' ?>">
            <p class="mb-1 fw-bold"><?= $time ?></p>
            <img src="<?= str_replace('64x64', '64x64', $hour['condition']['icon']) ?>" alt="">
            <p class="mb-0"><?= $hour['temp_c'] ?>°C</p>
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>

  <footer class="text-center py-4 <?= $currentTheme['text'] ?>">
    <p>Weather data provided by <a href="https://www.weatherapi.com/" class="link-primary">WeatherAPI.com</a></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>