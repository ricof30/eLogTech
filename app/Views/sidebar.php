<div class="sidebar pe-4 pb-3"style="background-color:#7AB2B2">
            <nav class="navbar  navbar-dark">
            <a href="index.html" class="navbar-brand mx-4 mb-3" style="display: flex; align-items: center;">
                <img src="../assets/img/eLogTech.jpg" alt="logo" style="width: 50px; margin-right: 10px; background-color:#4D869C; border-radius: 50%" >
                 <h3 class="small gradient-text" style="background:white; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-fill-color: transparent; margin: 0;">e-LogTech</h3>
            </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                    <img class="rounded-circle" src="<?= base_url('../assets/img/' . (!empty($user['image']) ? esc($user['image']) : 'default.jpg')); ?>" alt="Profile photo" style="width: 40px; height: 40px;">

                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?= $user['username']; ?></h6>
                        <!-- <span class="text-dark">Admin</span> -->
                    </div>
                </div>
                <?php if($user['role'] == "admin"):?>
                <div class="menu navbar-nav w-100">
                    <a href="/" class="menu-item nav-item nav-link <?= (current_url() == base_url('/')) ? 'active' : ''; ?>">
                        <i class="fa fa-tachometer-alt me-2 bg-white"></i> <span class="text-white">Dashboard</span>
                    </a>
                    <a href="/contact" class="menu-item nav-item nav-link <?= (current_url() == base_url('/contact')) ? 'active' : ''; ?>">
                        <i class="fa fa-phone me-2 bg-white"></i><span class="text-white">Contact Numbers</span>
                    </a>
                    <a href="/sentMessage" class="menu-item nav-item nav-link <?= (current_url() == base_url('/sentMessage')) ? 'active' : ''; ?>">
                        <i class="fa fa-envelope me-2 bg-white"></i> <span class="text-white">Sent Message</span>
                    </a>
                    <a href="/status" class="menu-item nav-item nav-link <?= (current_url() == base_url('/status')) ? 'active' : ''; ?>">
                        <i class="fas fa-info-circle me-2 bg-white"></i> <span class="text-white">Device Status</span>
                    </a>
                    <a href="/alertHistory" class="menu-item nav-item nav-link <?= (current_url() == base_url('/alertHistory')) ? 'active' : ''; ?>">
                        <i class="fas fa-bell me-2 bg-white"></i> <span class="text-white">Alert History</span>
                    </a>
                    <a href="/user" class="menu-item nav-item nav-link <?= (current_url() == base_url('/user')) ? 'active' : ''; ?>">
                        <i class="fas fa-bell me-2 bg-white"></i> <span class="text-white">User List</span>
                    </a>
                </div>
                <?php endif;?>

                <?php if($user['role'] == "user"):?>
                <div class="menu navbar-nav w-100">
                    <a href="/" class="menu-item nav-item nav-link <?= (current_url() == base_url('/')) ? 'active' : ''; ?>">
                        <i class="fa fa-tachometer-alt me-2 bg-white"></i> <span class="text-white">Dashboard</span>
                    </a>
                    <!-- <a href="/contact" class="menu-item nav-item nav-link <?= (current_url() == base_url('/contact')) ? 'active' : ''; ?>">
                        <i class="fa fa-phone me-2 bg-white"></i><span class="text-white">Contact Numbers</span>
                    </a> -->
                    <!-- <a href="/sentMessage" class="menu-item nav-item nav-link <?= (current_url() == base_url('/sentMessage')) ? 'active' : ''; ?>">
                        <i class="fa fa-envelope me-2 bg-white"></i> <span class="text-white">Sent Message</span>
                    </a> -->
                    <!-- <a href="/status" class="menu-item nav-item nav-link <?= (current_url() == base_url('/status')) ? 'active' : ''; ?>">
                        <i class="fas fa-info-circle me-2 bg-white"></i> <span class="text-white">Device Status</span>
                    </a> -->
                    <a href="/alertHistory" class="menu-item nav-item nav-link <?= (current_url() == base_url('/alertHistory')) ? 'active' : ''; ?>">
                        <i class="fas fa-user me-2 bg-white"></i> <span class="text-white">Alert History</span>
                    </a>
                </div>
                <?php endif;?>

            </nav>
        </div>

