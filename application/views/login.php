<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head></head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cattle Management - Login</title>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Montserrat", sans-serif;
    }

    body {
      width: 100%;
      min-height: 100vh;
      padding: 0 10px;
      display: flex;
      background: url('<?php echo base_url('assets/images/product_image/5af8409ede83b.jpg') ?>') no-repeat center center fixed;
      background-size: cover;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }

    .login_form {
      width: 100%;
      max-width: 435px;
      background: #fff;
      border-radius: 12px;
      padding: 41px 30px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
      position: relative;
      z-index: 2;
    }

    .login_form h3 {
      font-size: 24px;
      text-align: center;
      margin-bottom: 20px;
      color: #171645;
    }

    .login_form p.subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 30px;
    }

    /* Updated Error Alert Styling */
    .error-container {
      background-color: #fff8f8;
      border: 1px solid #ffebee;
      border-left: 4px solid #dc3545;
      border-radius: 4px;
      margin-bottom: 20px;
      padding: 16px 40px 16px 16px; /* Increased right padding to accommodate close button */
      position: relative;
      transition: all 0.3s ease;
    }

    .error-container .error-message {
      color: #dc3545;
      font-size: 14px;
      line-height: 1.4;
      margin: 0;
    }

    .error-container .close {
      position: absolute;
      top: 8px;          /* Adjusted positioning */
      right: 8px;        /* Adjusted positioning */
      width: 24px;       /* Fixed width */
      height: 24px;      /* Fixed height */
      display: flex;     /* For centering the × symbol */
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #dc3545;
      opacity: 0.7;
      font-size: 20px;
      font-weight: 700;
      border: none;
      background: none;
      padding: 0;
      border-radius: 50%;
      transition: all 0.2s ease;
    }

    .error-container .close:hover {
      opacity: 1;
      background-color: rgba(220, 53, 69, 0.1);
    }

    form .input_box {
      margin-bottom: 20px;
    }

    form .input_box label {
      display: block;
      font-weight: 500;
      margin-bottom: 8px;
      color: #171645;
    }

    form .input_box input {
      width: 100%;
      height: 57px;
      border: 1px solid #DADAF2;
      border-radius: 5px;
      outline: none;
      background: #F8F8FB;
      font-size: 16px;
      padding: 0px 20px;
      transition: all 0.3s ease;
    }

    form .input_box input:focus {
      border-color: #626cd6;
      box-shadow: 0 0 0 3px rgba(98, 108, 214, 0.1);
    }

    .remember-me {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin-right: 8px;
    }

    .remember-me label {
      color: #666;
      font-size: 14px;
      user-select: none;
    }

    form button {
      width: 100%;
      height: 56px;
      border-radius: 5px;
      border: none;
      outline: none;
      background: #626CD6;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      text-transform: uppercase;
      cursor: pointer;
      margin-bottom: 20px;
      transition: all 0.3s ease;
    }

    form button:hover {
      background: #4954d0;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(98, 108, 214, 0.2);
    }

    form button:active {
      transform: translateY(0);
    }

    @media (max-width: 768px) {
      .login_form {
        margin: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login_form">
    <form action="<?php echo base_url('auth/login') ?>" method="post" autocomplete="off">
      <h3>Cattle Management</h3>
      <p class="subtitle">Sign in to start cattle management</p>

      <?php if(!empty($errors)): ?>
        <div class="error-container">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
          <p class="error-message"><?php echo htmlspecialchars($errors); ?></p>
        </div>
      <?php endif; ?>

      <?php if(validation_errors()): ?>
        <div class="error-container">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
          <p class="error-message"><?php echo validation_errors(); ?></p>
        </div>
      <?php endif; ?>

      <div class="input_box">
        <label for="email">Email</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          placeholder="Enter your email address" 
          required 
          autocomplete="off"
          value="<?php echo htmlspecialchars(set_value('email')); ?>"
        >
      </div>

      <div class="input_box">
        <label for="password">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          placeholder="Enter your password" 
          required 
          autocomplete="off"
        >
      </div>

      <div class="remember-me">
        <input type="checkbox" id="remember" name="remember">
        <label for="remember">Remember Me</label>
      </div>

      <button type="submit">Sign In</button>
    </form>
  </div>

  <script src="<?php echo base_url('assets/plugins/jQuery/jquery-2.2.3.min.js') ?>"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Error alert close button functionality
      const closeButtons = document.querySelectorAll('.close');
      closeButtons.forEach(button => {
        button.addEventListener('click', function() {
          this.parentElement.style.display = 'none';
        });
      });

      // Form validation
      const form = document.querySelector('form');
      form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email || !password) {
          e.preventDefault();
          alert('Please fill in all required fields');
        }
      });

      // Remember me functionality
      const rememberCheckbox = document.getElementById('remember');
      const emailInput = document.getElementById('email');
      
      // Check if there's a saved email
      const savedEmail = localStorage.getItem('rememberedEmail');
      if (savedEmail) {
        emailInput.value = savedEmail;
        rememberCheckbox.checked = true;
      }

      // Save email when remember me is checked
      rememberCheckbox.addEventListener('change', function() {
        if (this.checked) {
          localStorage.setItem('rememberedEmail', emailInput.value);
        } else {
          localStorage.removeItem('rememberedEmail');
        }
      });
    });
  </script>
</body>

</html>