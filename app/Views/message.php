<?= $this->include('header'); ?>
<?= $this->include('sidebar'); ?>
<div class="content">
<div class="menu-bar">
        <a href="#" class="sidebar-toggler flex-shrink-0">
                        <i class="fa fa-bars"></i>
        </a>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-secondary rounded h-100 p-4">

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    <?= session()->getFlashdata('success'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h6 class="mb-4 text-center large">Received Messages</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-white">Message</th>
                                    <th scope="col" class="text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($distinctMessages as $message): ?>
    <tr>    
        <td>
            <div class="d-flex align-items-start">
                <!-- User Logo -->
                <img src="../assets/img/user.png" alt="User Logo" class="me-2" style="width: 50px;">
                <!-- Message Content -->
                <div>
                    <!-- Message Text -->
                    <a href="/message/show/<?= $message['phone_number']; ?>" class="message-details text-white" data-phone="<?= $message['phone_number']; ?>" data-message="<?= $message['message']; ?>" data-date="<?= date('Y-m-d', strtotime($message['date'])); ?>" data-time="<?= $message['time']; ?>"><?= $message['phone_number']; ?></a>
                </div>
            </div>
        </td>
        <td>
        <a href="deleteByPhone/<?= $message['phone_number']; ?>" class="btn btn-danger delete-message" onclick="return confirm('Are you sure you want to delete this message?')">  <i class="fas fa-trash"></i></a>
        </td>
    </tr>
<?php endforeach; ?>





                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Message Details -->
<!-- <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title text-danger text-center" id="messageModalLabel">Message Details</h5>
            </div>
            <div class="modal-body">
                <p id="messageContent"></p>
                <p id="messageDateTime"></p>
            </div>
        </div>
    </div>
</div> -->
<?= $this->include('script'); ?>    
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.alert').forEach(function(alert) { 
            setTimeout(function() {
                setTimeout(function() {
                    alert.remove(); 
                }); 
            }, 2000);
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Event listener for opening message details modal
        $('.message-details').click(function() {
            var message = $(this).data('message');
            var date = $(this).data('date');
            var time = $(this).data('time');

            console.log("Time:", time); // Add this line to check the time va

            $('#messageContent').text('Message: '+ ' ' + message);
            $('#messageDateTime').text('Sent on: ' + date + ' ' + formatTime(time));

            $('#messageModal').modal('show');
        });

        // Function to format time to 12-hour format
     // Function to format time to 12-hour format
function formatTime(timeString) {
    // Create a Date object from the time string
    var dateTime = new Date(timeString);
    
    // Format the time portion to HH:mm AM/PM
    var formattedTime = dateTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    // Return the formatted time
    return formattedTime;
}

    });
</script>
