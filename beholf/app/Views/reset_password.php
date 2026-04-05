<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">

    <style>
        body {
            background: url('<?= base_url("assets/dash/img/Login-bg.png") ?>') center center / cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 20px;
        }

        #auth {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(6px);
            border-radius: 15px;
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
            color: #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(143, 66, 85, 0.3);
            color: #fff;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgb(255, 84, 126);
            box-shadow: 0 0 0 0.25rem rgba(255, 84, 126, 0.3);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-primary {
            background: rgb(245, 100, 136);
            border: none;
            width: 100%;
            padding: 0.9rem;
            font-weight: 700;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background: #d54b6f;
            transform: translateY(-2px);
        }
        
        .btn-primary:disabled {
            background: rgba(245, 100, 136, 0.5);
            cursor: not-allowed;
            transform: none;
        }
        
        .password-requirements {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.3rem;
        }
        
        .requirement i {
            margin-right: 0.5rem;
            font-size: 0.8rem;
        }
        
        .requirement.valid {
            color: #4CAF50;
        }
        
        .requirement.invalid {
            color: #f44336;
        }
        
        .password-strength {
            height: 5px;
            border-radius: 5px;
            margin-top: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 5px;
        }
        
        .password-strength-weak {
            background: #f44336;
            width: 25%;
        }
        
        .password-strength-medium {
            background: #ff9800;
            width: 50%;
        }
        
        .password-strength-good {
            background: #4CAF50;
            width: 75%;
        }
        
        .password-strength-strong {
            background: #2196F3;
            width: 100%;
        }
        
        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-decoration-none {
            text-decoration: none;
        }
        
        .mt-3 {
            margin-top: 1rem;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .w-100 {
            width: 100%;
        }
        
        .shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0.3rem;
        }
        
        /* Token input styling */
        .token-input {
            letter-spacing: 0.5em;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .token-input::placeholder {
            letter-spacing: normal;
            font-weight: normal;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div id="auth">
        <h2 class="text-center mb-4" style="font-weight: 700; color: #ff94a8;">Reset Password</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger text-center mb-4" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success text-center mb-4" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('reset-password') ?>" method="post" id="resetPasswordForm">
            <?= csrf_field() ?>
            <div class="form-group">
                <input type="text" name="token" class="form-control token-input" placeholder="Masukkan Token Reset" maxlength="6" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password Baru" required>
                
                <!-- Password Requirements -->
                <div class="password-requirements">
                    <div class="requirement invalid" id="lowercase">
                        <i class="bi bi-x-circle"></i> Setidaknya satu huruf kecil
                    </div>
                    <div class="requirement invalid" id="uppercase">
                        <i class="bi bi-x-circle"></i> Setidaknya satu huruf besar
                    </div>
                    <div class="requirement invalid" id="number">
                        <i class="bi bi-x-circle"></i> Setidaknya satu angka
                    </div>
                    <div class="requirement invalid" id="special">
                        <i class="bi bi-x-circle"></i> Setidaknya satu karakter khusus
                    </div>
                    <div class="requirement invalid" id="length">
                        <i class="bi bi-x-circle"></i> Minimal 8 karakter
                    </div>
                </div>
                
                <!-- Password Strength Meter -->
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Konfirmasi Password Baru" required>
                <div class="password-requirements">
                    <div class="requirement invalid" id="passwordMatch">
                        <i class="bi bi-x-circle"></i> Password harus cocok
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg shadow-lg mt-3 mb-4" id="submitButton" disabled>
                Reset Password
            </button>

            <p class="text-center mt-3">
                <a href="<?= base_url('login') ?>" class="text-decoration-none" style="color: #ff94a8;">Kembali ke Login</a>
            </p>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const submitButton = document.getElementById('submitButton');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');
            
            // Password requirement elements
            const lowercaseReq = document.getElementById('lowercase');
            const uppercaseReq = document.getElementById('uppercase');
            const numberReq = document.getElementById('number');
            const specialReq = document.getElementById('special');
            const lengthReq = document.getElementById('length');
            const passwordMatchReq = document.getElementById('passwordMatch');
            
            // Regular expressions for password validation
            const lowercaseRegex = /[a-z]/;
            const uppercaseRegex = /[A-Z]/;
            const numberRegex = /[0-9]/;
            const specialRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
            
            function validatePassword() {
                const password = passwordInput.value;
                let isValid = true;
                let strength = 0;
                
                // Check lowercase
                if (lowercaseRegex.test(password)) {
                    lowercaseReq.classList.remove('invalid');
                    lowercaseReq.classList.add('valid');
                    lowercaseReq.querySelector('i').className = 'bi bi-check-circle';
                    strength += 20;
                } else {
                    lowercaseReq.classList.remove('valid');
                    lowercaseReq.classList.add('invalid');
                    lowercaseReq.querySelector('i').className = 'bi bi-x-circle';
                    isValid = false;
                }
                
                // Check uppercase
                if (uppercaseRegex.test(password)) {
                    uppercaseReq.classList.remove('invalid');
                    uppercaseReq.classList.add('valid');
                    uppercaseReq.querySelector('i').className = 'bi bi-check-circle';
                    strength += 20;
                } else {
                    uppercaseReq.classList.remove('valid');
                    uppercaseReq.classList.add('invalid');
                    uppercaseReq.querySelector('i').className = 'bi bi-x-circle';
                    isValid = false;
                }
                
                // Check number
                if (numberRegex.test(password)) {
                    numberReq.classList.remove('invalid');
                    numberReq.classList.add('valid');
                    numberReq.querySelector('i').className = 'bi bi-check-circle';
                    strength += 20;
                } else {
                    numberReq.classList.remove('valid');
                    numberReq.classList.add('invalid');
                    numberReq.querySelector('i').className = 'bi bi-x-circle';
                    isValid = false;
                }
                
                // Check special character
                if (specialRegex.test(password)) {
                    specialReq.classList.remove('invalid');
                    specialReq.classList.add('valid');
                    specialReq.querySelector('i').className = 'bi bi-check-circle';
                    strength += 20;
                } else {
                    specialReq.classList.remove('valid');
                    specialReq.classList.add('invalid');
                    specialReq.querySelector('i').className = 'bi bi-x-circle';
                    isValid = false;
                }
                
                // Check length
                if (password.length >= 8) {
                    lengthReq.classList.remove('invalid');
                    lengthReq.classList.add('valid');
                    lengthReq.querySelector('i').className = 'bi bi-check-circle';
                    strength += 20;
                } else {
                    lengthReq.classList.remove('valid');
                    lengthReq.classList.add('invalid');
                    lengthReq.querySelector('i').className = 'bi bi-x-circle';
                    isValid = false;
                }
                
                // Update password strength bar
                passwordStrengthBar.className = 'password-strength-bar';
                if (strength <= 25) {
                    passwordStrengthBar.classList.add('password-strength-weak');
                } else if (strength <= 50) {
                    passwordStrengthBar.classList.add('password-strength-medium');
                } else if (strength <= 75) {
                    passwordStrengthBar.classList.add('password-strength-good');
                } else {
                    passwordStrengthBar.classList.add('password-strength-strong');
                }
                
                return isValid;
            }
            
            function validatePasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword && password === confirmPassword) {
                    passwordMatchReq.classList.remove('invalid');
                    passwordMatchReq.classList.add('valid');
                    passwordMatchReq.querySelector('i').className = 'bi bi-check-circle';
                    return true;
                } else {
                    passwordMatchReq.classList.remove('valid');
                    passwordMatchReq.classList.add('invalid');
                    passwordMatchReq.querySelector('i').className = 'bi bi-x-circle';
                    return false;
                }
            }
            
            function validateForm() {
                const isPasswordValid = validatePassword();
                const isPasswordMatch = validatePasswordMatch();
                
                submitButton.disabled = !(isPasswordValid && isPasswordMatch);
            }
            
            // Event listeners
            passwordInput.addEventListener('input', validateForm);
            confirmPasswordInput.addEventListener('input', validateForm);
            
            // Token input formatting
            const tokenInput = document.querySelector('input[name="token"]');
            tokenInput.addEventListener('input', function() {
                // Convert to uppercase
                this.value = this.value.toUpperCase();
                
                // Remove non-alphanumeric characters
                this.value = this.value.replace(/[^A-Z0-9]/g, '');
            });
            
            // Initial validation
            validateForm();
        });
    </script>
</body>

</html>