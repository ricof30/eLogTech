      <!-- Navbar Start -->
      <nav class="navbar navbar-expand  navbar-dark sticky-top px-4 py-0" style="background-color:#7AB2B2">
              
              <a href="#" class="sidebar-toggler flex-shrink-0 bg-white">
                  <i class="fa fa-bars"></i>
              </a>
              <!-- <form class="d-none d-md-flex ms-4">
                  <input class="form-control bg-dark border-0" type="search" placeholder="Search">
              </form> -->

              <!-- div for Message Notification -->
              <div class="navbar-nav align-items-center ms-auto">
  <!-- <div class="nav-item dropdown position-relative">
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
                  if ($count >= 4) {
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
              <a href="/messages" class="dropdown-item text-center">See all messages</a>
      </div>
  </div> -->


<div class="nav-item dropdown position-relative">
  <?php 
      // Calculate the total number of notifications
      $totalNotifications = count($latestWaterLevel);
  ?>
  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
      <i class="fa fa-bell me-lg-2 position-relative bg-white">
          <?php if ($totalNotifications > 0): ?>
              <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                  <?= $totalNotifications ?>
              </span>
          <?php endif; ?>
      </i>
      <span class="d-none d-lg-inline-flex text-white">Recent Alerts</span>
  </a>
  <div class="alerts_dropdown dropdown-menu dropdown-menu-center border border-0 border-dark rounded-0 rounded-bottom" style="background-color:#ADD8E6"> 

          <?php
             $counter = 0;
             foreach ($latestWaterLevel as $level): 

             

                 if ($counter >= 4) {
                     break; // Exit the loop after displaying 3 alerts
                 }
                 $counter++;
              // Determine the alert message based on the water level
              if ($level['waterlevel'] > 3) {
                  $alertMessage = "High water level: " . $level['waterlevel'] . " meters"; 
                  $toastrType = 'error';
              } elseif ($level['waterlevel'] > 2 && $level['waterlevel'] <= 3) {
                  $alertMessage = "Moderate water level: " . $level['waterlevel'] . " meters";
                  $toastrType = 'warning';
              } elseif ($level['waterlevel'] > 0 && $level['waterlevel'] <= 2) {
                  $alertMessage = "Low water level: " . $level['waterlevel'] . " meters";
                  $toastrType = 'info';
              } else {
                  $alertMessage = "Normal water level: " . $level['waterlevel'] . " meters";
                  $toastrType = 'success';
              }

          ?>
          <a href="#" class="dropdown-item">
              <h6 class="fw-normal mb-0 text-dark"><?= $alertMessage ?></h6>
              <small><?= date('F j, Y,', strtotime($level['date'])) . " " . date('g: i a',strtotime($level['time'])) ?></small>
          </a>
          <hr class="dropdown-divider">
          
           <!-- <script>
              // Trigger Toastr notification for this alert
              document.addEventListener('DOMContentLoaded', function() {
                  toastr.<?= $toastrType ?>('<?= $alertMessage ?>');
              });
          </script>  -->
      <?php endforeach; ?>
      <a href="/alertHistory" class="dropdown-item text-center">See all notifications</a>
  </div>
</div> 



                  <div class="nav-item dropdown">
                      <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                          <img class="rounded-circle me-lg-2" src="<?= base_url('../assets/img/' . $user['image']); ?>"  alt="" style="width: 40px; height: 40px;">
                          <span class="d-none d-lg-inline-flex text-white"><?= $user['username']; ?></span>
                      </a>
                      <div class="profile_dropdown dropdown-menu dropdown-menu-end  border-0 rounded-0 rounded-bottom m-0" style="background-color:#E0FFFF"> <!-- Change background color here -->

                        <a href="#" class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#profileModal">My Profile</a>

                          <!-- <a href="#" class="dropdown-item">Settings</a> -->
                          <a href="logout" class="dropdown-item text-dark">Log Out</a>
                      </div>
                  </div>
              </div>
          </nav>
          <!-- Navbar End -->

      <!-- Profile Modal -->
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
                  <form action="<?= base_url('updatePhoto'); ?>" method="post" enctype="multipart/form-data">

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
                      <input type="text" class="form-control bg-white" id="username" name="username" value="<?= esc($user['username']); ?>" readonly>
                  </div>

                  <!-- Email Field -->
                  <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control bg-white" id="email" name="email" value="<?= esc($user['email']); ?>" readonly>
                  </div>

                  <button type="submit" class="btn btn-primary">Save changes</button>
                  </form>

          </div>
      </div>
  </div>
</div>

