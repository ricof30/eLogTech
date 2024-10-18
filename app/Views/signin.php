<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>e-LogTech</title>
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="../assets/img/eLogTech.png" type="image/x-icon">
  
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <style>
    /* Google Font Link */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #EEF7FF;
  padding: 30px;
}

.container {
  position: relative;
  max-width: 850px;
  width: 100%;
  background: #fff;
  padding: 40px 30px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  perspective: 2700px;
}

.container .cover {
  position: absolute;
  top: 0;
  left: 50%;
  height: 100%;
  width: 50%;
  z-index: 98;
  transition: all 1s ease;
  transform-origin: left;
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

.container #flip:checked ~ .cover {
  transform: rotateY(-180deg);
}

.container #flip:checked ~ .forms .login-form {
  pointer-events: none;
}

.container .cover .front,
.container .cover .back {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
}

.cover .back {
  transform: rotateY(180deg);
}

.container .cover img {
  position: absolute;
  height: 100%;
  width: 100%;
  object-fit: cover;
  z-index: 10;
}

.container .cover .text {
  position: absolute;
  z-index: 10;
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.container .cover .text::before {
  content: '';
  position: absolute;
  height: 100%;
  width: 100%;
  opacity: 0.5;
  background: #7d2ae8;
}

.cover .text .text-1,
.cover .text .text-2 {
  z-index: 20;
  font-size: 26px;
  font-weight: 600;
  color: #fff;
  text-align: center;
}

.cover .text .text-2 {
  font-size: 15px;
  font-weight: 500;
}

.container .forms {
  height: 100%;
  width: 100%;
  background: #fff;
}

.container .form-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.form-content .login-form,
.form-content .signup-form {
  width: calc(100% / 2 - 25px);
}

.forms .form-content .title {
  position: relative;
  font-size: 24px;
  font-weight: 500;
  color: #333;
}

.forms .form-content .title:before {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  height: 3px;
  width: 25px;
  background: #7d2ae8;
}

.forms .signup-form .title:before {
  width: 20px;
}

.forms .form-content .input-boxes {
  margin-top: 30px;
}

.forms .form-content .input-box {
  display: flex;
  align-items: center;
  height: 50px;
  width: 100%;
  margin: 10px 0;
  position: relative;
}

.form-content .input-box input {
  height: 100%;
  width: 100%;
  outline: none;
  border: none;
  padding: 0 30px;
  font-size: 16px;
  font-weight: 500;
  border-bottom: 2px solid rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.form-content .input-box input:focus,
.form-content .input-box input:valid {
  border-color: #7d2ae8;
}

.form-content .input-box i {
  position: absolute;
  color: #7d2ae8;
  font-size: 17px;
}

.forms .form-content .text {
  font-size: 14px;
  font-weight: 500;
  color: #333;
}

.forms .form-content .text a {
  text-decoration: none;
}

.forms .form-content .text a:hover {
  text-decoration: underline;
}

.forms .form-content .button {
  color: #fff;
  margin-top: 40px;
}

.forms .form-content .button input {
  color: #fff;
  background: #7d2ae8;
  border-radius: 6px;
  padding: 0;
  cursor: pointer;
  transition: all 0.4s ease;
}

.forms .form-content .button input:hover {
  background: #5b13b9;
}

.forms .form-content label {
  color: #5b13b9;
  cursor: pointer;
}

.forms .form-content label:hover {
  text-decoration: underline;
}

.forms .form-content .login-text,
.forms .form-content .sign-up-text {
  text-align: center;
  margin-top: 25px;
}

.container #flip {
  display: none;
}

@media (max-width: 730px) {
  .container .cover {
    display: none;
  }

  .form-content .login-form,
  .form-content .signup-form {
    width: 100%;
  }

  .form-content .signup-form {
    display: none;
  }

  .container #flip:checked ~ .forms .signup-form {
    display: block;
  }

  .container #flip:checked ~ .forms .login-form {
    display: none;
  }
}

.error-box {
    margin-top: 5px;
}
.google-signin-btn {
    background-color: #4285F4;           /* Google blue background */
    display: flex;                        /* Flexbox for layout */
    align-items: center;                  /* Center vertically */
    justify-content: flex-start;          /* Align items to the start (left) */
    width: 100%;                          /* Full width */
    padding: 10px;                        /* Padding for the button */
    border: none;                         /* Remove border */
    border-radius: 5px;                  /* Rounded corners */
    cursor: pointer;                      /* Pointer cursor */
    gap: 20px;                            /* Space between image and text */
}

.google-signin-btn img {
    width: 40px;   
    height: 30px;                        /* Google logo size */
    border-radius: 6px;                  /* Rounded corners for the image */
    padding: 2px;                        /* Padding inside the image border */
    background-color: #4285F4;           /* Background color to match button */
}

.google-signin-btn span {
    margin-left: 50px;                   /* Space between the image and text */
    color: white; 
}

@media (max-width: 768px) {
    .google-signin-btn {
        gap: 10px;                       /* Reduce gap on smaller screens */
    }

    .google-signin-btn img {
        width: 35px;                     /* Adjust image size */
        height: 25px;                    /* Adjust image size */
    }

    .google-signin-btn span {
        margin-left: 20px;               /* Adjust margin for text */
    }

    .forms {
        padding: 10px;                   /* Reduce padding on smaller screens */
    }
}

@media (max-width: 480px) {
    .google-signin-btn {
        padding: 8px;                    /* Adjust padding for smaller devices */
    }

    .google-signin-btn img {
        width: 30px;                     /* Further adjust image size */
        height: 20px;                    /* Further adjust image size */
    }

    .google-signin-btn span {
        font-size: 14px;                 /* Adjust font size for smaller devices */
    }

    .forms {
        padding: 5px;                    /* Further reduce padding on smallest screens */
    }
}
  </style>
</head>
<body>
  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img src="../assets/img/floodlogin.jpg" alt="">
        <div class="text">
            <span class="text-1">Stay informed, stay safe <br> with flood updates</span>
            <span class="text-2">Monitor your environment</span>
        </div>
      </div>
      <div class="back">
        <img class="backImg" src="../assets/img/floodlogin.jpg" alt="">
        <div class="text">
            <span class="text-1">Preparedness is key <br> in flood situations</span>
            <span class="text-2">Let's stay alert</span>
        </div>
      </div>
    </div>
    
    <div class="forms">
      <div class="form-content">
        <div class="login-form">
          <div class="title">Login</div>
          <form action="<?= base_url('adminSignin');?>" method="post">
                  <div class="input-boxes">
                    <div class="input-box">
                      <i class="fas fa-envelope"></i>
                      <input type="text" placeholder="Enter your email" name="email" required>
                    </div>
                    <div class="input-box">
                       <i class="fas fa-lock"></i>
                      <input id="loginPassword" type="password" placeholder="Enter your password" name="password" required>
                  <i class="fas fa-eye" id="toggleLoginPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    <div class="text"><a href="#">Forgot password?</a></div>
                    <div class="button input-box">
                      <input type="submit" value="Submit">
                    </div>
                    <div class="text sign-up-text">Don't have an account? <label for="flip">Signup now</label></div>
                    <div class="text-center" style="margin-top: 20px;">
                      <a href="<?= base_url('google-login');?>" style="text-decoration:none">
                        <button type="button" class="google-signin-btn  text-center">
                        <img src="../assets/img/google.png" alt="Google Logo">
                        <span class="text-center">Sign in with Google</span>
                      </button>
                      </a>
                </div>
                  </div>
          </form>  
        </div>
        
        <div class="signup-form">
          <div class="title">Signup</div>
          <form id="signupForm" action="<?= base_url('signUp');?>" method="post">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Enter your name" name="name" required>
              </div>
              <div class="input-box">
                <i class="fas fa-phone"></i>
                <input type="number" placeholder="Enter your phone number" name="contact" required>
              </div>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" placeholder="Enter your email" name="email" required>
              </div>
              <div class="input-box" style="position: relative;">
                  <i class="fas fa-lock"></i>
                  <input id="password" type="password" placeholder="Enter your password" name="password" required style="padding-left: 30px;">
                  <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
              </div>
              <div class="error-box">
                  <span id="password-error" style="color: red; display: none;">Password must have at least one uppercase letter, one lowercase letter, one number, and one special character!</span>
              </div>

              <div class="input-box" style="position: relative;">
                  <i class="fas fa-lock"></i>
                  <input id="confirm_password" type="password" placeholder="Confirm password" name="confirm_pass" required style="padding-left: 30px;">
                  <i class="fas fa-eye" id="toggleConfirmPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
              </div>

              <!-- Password mismatch error message -->
              <div class="error-box">
                <span id="error-message" style="color: red; display: none;">Passwords do not match!</span>
              </div>
              <div class="button input-box">
                <input type="submit" value="Submit">
              </div>
              <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
            </div>
  </form>
</div>




      </div>
    </div>
  </div>

  <!-- Toastr JS -->
   <?= $this->include('script');?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



  <script>
    // Toastr options
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "100",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    // Display Toastr error message if set in session
    <?php if (session()->getFlashdata('error')): ?>
      toastr.error("<?= session()->getFlashdata('error'); ?>");
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
      toastr.success("<?= session()->getFlashdata('success'); ?>");
    <?php endif; ?>
  </script>

  <script>

  // Real-time validation for password input
document.getElementById('password').addEventListener('input', function () {
    var password = this.value;
    var passwordError = document.getElementById('password-error');

    // Regular expression for password validation (must contain upper, lower, number, and special characters)
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;

    // Reset password error state
    passwordError.style.display = 'none';

    // Check if password matches the validation criteria
    if (password !== '' && !passwordPattern.test(password)) {
        passwordError.style.display = 'inline';
        passwordError.textContent = "Password must have at least one uppercase letter, one lowercase letter, one number, and one special character!";
    }
});

// Real-time validation for confirm password input
document.getElementById('confirm_password').addEventListener('input', function () {
    var password = document.getElementById('password').value;
    var confirmPassword = this.value;
    var confirmError = document.getElementById('error-message');

    // Reset confirm password error state
    confirmError.style.display = 'none';

    // Check if confirm password matches the password
    if (confirmPassword !== '' && password !== confirmPassword) {
        confirmError.style.display = 'inline';
        confirmError.textContent = "Passwords do not match!";
    }
});

// Form submission event to check validation on submit
document.getElementById('signupForm').addEventListener('submit', function(event) {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var passwordError = document.getElementById('password-error');
    var confirmError = document.getElementById('error-message');

    // Regular expression for password validation (must contain upper, lower, number, and special characters)
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;

    // Reset error states
    passwordError.style.display = 'none';
    confirmError.style.display = 'none';

    var hasError = false; // Variable to track if there's any error

    // Check if password meets the validation criteria
    if (!passwordPattern.test(password)) {
        passwordError.style.display = 'inline';
        passwordError.textContent = "Password must have at least one uppercase letter, one lowercase letter, one number, and one special character!";
        hasError = true;
    }

    // Check if confirm password matches the password
    if (password !== confirmPassword) {
        confirmError.style.display = 'inline';
        confirmError.textContent = "Passwords do not match!";
        hasError = true;
    }

    // Prevent form submission if there's any error
    if (hasError) {
        event.preventDefault();
    }
});

  </script>

  
</body>
</html>
