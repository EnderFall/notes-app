<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page - Meeting Assistant</title>
    <link rel="icon" href="<?= base_url('assets/dash/img/ellie-logo.jpg'); ?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">

    <style>
        body {
            background: url('assets/dash/img/Login-bg.png') center center / cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Nunito', sans-serif;
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
        }

        .form-control {
            width: 90%;
            padding: 0.9rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(143, 66, 85, 0.3);
            color: #fff;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgb(255, 84, 126);
            box-shadow: 0 0 0 0.25rem rgba(255, 84, 126, 0.3);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7)
        }

        .btn-primary {
            background: rgb(7, 151, 108);
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
            background: #055e49;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div id="auth">
        <h2 class="text-center mb-4" style="font-weight: 700; color: #ff94a8;">Register Akun</h2>

        <!-- Flash error -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="text" name="nomor_hp" class="form-control" placeholder="Nomor HP / WhatsApp" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Alamat Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="btn-center">
                <button type="submit" class="btn btn-primary btn-lg shadow-lg mt-4 w-100"
                    style="background-color: #f56488;">
                    Daftar
                </button>
            </div>
            <p class="text-center mt-3">
                Sudah punya akun? <a href="<?= base_url('login') ?>" class="text-decoration-none"
                    style="color: #ff94a8;">Login</a>
            </p>
        </form>
    </div>

</body>

</html>