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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="sidebar pe-4 pb-3">
        <nav class="navbar bg-secondary navbar-dark">
            <a href="index.html" class="navbar-brand mx-4 mb-3" style="display: flex; align-items: center;">
                <img src="../../assets/img/eLogTech.png" alt="logo" style="width: 50px; margin-right: 10px;">
                <h3 class="small gradient-text" style="background: linear-gradient(to right, red, orange); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-fill-color: transparent; margin: 0;">eLogTech</h3>
            </a>
            <div class="d-flex align-items-center ms-4 mb-4">
                <div class="position-relative">
                    <img class="rounded-circle" src="../../assets/img/user logo.jpg" alt="" style="width: 40px; height: 40px;">
                    <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Rico Fontecilla</h6>
                    <span>Admin</span>
                </div>
            </div>
            <div class="navbar-nav w-100">
                <a href="/" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="contact" class="nav-item nav-link"><i class="fa fa-phone me-2"></i>Contact Numbers</a>
                <a href="messages" class="nav-item nav-link"><i class="fa fa-envelope me-2"></i>Messages</a>
                <a href="status" class="menu-item nav-item nav-link"><i class="fas fa-info-circle me-2"></i> Device Status</a>
                <a href="alertHistory" class="menu-item nav-item nav-link"><i class="fas fa-info-circle me-2"></i> Alert History</a>
            </div>
        </nav>
    </div>

    <div class="content">
        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-12">
                    <div class="bg-secondary rounded h-100 p-4">
                        <h5 class="mb-4 text-primary"><a href="/messages">< return</a></h5>
                        <h6 class="mb-4 text-center large text-primary">Messages</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-white">Message</th>
                                        <th scope="col" class="text-white">Date</th>
                                        <th scope="col" class="text-white">Time</th>
                                        <th scope="col" class="text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                        <tr>
                                        <td class="text-white"><?= $message['message'] ?></td>
                                        <td class="text-white"><?= date('F j, Y', strtotime($message['date'])) ?></td>
                                        <td class="text-white"><?= date('g:i a', strtotime($message['time'])) ?></td>
                                       <td>
                                       <a href="<?= base_url('delete_message/' . $message['id']) ?>" class="btn btn-danger delete-message" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
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

</html>
