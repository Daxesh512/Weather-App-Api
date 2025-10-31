<?php
// Replace with your actual API key
$apiKey = "17d35253d25f4b96bf042235251406";

$city = isset($_GET['city']) ? $_GET['city'] : 'Anand';

// Step 1: Get lat/lon for the city
$geoUrl = "http://api.openweathermap.org/geo/1.0/direct?q=$city&limit=1&appid=$apiKey";
$geoResponse = file_get_contents($geoUrl);
$geoData = json_decode($geoResponse, true);

if (isset($geoData[0])) {
    $lat = $geoData[0]['lat'];
    $lon = $geoData[0]['lon'];

    // Step 2: Get 7-day forecast
    $forecastUrl = "https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&exclude=minutely,hourly,alerts&units=metric&appid=$apiKey";
    $forecastResponse = file_get_contents($forecastUrl);
    $forecastData = json_decode($forecastResponse, true);

    // Step 3: Extract forecast info
    $labels = [];
    $temps = [];

    foreach ($forecastData['daily'] as $day) {
        $labels[] = date("D", $day['dt']);
        $temps[] = $day['temp']['day'];
    }
} else {
    $labels = [];
    $temps = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>7-Day Temperature Chart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: linear-gradient(120deg, #d4fc79, #96e6a1);
      min-height: 100vh;
      padding: 20px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h2 class="text-center mb-4">7-Day Temperature Forecast for <span class="text-primary"><?php echo htmlspecialchars($city); ?></span></h2>

    <div class="card p-4">
      <canvas id="forecastChart" height="100"></canvas>
    </div>
    
    <div class="text-center mt-4">
      <form action="" method="get" class="d-flex justify-content-center gap-2">
        <input type="text" name="city" class="form-control w-50" placeholder="Enter another city..." required>
        <button type="submit" class="btn btn-success">Show Forecast</button>
      </form>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('forecastChart').getContext('2d');
    const forecastChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
          label: 'Temperature (°C)',
          data: <?php echo json_encode($temps); ?>,
          backgroundColor: 'rgba(25, 135, 84, 0.2)',
          borderColor: 'rgba(25, 135, 84, 1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointRadius: 4
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: false
          }
        }
      }
    });
  </script>
</body>
</html>
