<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
    }
    .search-card {
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      background: rgba(255,255,255,0.9);
      backdrop-filter: blur(10px);
    }
    .theme-selector {
      cursor: pointer;
      transition: all 0.3s;
    }
    .theme-selector:hover {
      transform: scale(1.05);
    }
    .theme-selector.active {
      border: 3px solid #0d6efd;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="search-card p-4 p-md-5 mb-4">
          <h1 class="text-center mb-4"><i class="bi bi-cloud-sun"></i> Global Weather Dashboard</h1>
          
          <form action="weather.php" method="get" class="needs-validation" novalidate>
            <div class="row g-3">
              <div class="col-md-12">
                <label for="location" class="form-label">Search Location</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-search"></i></span>
                  <input type="text" class="form-control" id="location" name="city" 
                         placeholder="Enter city name, country (e.g., 'London, UK')" required>
                  <button class="btn btn-primary" type="submit">Get Weather</button>
                </div>
                <div class="form-text">Try: New York, Tokyo, Paris, Sydney, etc.</div>
              </div>
            </div>
            
            <div class="mt-4">
              <h5 class="text-center mb-3">Select Theme</h5>
              <div class="d-flex justify-content-center gap-3">
            
            
                <div class="theme-selector rounded-3 p-2" data-theme="nature">
                  <div class="bg-success p-3 rounded-2" style="width: 80px; height: 60px;"></div>
                  <div class="text-center mt-2">Nature</div>
                </div>
                <div class="theme-selector rounded-3 p-2" data-theme="ocean">
                  <div class="bg-info p-3 rounded-2" style="width: 80px; height: 60px;"></div>
                  <div class="text-center mt-2">Ocean</div>
                </div>
              </div>
              <input type="hidden" id="theme" name="theme" value="light">
            </div>
          </form>
        </div>
        
        <div class="text-center text-muted">
          <p>Search for any location worldwide to get current weather and 7-day forecast</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Theme selection
    document.querySelectorAll('.theme-selector').forEach(selector => {
      selector.addEventListener('click', function() {
        document.querySelectorAll('.theme-selector').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('theme').value = this.dataset.theme;
      });
    });
    
    // Form validation
    (function () {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
</body>
</html>