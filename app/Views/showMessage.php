<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>eLogTech</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../../assets/img/eLogTech.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="sidebar pe-4 pb-3">
        <!-- Sidebar content -->
    </div>

    <div class="content">
        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-12">
                    <div class="bg-secondary rounded h-100 p-4">
                        <h6 class="mb-4 text-center large text-primary"> Sent Messages</h6>
                        
                        <!-- Toast Container -->
                        <div aria-live="polite" aria-atomic="true" class="position-relative">
                            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
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
                                    <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
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

                        <div class="table-responsive">
                            <table id="page" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-white">Message</th>
                                        <th scope="col" class="text-white">Date</th>
                                        <th scope="col" class="text-white">Time</th>
                                        <th scope="col" class="text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sentSMS as $message): ?>
                                        <tr>
                                            <td class="text-white"><?= $message['message'] ?></td>
                                            <td class="text-white"><?= date('F j, Y', strtotime($message['date'])) ?></td>
                                            <td class="text-white"><?= date('g:i a', strtotime($message['time'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('deleteSentMessage/' . $message['id']) ?>" class="btn btn-danger delete-message" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
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

        <div class="container-fluid pt-4 px-4">
            <div class="bg-secondary rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        &copy; <a href="#">Arangin Flood Monitoring System</a>, All Right Reserved 2024. 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include your script file -->
    <?= $this->include('script'); ?>

    <script>
        // Initialize DataTable
        let table = new DataTable('#page');

        // Ensure toast displays on page load
        document.addEventListener('DOMContentLoaded', function() {
            let successToast = document.querySelector('.toast.bg-success');
            let errorToast = document.querySelector('.toast.bg-danger');
            
            if (successToast || errorToast) {
                let toast = new bootstrap.Toast(successToast || errorToast);
                toast.show();
            }
        });
    </script>

</body>
</html>
