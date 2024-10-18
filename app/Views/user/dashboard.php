<?= $this->include('user/header');?>
<?= $this->include('user/css');?>
        
       <?= $this->include('user/navbar');?>
        
        <div class="container-fluid carousel-header vh-100 px-0" id="home">
            <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img src="../assets/img/flood.png" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="p-3" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">We'll Monitor Our Waters</h4>
                                <h1 class="display-1 text-capitalize text-white mb-4">Flood Surveillance System</h1>
                                <p class="mb-5 fs-5">Our flood surveillance system is designed to monitor water levels and alert communities to potential flooding risks. Stay safe and informed.</p>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="btn-hover-bg btn btn-primary text-white py-3 px-5" href="#about">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>





        <!-- About Start -->
      <div class="container-fluid about py-5" id="about">
    <div class="container py-5" >
        <div class="row g-5">
            <div class="col-xl-5">
                <div class="h-100">
                    <img src="../assets/img/flood1.png" class="img-fluid w-100 h-100" alt="Flood Surveillance">
                </div>
            </div>
            <div class="col-xl-7">
                <h5 class="text-uppercase text-primary">About Us</h5>
                <h1 class="mb-4">Our main goal is to protect the environment</h1>
                <p class="fs-5 mb-4">
                    At e-LogTech, we are dedicated to provide a comprehensive flood surveillance system that ensures the safety and well-being of communities prone to flooding. Our system leverages technology to monitor water levels in real-time, helping to mitigate the risks associated with floods.
                </p>
                <div class="tab-class bg-secondary p-4">
                    <ul class="nav d-flex mb-2">
                        <li class="nav-item mb-3">
                            <a class="d-flex py-2 text-center bg-white active" data-bs-toggle="pill" href="#tab-1">
                                <span class="text-dark" style="width: 150px;">About</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="text-start my-auto">
                                            <h5 class="text-uppercase mb-3">About e-LogTech</h5>
                                            <p class="mb-4">e-LogTech provides innovative solutions for flood monitoring and management. Our flood surveillance system is designed to monitor water levels, detect anomalies, and alert local authorities and residents about potential flooding situations. This proactive approach aims to save lives and reduce property damage.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- About End -->

        <div class="container-fluid about py-5" id="weather">
    <div class="container-fluid" style="width: 100%; padding: 0;">
        <!-- Full-width container -->
        <div class="row g-5">
            <div class="col-12">
                <div class="weather-dashboard">
                    <!-- Title -->
                    <h2 class="text-center text-dark mb-4" style="font-size: 2.5rem; font-weight: bold;">Weather Forecast</h2>
                    
                    <!-- Main Card -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-lg border-0" style="background: linear-gradient(to right, #83a4d4, #b6fbff); border-radius: 20px;">
                                <div class="card-header text-white text-center" style="background-color: transparent; border-radius: 20px 20px 0 0;">
                                    <h6 class="mt-3 mb-0 text-white" style="font-size: 1.5rem;">Arangin, Naujan, Oriental Mindoro</h6>
                                </div>
                                <div class="card-body p-4 border-0">
                                    <?php if (!empty($weatherData)): ?>
                                        <div class="row">
                                            <?php foreach ($weatherData as $date => $data): ?>
                                                <div class="col-lg-3 col-md-6 mb-4">
                                                    <!-- Forecast Cards -->
                                                    <div class="card h-100 border-light rounded shadow-sm" style="transition: transform 0.3s ease; overflow: hidden; border-radius: 20px; background-color: #ffffff;">
                                                        <div class="card-body text-center" style="padding: 2rem;">
                                                            <!-- Date -->
                                                            <h5 class="card-title text-dark mb-3" style="font-weight: bold;">
                                                                <?= date('l, F j', strtotime($date)) ?>
                                                            </h5>
                                                            
                                                            <!-- Weather Icon and Description -->
                                                            <div class="mb-3">
                                                                <?php if ($data['weatherCode'] >= 1000 && $data['weatherCode'] <= 1100): ?>
                                                                    <i class="fas fa-sun fa-3x text-warning mb-2"></i>
                                                                    <p class="mb-0">Sunny</p>
                                                                <?php elseif ($data['weatherCode'] >= 1101 && $data['weatherCode'] <= 1200): ?>
                                                                    <i class="fas fa-cloud fa-3x text-secondary mb-2"></i>
                                                                    <p class="mb-0">Cloudy</p>
                                                                <?php elseif ($data['weatherCode'] >= 2000 && $data['weatherCode'] <= 2300): ?>
                                                                    <i class="fas fa-cloud-rain fa-3x text-info mb-2"></i>
                                                                    <p class="mb-0">Rainy</p>
                                                                <?php elseif ($data['weatherCode'] >= 3000 && $data['weatherCode'] <= 3300): ?>
                                                                    <i class="fas fa-snowflake fa-3x text-primary mb-2"></i>
                                                                    <p class="mb-0">Snowy</p>
                                                                <?php elseif ($data['weatherCode'] >= 4000 && $data['weatherCode'] <= 4200): ?>
                                                                    <i class="fas fa-wind fa-3x text-success mb-2"></i>
                                                                    <p class="mb-0">Windy</p>
                                                                <?php else: ?>
                                                                    <i class="fas fa-question-circle fa-3x text-danger mb-2"></i>
                                                                    <p class="mb-0">Unknown (Code: <?= $data['weatherCode'] ?>)</p>
                                                                <?php endif; ?>
                                                            </div>
                                                                <div class="d-flex justify-content-around align-items-center mb-2">
                                                                    <div class="text-left">
                                                                        <!-- Temperature Box -->
                                                                        <div class="weather-box d-flex flex-column align-items-center justify-content-center mb-2">
                                                                            <i class="fas fa-temperature-high fa-lg text-danger mb-1"></i>
                                                                            <p class="mb-0">Temp</p>
                                                                            <p class="font-weight-bold"><?= $data['temperature'] ?> °C</p>
                                                                        </div>
                                                                        
                                                                        <!-- Precipitation Box -->
                                                                        <div class="weather-box d-flex flex-column align-items-center justify-content-center mb-2">
                                                                            <i class="fas fa-cloud-rain fa-lg text-primary mb-1"></i>
                                                                            <p class="mb-0">Precipitation</p>
                                                                            <p class="font-weight-bold"><?= $data['precipitationIntensity'] ?> mm/h</p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="text-right">
                                                                        <!-- Wind Box -->
                                                                        <div class="weather-box d-flex flex-column align-items-center justify-content-center mb-2">
                                                                            <i class="fas fa-wind fa-lg text-success mb-1"></i>
                                                                            <p class="mb-0">Wind</p>
                                                                            <p class="font-weight-bold"><?= $data['windSpeed'] ?> m/s</p>
                                                                        </div>

                                                                        <!-- Wind Direction Box -->
                                                                        <div class="weather-box d-flex flex-column align-items-center justify-content-center mb-2">
                                                                            <i class="fas fa-location-arrow fa-lg text-info mb-1"></i>
                                                                            <p class="mb-0">Direction</p>
                                                                            <p class="font-weight-bold"><?= $data['windDirection'] ?>°</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
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
            </div>
        </div>
    </div>
</div>
<div class="container-fluid px-0 about py-5" id="chart" style="background-color: #f5f7fa;">
    <div class="container-fluid py-5">
        <div class="row g-5">

            <!-- First Chart: Water Level Chart -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow border-0" style="padding: 20px; border-radius: 30px; background-color: #ffffff;">
                    <h4 class="text-center text-primary mb-4" style="font-weight: bold; font-family: 'Poppins', sans-serif;">Water Level Data</h4>
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <button id="filterWaterLevel" class="filter btn button text-white">Filter</button>
                    </div>
                    <div id="waterlevel" style="width: 100%; height: 400px; background: linear-gradient(to right, #e3f2fd, #bbdefb); border-radius: 30px; border: 1px solid #dfe6ed;"></div>
                </div>
            </div>

            <!-- Second Chart: Rainfall Chart -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow border-0" style="padding: 20px; border-radius: 30px; background-color: #ffffff;">
                    <h4 class="text-center text-primary mb-4" style="font-weight: bold; font-family: 'Poppins', sans-serif;">Rainfall Data</h4>
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <button id="filterRainfall" class="filter btn button text-white">Filter</button>
                    </div>
                    <div id="rainfallChart" style="width: 100%; height: 400px; background: linear-gradient(to right, #e3f2fd, #bbdefb); border-radius: 30px; border: 1px solid #dfe6ed;"></div>
                </div>
            </div>

        </div>

        <div id="reportFloodButton" class="fixed-bottom start-0 m-4">
    <button class="btn btn-primary" onclick="toggleChatBox()">Report Flood</button>
</div>

<!-- Report Flood Chat Window -->
<div id="reportFloodChat" class="chat-box p-3 border rounded bg-white shadow-lg d-none">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="text-center">Chat Reports</h5>
        <button type="button" class="btn-close" onclick="toggleChatBox()" aria-label="Close"></button>
    </div>

    <!-- Chat Messages -->
    <div id="chatBox" class="chat-messages" style="height: 300px; overflow-y: scroll;"></div>

    <!-- Chat Form -->
    <form id="chatForm" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
        <input type="hidden" id="username" name="username" value="<?= esc($user['username']); ?>">

        <!-- Image upload elements -->
        <input type="file" id="image" name="image" class="d-none" onchange="showImageName()">
        <button type="button" class="btn btn-light me-2" id="uploadIcon" onclick="document.getElementById('image').click()">
            <i class="bi bi-image" style="font-size: 24px;"></i>
        </button>
        
        <!-- Camera upload elements -->
        <!-- <input type="file" id="camera" name="camera" accept="image/*" capture="camera" class="d-none" onchange="showImageName()">
        <button type="button" class="btn btn-light me-2" id="cameraIcon" onclick="document.getElementById('camera').click()">
            <i class="bi bi-camera" style="font-size: 24px;"></i>
        </button> -->

        <span id="imageName" class="me-2" style="color: #666;"></span> <!-- Span to display the image name -->
        
        <div class="flex-grow-1">
            <textarea class="form-control" id="message" name="message" rows="2" placeholder="Type your message..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary ms-2">Send</button>
    </form>
</div>




<!-- Image Modal (Full Screen) -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <div class="d-flex align-items-center">
                    <a id="downloadImageLink" href="#" download class="btn btn-success me-2">
                        <i class="bi bi-download"></i>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="toggleChatBox()" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center" style="position: relative;">
                <button class="btn btn-light position-absolute start-0" id="prevImage" style="z-index: 10;" onclick="prevImage()">&#10094;</button>
                <img id="modalImage" src="" alt="Image" class="img-fluid" style="max-height: 100vh;">
                <button class="btn btn-light position-absolute end-0" id="nextImage" style="z-index: 10;" onclick="nextImage()">&#10095;</button>
            </div>
        </div>
    </div>
</div>






    </div>
</div>


<!-- Rainfall Filter Modal -->
<!-- Rainfall Filter Modal -->
<div class="modal fade" id="filterRain" tabindex="-1" aria-labelledby="filterRainModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="filterRainForm" action="<?= base_url('filterRainfall') ?>" method="post" onsubmit="closeModal('filterRain')">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-center text-white" id="filterRainModalLabel">Filter Rainfall Data</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rainStartDate" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="rainStartDate" name="rain_start_date" readonly required>
                        <small id="rainStartDateError" class="text-danger" style="display:none;">Please select a start date.</small>
                    </div>
                    <div class="mb-3">
                        <label for="rainEndDate" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="rainEndDate" name="rain_end_date" readonly required>
                        <small id="rainEndDateError" class="text-danger" style="display:none;">Please select an end date.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger text-white">Print</button>
                    <button type="button" class="btn btn-dark text-white" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Water Level Filter Modal -->
<div class="modal fade" id="filterWater" tabindex="-1" aria-labelledby="filterWaterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="filterWaterForm" action="<?= base_url('filterWaterLevel') ?>" method="post" onsubmit="closeModal('filterWater')">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-center text-white" id="filterWaterModalLabel">Filter Water Level Data</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="waterStartDate" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="waterStartDate" name="water_start_date" readonly required>
                        <small id="waterStartDateError" class="text-danger" style="display:none;">Please select a start date.</small>
                    </div>
                    <div class="mb-3">
                        <label for="waterEndDate" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="waterEndDate" name="water_end_date" readonly required>
                        <small id="waterEndDateError" class="text-danger" style="display:none;">Please select an end date.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger text-white">Print</button>
                    <button type="button" class="btn btn-dark text-white" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

      <?= $this->include('user/script');?>
      <?= $this->include('user/footer');?>