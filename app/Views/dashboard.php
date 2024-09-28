<?= $this->include('header');?>
<body>
    <div class="container-fluid position-relative d-flex p-0">
      


        <!-- Sidebar Start -->
        <?= $this->include('sidebar');?>

        <!-- Content Start -->
        <div class="content">
      
            <?= $this->include('navbar');?>
            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4 flex-content justify-content-around">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                           <img src="../assets/img/family.png" alt="family icon" style="width:70px">
                            <div class="ms-3">
                                <p class="numbers mb-2" >Number of Family</p>
                                <h6 class="mb-0">57</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                             <img src="../assets/img/house.png" alt="family icon" style="width:50px">
                            <div class="ms-3">
                                <p class="numbers mb-2">Number of Houses</p>
                                <h6 class="mb-0">57</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                             <img src="../assets/img/indiv.png" alt="family icon" style="width:65px;height:50px">
                            <div class="ms-3">
                                <p class="numbers mb-2">Number of Individual</p>
                                <h6 class="mb-0">189</h6>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2"></p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <!-- Sale & Revenue End -->

<!-- Water Level Monitoring View -->
<!-- <div class="container-fluid pt-4 px-6">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="position-relative">
                <button id="filterWaterLevel" class="btn btn-primary position-absolute top-0 end-0 mt-3 me-3">Filter</button>
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Water Level Monitoring</h6>
                    </div>
                    <canvas id="waterlevel" style="width: 100%;height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6 ">
            <div class="waterlevelchart bg-secondary text-center rounded p-3">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0 pl-5">Water Level Chart</h6>
                    <button id="filterWaterLevel" class="btn btn-primary">Filter</button>
                </div>
                <canvas id="waterlevel" style="width: 100%;height:350px;"></canvas>
            </div>
        </div>  
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary text-center rounded p-3">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Rainfall Chart</h6>
                    <button id="filterRainfall" class="btn btn-primary">Filter</button>
                </div>
                <canvas id="rainfallChart" style="width: 100%;height: 350px;"></canvas>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rainfall Monitoring View -->
<!-- <div class="container-fluid pt-4 px-6">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="position-relative">
                <button id="filterRainfall" class="btn btn-primary position-absolute top-0 end-0 mt-3 me-3">Filter</button>
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Rainfall Monitoring</h6>
                    </div>
                    <canvas id="rainfallChart" style="width: 100%; height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div> -->

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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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


      <!-- Script End -->
</body>

</html>