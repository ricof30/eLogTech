<div class="container-fluid fixed-top px-0">
    <div class="container px-0">
        <nav class="navbar navbar-light bg-light navbar-expand-xl">
            <a href="index.html" class="navbar-brand ms-3">
                <h1 class="text-primary" style="font-size:25px; margin:0;">e-LogTech</h1>
            </a>
            <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                <div class="navbar-nav ms-auto align-items-center"> <!-- Added align-items-center -->
                    <a href="#home" class="nav-item nav-link active">Home</a>
                    <a href="#about" class="nav-item nav-link">About</a>
                    <a href="#weather" class="nav-item nav-link">Weather Forecast</a>
                    <a href="#chart" class="nav-item nav-link">Charts</a>
                    <a href="events.html" class="nav-item nav-link">Safety Tips</a>
                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-2" src="<?= base_url('../assets/img/' . $user['image']); ?>" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline"><?= $user['username']; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end border-0 rounded-bottom shadow" style="background-color: #E0FFFF;">
                            <a href="#" class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#profileModal">My Profile</a>
                            <a href="logout" class="dropdown-item text-dark">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title text-danger" id="profileModalLabel">My Profile</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <!-- Display current photo -->
              <div class="text-center mb-4">
                  <form id="profileForm" action="<?= base_url('updateProfile'); ?>" method="post" enctype="multipart/form-data">
                      <img id="profilePhotoPreview" src="<?= base_url('../assets/img/' . $user['image']); ?>" alt="Profile Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                      <div class="position-relative">
                          <input type="file" class="form-control bg-white position-absolute top-100 start-50 translate-middle" id="profilePhoto" name="profilePhoto" style="opacity: 0; width: 100%; height: 100%; z-index: 2;">
                          <label for="profilePhoto" class="position-absolute top-0 start-10 translate-middle rounded-circle bg-light text-dark" style="width: 40px; height: 40px; line-height: 40px; text-align: center; cursor: pointer;">
                              <i class="fa fa-camera"></i>
                          </label>
                      </div>
                  </div>

                  <!-- Username Field -->
                  <div class="mb-3">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" class="form-control bg-white" id="username" name="username" value="<?= esc($user['username']); ?>">
                  </div>

                  <!-- Email Field -->
                  <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control bg-white" id="email" name="email" value="<?= esc($user['email']); ?>" readonly>
                  </div>

                  <!-- Contact Field -->
                  <div class="mb-3">
                      <label for="contact" class="form-label">Contact Number</label>
                      <input type="number" class="form-control bg-white" id="contact" name="contact" value="<?= esc($user['contact']); ?>">
                  </div>

                  <button type="submit" class="btn btn-danger">Save changes</button>
              </form>
              </div>
          </div>
      </div>
  </div>
</div>