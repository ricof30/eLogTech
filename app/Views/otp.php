<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/img/eLogTech.png" type="image/x-icon">
    <title>e-LogTech | Email Verify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS for styling -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
            font-family: Arial, sans-serif;
        }
        .otp-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 14px;
            color: #555;
        }
        .form-control {
            padding: 10px;
            font-size: 14px;
        }
        button[type="submit"] {
            background-color: #007bff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            margin-top: 20px;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-bottom: 20px;
            transition: opacity 0.5s ease; /* Smooth transition */
        }
        .btn-link {
            font-size: 14px;
            margin-top: 10px;
            color: #007bff;
        }
        .btn-link:hover {
            text-decoration: underline;
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="otp-container">
    <h2>Activate your account</h2>

    <!-- Display Error Message -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" id="error-message">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Display Success Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" id="success-message">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('verifyOTP');?>" method="post">
    <div class="form-group">
        <label for="otp">An activation code has been sent to your email <strong><?= esc($email); ?></strong>:</label>
        <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter the activation code" required 
               maxlength="6" pattern="\d{6}" title="Please enter exactly 6 digits. Only numbers are allowed." onkeypress="return event.charCode >= 48 && event.charCode <= 57">
    </div>
    
    <!-- Hidden input to pass the user's email to the controller -->
    <input type="hidden" name="email" value="<?= esc($email); ?>">

    <button type="submit" class="btn btn-primary btn-block">Activate your account</button>
    <a href="/signin" class="btn btn-link">Back to Sign In</a>
</form>

</div>

<script>
    // Function to hide messages after 3 seconds
    window.onload = function() {
        setTimeout(function() {
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');

            if (errorMessage) {
                errorMessage.style.opacity = '0';
                setTimeout(() => errorMessage.style.display = 'none', 500); // Wait for opacity transition
            }

            if (successMessage) {
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.style.display = 'none', 500); // Wait for opacity transition
            }
        }, 2000); // 3000 milliseconds = 3 seconds
    };
</script>

</body>
</html>
