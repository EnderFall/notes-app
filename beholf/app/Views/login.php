<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Notes Assistant</title>
    <link rel="icon" href="<?= base_url('assets/dash/img/ellie-logo.jpg'); ?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <style>
        body {background: url('assets/dash/img/Login-bg.png') center center / cover no-repeat fixed; min-height: 100vh; 
            display: flex; justify-content: center; align-items: center; font-family: 'Nunito', sans-serif;}
        #auth {background: rgba(0,0,0,0.7); backdrop-filter: blur(6px); border-radius: 15px; padding: 40px 30px; max-width: 400px; 
            width: 100%; color: #fff; box-shadow: 0 8px 25px rgba(0,0,0,0.35);}
        #auth h3 {text-align: center; margin-bottom: 30px; font-weight: 800; color: #fff;}
        .form-group {margin-bottom: 1.5rem;}
        .form-control {width: 90%; padding: 0.9rem 1rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(143, 66, 85,0.3);
            color: #fff; border-radius: 8px; transition: all 0.3s ease; font-size: 1rem;}
        .form-control:focus {background: rgba(255,255,255,0.2); border-color: rgb(255, 84, 126);
            box-shadow: 0 0 0 0.25rem rgba(255, 84, 126,0.3); outline: none;}
        .form-control::placeholder {color: rgba(255,255,255,0.7)}
        .g-recaptcha {display: flex; justify-content: center; margin-bottom: 1.5rem;}
        #math-captcha label {font-weight: 700; margin-bottom: 0.5rem; display: block; color: #eee;}
        .btn-primary {background: rgb(7, 151, 108); border: none; width: 100%; padding: 0.9rem; font-weight: 700; border-radius: 8px;
            color: #fff; cursor: pointer; transition: background 0.3s ease, transform 0.2s ease;}
        .btn-primary:hover {background: #055e49; transform: translateY(-2px);}
    </style>

</head>

<body>
    
    <div id="auth">
        <h2 class="text-center mb-4" style="font-weight: 700; color: #ff94a8;">Ellie Notes Assistant</h2>
        <form id="login-form" action="<?= base_url('login/aksi_login') ?>" method="post">
            <input type="hidden" name="correct_answer" id="correct-answer">
            <input type="hidden" name="math_answer" id="user-answer">

            <div class="form-group position-relative has-icon-left mb-3">
                <input type="text" name="usn" class="form-control form-control-lg" placeholder="Username" required>
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
            <pre></pre>
            <div class="form-group position-relative has-icon-left mb-3">
                <input type="password" name="pswd" class="form-control form-control-lg" placeholder="Password" required>
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
                        <pre></pre>

            <p class="text-end text-gray-600">
                <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none" style="color: #ff94a8;">Forgot Password?</a>
            </p>

            <div class="g-recaptcha mb-3 d-flex justify-content-center" data-sitekey="6LeZQekqAAAAAPiNKQ3qaP5Rr-UrphqwjW894Am2"></div>

            <div id="math-captcha" class="mt-3" style="display: none;">
                <label id="math-question" class="form-label fw-bold"></label>
                <input type="number" id="math-answer" class="form-control" placeholder="Jawaban Anda">
            </div>
            <pre></pre>
            <div class="btn-center">
                <button type="submit" onclick="validateCaptcha()" class="btn btn-primary btn-lg shadow-lg mt-4 w-100" style="text-color: white; background-color: #f56488;">
                    Login
                </button>
                <p class="text-center mt-3">
    Belum punya akun? <a href="<?= base_url('register') ?>" class="text-decoration-none" style="color: #ff94a8;">Daftar di sini</a>
</p>

            </div>

            <div id="google_translate_element" style="position: absolute; top: -9999px;"></div>
        </form>
    </div>

    <script>
        let questions = [
            { a: 2, b: 3, op: '+' },
            { a: 5, b: 2, op: '-' },
            { a: 1, b: 6, op: '+' },
            { a: 8, b: 4, op: '-' }
        ];

        let correctAnswer;

        function generateMathCaptcha() {
            const soal = questions[Math.floor(Math.random() * questions.length)];
            const question = Berapa hasil dari ${soal.a} ${soal.op} ${soal.b}?;
            document.getElementById('math-question').innerText = question;
            correctAnswer = soal.op === '+' ? soal.a + soal.b : soal.a - soal.b;
            document.getElementById('correct-answer').value = correctAnswer;
        }

        function validateCaptcha() {
            if (!navigator.onLine) {
                const userAnswer = parseInt(document.getElementById('math-answer').value);
                document.getElementById('user-answer').value = userAnswer;
                if (isNaN(userAnswer) || userAnswer !== correctAnswer) {
                    alert("Jawaban soal matematika salah. Silakan coba lagi.");
                    generateMathCaptcha();
                } else {
                    document.getElementById('login-form').submit();
                }
            } else {
                const response = grecaptcha.getResponse();
                if (response.length === 0) {
                    alert("Silakan lengkapi CAPTCHA terlebih dahulu.");
                } else {
                    document.getElementById('login-form').submit();
                }
            }
        }

        window.addEventListener('load', function () {
            if (!navigator.onLine) {
                document.getElementById('math-captcha').style.display = 'block';
                generateMathCaptcha();
            }
        });
    </script>
</body>
</html>