<?= $this->include('header');?>



    <?= $this->include('sidebar');?>

        <div class="content" style="background-color:#CDE8E5">
        <?= $this->include('navbar');?>
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="rounded h-100 p-4" style="background-color: #EEF7FF">
                            <div aria-live="polite" aria-atomic="true" class="position-relative">
                                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
                                    <?php if (session()->getFlashdata('success')): ?>
                                        <div class="toast align-items-center text-dark bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                                            <div class="d-flex">
                                                <div class="toast-body">
                                                    <i class="fa fa-check-circle me-2"></i>
                                                    <?= session()->getFlashdata('success'); ?>
                                                </div>
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
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h4 class="mb-4 text-center large text-dark"> Sent Messages</h4>
                            <div class="table-responsive">
                                <table id="page" class="table-responsive table-bordered text-center">
                                    <thead>
                                        <tr style="background-color:green">
                                            <th scope="col" class="text-white text-center">Message</th>
                                            <th scope="col" class="text-white text-center">Date</th>
                                            <th scope="col" class="text-white text-center">Time</th>
                                            <th scope="col" class="text-white text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sent as $message): ?>
                                            <tr>
                                                <td class="text-dark"><?= $message['message'] ?></td>
                                                <td class="text-dark"><?= date('F j, Y', strtotime($message['date'])) ?></td>
                                                <td class="text-dark"><?= date('g:i a', strtotime($message['time'])) ?></td>
                                                <td>
                                                    <a href="deleteSentMessage/<?= $message['id']; ?>" class="btn btn-danger delete-message" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
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

        <div class="container-fluid pt-4 px-4">
            <div class="bg-secondary rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        &copy; <a href="#">Arangin Flood Monitoring System</a>, All Right Reserved 2024. 
                    </div>
                </div>
            </div>
        </div>
    </body>

    <!-- Script Includes -->
    <?= $this->include('script');?>


    <!-- Toastr Notifications -->
    <script>
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success("<?= session()->getFlashdata('success'); ?>", "", {
                "closeButton": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "showDuration": "200",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "toastClass": "toastr",
                "iconClass": "toast-success"
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            toastr.error("<?= session()->getFlashdata('error'); ?>", "", {
                "closeButton": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "showDuration": "200",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "toastClass": "toastr",
                "iconClass": "toast-error"
            });
        <?php endif; ?>
    </script>
    <script>
        let table = new DataTable("#page");
    </script>


    </html>
