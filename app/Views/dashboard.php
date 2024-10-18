<?= $this->include('header');?>
<body>
    <div class="container-fluid position-relative d-flex p-0">
      


        <!-- Sidebar Start -->
        <?= $this->include('sidebar');?>

        <!-- Content Start -->
            <div class="content">
        
                <?= $this->include('navbar');?>
                
                <!-- Toast containers for success and error messages -->
        <div aria-live="polite" aria-atomic="true" class="position-relative">
                                        <div class="toast-container position-fixed top-1 end-0 p-3" style="z-index: 1100;">
                                            <?php if (session()->getFlashdata('success')): ?>
                                                <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                                                    <div class="d-flex">
                                                        <div class="toast-body">
                                                            <i class="fa fa-check-circle me-2"></i>
                                                            <?= session()->getFlashdata('success'); ?>
                                                        </div>
                                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (session()->getFlashdata('error')): ?>
                                                <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                                                    <div class="d-flex">
                                                        <div class="toast-body">
                                                            <i class="fa fa-exclamation-circle me-2"></i>
                                                            <?= session()->getFlashdata('error'); ?>
                                                        </div>
                                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>



        <div class="container-fluid pt-4 px-4" style="background-color:#CDE8E5">
        <div class="row g-4 flex-content justify-content-between">

        <div class="weather-dashboard">
            <h2 class="text-center text-dark mb-4">Weather Forecast</h2>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header  text-white" style="background-color: #7AB2B2;">
                            <h6 class="mt-3 text-white">Next 4 Days Forecast</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($weatherData)): ?>
                                <div class="row">
                                    <?php foreach ($weatherData as $date => $data): ?>
                                        <div class="col-md-3 mb-4">
                                            <div class="card h-100 border-light rounded">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title text-dark">
                                                        <?= date('l, F j', strtotime($date)) ?>
                                                    </h5>
                                                    <p class="card-text">
                                                        <i class="fas fa-temperature-high fa-2x text-danger"></i>
                                                        <br>
                                                        Temperature: <span class="font-weight-bold"><?= $data['temperature'] ?> °C</span>
                                                    </p>
                                                    <p class="card-text">
                                                        <i class="fas fa-cloud-rain fa-2x text-primary"></i>
                                                        <br>
                                                        Precipitation: <span class="font-weight-bold"><?= $data['precipitationIntensity'] ?> mm/h</span>
                                                    </p>
                                                    <p class="card-text">
                                                        <i class="fas fa-wind fa-2x text-success"></i>
                                                        <br>
                                                        Wind: <span class="font-weight-bold"><?= $data['windSpeed'] ?> m/s, <?= $data['windDirection'] ?>°</span>
                                                    </p>
                                                    <p class="card-text">
                                                    
                                                    <?php if ($data['weatherCode'] >= 1000 && $data['weatherCode'] <= 1100): ?>
                                                            <i class="fas fa-sun fa-2x text-warning"></i>
                                                            <br>
                                                            Weather: Sunny</span>
                                                        <?php elseif ($data['weatherCode'] >= 1101 && $data['weatherCode'] <= 1200): ?>
                                                            <i class="fas fa-cloud fa-2x text-secondary"></i>
                                                            <br>
                                                            Weather: Cloudy</span>
                                                        <?php elseif ($data['weatherCode'] >= 2000 && $data['weatherCode'] <= 2300): ?>
                                                            <i class="fas fa-cloud-rain fa-2x text-info"></i>
                                                            <br>
                                                            Weather: Rainy</span>
                                                        <?php elseif ($data['weatherCode'] >= 3000 && $data['weatherCode'] <= 3300): ?>
                                                            <i class="fas fa-snowflake fa-2x text-primary"></i>
                                                            <br>
                                                            Weather: Snowy</span>
                                                        <?php elseif ($data['weatherCode'] >= 4000 && $data['weatherCode'] <= 4200): ?>
                                                            <i class="fas fa-wind fa-2x text-success"></i>
                                                            <br>
                                                            Weather: Windy</span>
                                                        <?php else: ?>
                                                            <i class="fas fa-question-circle fa-2x text-danger"></i>
                                                            <br>
                                                            Weather: Unknown (Code: <span class="font-weight-bold"><?= $data['weatherCode'] ?></span>)
                                                        <?php endif; ?>


                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-center">No weather data available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>

                                <!-- Floating Report Flood Button -->


            <!-- First Row: Pie Chart and Water Level Chart -->
            <div class="col-sm-12 col-xl-4">
                <div id="pieChart" class="rounded" style="width: 100%; height: 495px;"></div>
            </div>

            <div class="col-sm-12 col-xl-8">
                <div class="text-center rounded p-3" style="background-color: #EEF7FF">
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <button id="filterWaterLevel" class="btn btn-primary">Filter</button>
                    </div>
                    <div id="waterlevel" class="bg-secondary" style="width: 100%; height: 400px;"></div>
                </div>
            </div>

            <!-- Second Row: Rainfall Chart -->
            <div class="col-12">
                <div class="text-center rounded p-3" style="background-color: #EEF7FF">
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <button id="filterRainfall" class="btn btn-primary">Filter</button>
                    </div>
                    <div id="rainfallChart" style="width: 100%; height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

        </div>
    </div>
        

<!-- Modal for filtering water level -->
<div class="modal fade" id="filterWater" tabindex="-1" aria-labelledby="filterWaterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="filterWaterForm" action="<?= base_url('filterWaterLevel') ?>" method="post">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="filterWaterModalLabel">Filter Water Level Data</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="waterStartDate" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="waterStartDate" name="water_start_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="waterEndDate" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="waterEndDate" name="water_end_date" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Print</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal for filtering rainfall  -->
<div class="modal fade" id="filterRain" tabindex="-1" aria-labelledby="filterRainModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="filterRainForm" action="<?= base_url('filterRainfall') ?>" method="post">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="filterRainModalLabel">Filter Rainfall Data</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rainStartDate" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="rainStartDate" name="rain_start_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="rainEndDate" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="rainEndDate" name="rain_end_date" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Print</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Solar Charge Chart -->
<!-- <div class="container-fluid pt-4 px-6">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="position-relative">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Solar Charge Monitoring</h6>
                    </div>
                    <canvas id="solarVoltageChart" style="width: 100%; height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div> -->

        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
      <!-- Script Start -->
    <?= $this->include('script');?>
    <?= $this->include('script1');?>
    <script>
        document.getElementById('profilePhoto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhotoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        toastList.forEach(toast => toast.show());
    });
</script>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(function(reg) {
                console.log('Service Worker Registered', reg);
            }).catch(function(error) {
                console.log('Service Worker Registration Failed', error);
            });
    }
</script>
<script>
    function subscribeToPushNotifications() {
        if (!('serviceWorker' in navigator)) {
            return console.error('Service Worker is not supported');
        }

        navigator.serviceWorker.ready.then(function(registration) {
            return registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: '<your_public_key_base64>'
            });
        }).then(function(subscription) {
            // Send subscription to the server
            fetch('/push-notification/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(subscription)
            }).then(function(response) {
                if (!response.ok) {
                    throw new Error('Failed to subscribe');
                }
                console.log('User subscribed to push notifications');
            }).catch(function(error) {
                console.error('Subscription failed:', error);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        subscribeToPushNotifications();
    });
</script>




      <!-- Script End -->
</body>

</html>