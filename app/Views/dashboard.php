<?= $this->include('header');?>
<body>
    <div class="container-fluid position-relative d-flex p-0">
      


        <!-- Sidebar Start -->
        <?= $this->include('sidebar');?>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control bg-dark border-0" type="search" placeholder="Search">
                </form>

                <!-- div for Message Notification -->
                <div class="navbar-nav align-items-center ms-auto">
    <div class="nav-item dropdown position-relative">
        <?php 
            $unreadCount = 0;
            $unreadMessages = [];
            foreach ($messages as $message) {
                if (!$message['is_read']) {
                    $unreadCount++;
                    $unreadMessages[] = $message;
                }
            }
        ?>      
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fa fa-envelope me-lg-2 position-relative">
                <?php if ($unreadCount > 0): ?>
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        <?= $unreadCount ?>
                        <span class="visually-hidden">unread messages</span>
                    </span>
                <?php endif; ?>
            </i>
            <span class="d-none d-lg-inline-flex">Message</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
            <?php 
                $count = 0;
                foreach ($unreadMessages as $message): 
                    if ($count >= 5) {
                        break;
                    }
            ?>
                <a href="/messages" class="dropdown-item">
                    <div class="d-flex align-items-center">
                        <div class="ms-2">
                            <h6 class="fw-normal mb-0"><?= $message['message'] ?></h6>
                            <small><?= date('F j, Y, g:i a', strtotime($message['date'])) ?></small>
                        </div>
                    </div>
                </a>
                <hr class="dropdown-divider">
            <?php 
                    $count++;
                endforeach; 
            ?>
            <?php if ($unreadCount > 5): ?>
                <a href="/messages" class="dropdown-item text-center">See all messages</a>
            <?php endif; ?>
        </div>
    </div>



    <div class="nav-item dropdown position-relative">
    <?php 
        // Calculate the total number of notifications
        $totalNotifications = count($latestWaterLevel);
    ?>
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fa fa-bell me-lg-2 position-relative">
            <?php if ($totalNotifications > 0): ?>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                    <?= $totalNotifications ?>
                </span>
            <?php endif; ?>
        </i>
        <span class="d-none d-lg-inline-flex">Recent Alerts</span>
    </a>
    <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
        <?php foreach ($latestWaterLevel as $level): ?>
            <a href="#" class="dropdown-item">
                <h6 class="fw-normal mb-0">Water level: <?= $level['waterlevel'] ?> meters</h6>
                <small><?= date('F j, Y, g:i a', strtotime($level['date'])) ?></small>
            </a>
            <hr class="dropdown-divider">
        <?php endforeach; ?>
        <a href="#" class="dropdown-item text-center">See all notifications</a>
    </div>
</div>



                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="../assets/img/user logo.jpg" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">Rico Fontecilla</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="logout" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4 flex-content">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                           <img src="../assets/img/family.png" alt="family icon" style="width:70px">
                            <div class="ms-3">
                                <p class="mb-2">Number of Family</p>
                                <h6 class="mb-0">57</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                             <img src="../assets/img/house.png" alt="family icon" style="width:50px">
                            <div class="ms-3">
                                <p class="mb-2">Number of Houses</p>
                                <h6 class="mb-0">57</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                             <img src="../assets/img/indiv.png" alt="family icon" style="width:65px;height:50px">
                            <div class="ms-3">
                                <p class="mb-2">Number of Individual</p>
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
<div class="container-fluid pt-4 px-6">
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
                    <button type="submit" class="btn btn-primary">Download</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rainfall Monitoring View -->
<div class="container-fluid pt-4 px-6">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="position-relative">
                <!-- Filtering button -->
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
                    <button type="submit" class="btn btn-primary">Download</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Solar Charge Chart -->
<div class="container-fluid pt-4 px-6">
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
</div>



            <!-- Sales Chart End -->

            <!-- Footer Start -->
           <?= $this->include('footer');?>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
      <!-- Script Start -->
    <?= $this->include('script');?>
    <?= $this->include('script1');?>

      <!-- Script End -->
</body>

</html>