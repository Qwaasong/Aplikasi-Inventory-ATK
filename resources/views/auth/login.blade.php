<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<style>
    body {
        background-color: #F0EBEB;
        font-family: "Inter", sans-serif;
    }

    .header {
        text-align: center;
        font-size: 45px;
        padding-top: 40px;
        font-weight: 600;
    }

    .form-login {
        width: 400px;
        margin: 50px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    .input-group {
        position: relative;
    }

    .input-group .form-control {
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #000;
    }

    .toggle-password {
        opacity: 0.7;
    }

    ::placeholder {
        color: #464646;
        font-size: 14px;
        font-weight: 600;
    }

    .submit {
        width: 100%;
        padding: 12px 14px;
        font-size: 16px;
        margin-top: 12px;
        font-weight: 600;
        border-radius: 6px;
        border: 2px solid #1C4D8D;
        background-color: #fff;
        color: #000;
        cursor: pointer;
    }

    .submit:hover {
        background-color: #1C4D8D;
        color: #fff;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 15px 0;
        font-size: 14px;
    }

    .remember-me input[type="checkbox"] {
        accent-color: #1C4D8D;
        cursor: pointer;
    }

    .remember-me label {
        cursor: pointer;
        font-weight: 500;
    }

    .remember-me:hover {
        opacity: 0.85;
    }
</style>

<body>
    <div class="header">Selamat Datang</div>

    <div class="form-login">
        <form action="****" method="POST">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username"
                    class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required
                    autocomplete="username" autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" required
                        autocomplete="current-password" placeholder="********">
                    <i class="fa-solid fa-eye-slash toggle-password"></i>
                </div>
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="submit">Login</button>

        </form>
    </div>

    <script>
        // Mengambil data dari elemen html
        const togglePassword = document.querySelector('.toggle-password');
        const passwordField = document.getElementById('password');

        // Menambahkan event listener pada ikon mata
        togglePassword.addEventListener('click', function() {
            // Mengganti tipe input password yang awalnya 'password' menjadi 'text' dan sebaliknya
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>
