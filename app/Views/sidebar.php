<div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
            <a href="index.html" class="navbar-brand mx-4 mb-3" style="display: flex; align-items: center;">
    <img src="../assets/img/eLogTech.png" alt="logo" style="width: 50px; margin-right: 10px;">
    <h3 class="small gradient-text" style="background: linear-gradient(to right, red, orange); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-fill-color: transparent; margin: 0;">eLogTech</h3>
</a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                    <img class="rounded-circle" src="<?= base_url('../assets/img/' . (!empty($user['image']) ? esc($user['image']) : 'default.jpg')); ?>" alt="Profile photo" style="width: 40px; height: 40px;">

                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?= $user['username']; ?></h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="menu navbar-nav w-100">
                    <a href="/" class="menu-item nav-item nav-link">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="/contact" class="menu-item nav-item nav-link">
                        <i class="fa fa-phone me-2"></i>Contact Numbers
                    </a>
                    <a href="/sentMessage" class="menu-item nav-item nav-link">
                        <i class="fa fa-envelope me-2"></i>Sent Message
                    </a>
                    <!-- <div class="nav-item dropdown"> -->
                        <!-- <a href="/sentMessage" class="menu-item nav-item nav-link ">
                            <i class="fa fa-envelope me-2"></i>Messages
                        </a> -->
                        <!-- <ul class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0 ml-1" aria-labelledby="messagesDropdown">
                            <li style="margin-left:30px">  <a href="/messages" class="dropdown-item nav-link ml-5"><i class="fa fa-comment me-2"></i>Receive SMS</a></li>
                            <li style="margin-left:30px"><a href="/sentMessage" class="dropdown-item nav-link ml-5"><i class="fa fa-comment me-2"></i>Sent SMS</a></li>
                        </ul>
                    </div> -->
                    <a href="/status" class="menu-item nav-item nav-link">
                        <i class="fas fa-info-circle me-2"></i>Device Status
                    </a>
                    <a href="/alertHistory" class="menu-item nav-item nav-link">
                        <i class="fas fa-bell me-2"></i>Alert History
                    </a>

                </div>
            </nav>
        </div>

