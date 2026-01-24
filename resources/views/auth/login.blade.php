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
    /* Reset dan dasar */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: #F0EBEB;
        font-family: "Inter", sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        padding: 0 16px;
    }

    /* Header */
    .header {
        text-align: center;
        font-size: 28px;
        padding-top: 40px;
        padding-bottom: 20px;
        font-weight: 600;
        color: #1C4D8D;
        line-height: 1.3;
    }

    /* Container form */
    .form-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        padding-bottom: 40px;
    }

    /* Form login */
    .form-login {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        padding: 24px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Form group */
    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 8px;
        display: block;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        font-size: 16px;
        border-radius: 8px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #1C4D8D;
        box-shadow: 0 0 0 2px rgba(28, 77, 141, 0.1);
    }

    /* Input group untuk password */
    .input-group {
        position: relative;
    }

    .input-group .form-control {
        padding-right: 50px;
    }

    .toggle-password {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
        font-size: 18px;
        padding: 4px;
        z-index: 2;
    }

    .toggle-password:hover {
        color: #1C4D8D;
    }

    /* Placeholder styling */
    ::placeholder {
        color: #777;
        font-size: 15px;
        font-weight: 500;
    }

    /* Tombol submit */
    .submit {
        width: 100%;
        padding: 16px;
        font-size: 16px;
        margin-top: 20px;
        font-weight: 600;
        border-radius: 8px;
        border: 2px solid #1C4D8D;
        background-color: #1C4D8D;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .submit:hover,
    .submit:active {
        background-color: #153a6d;
        border-color: #153a6d;
        transform: translateY(-1px);
    }

    /* Remember me checkbox */
    .remember-me {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 18px 0;
        font-size: 15px;
    }

    .remember-me input[type="checkbox"] {
        accent-color: #1C4D8D;
        cursor: pointer;
        width: 18px;
        height: 18px;
    }

    .remember-me label {
        cursor: pointer;
        font-weight: 500;
        color: #555;
        margin: 0;
    }

    /* Responsif untuk tablet */
    @media (min-width: 768px) {
        .header {
            font-size: 35px;
            padding-top: 60px;
        }

        .form-login {
            padding: 30px;
        }
    }

    /* Responsif untuk desktop */
    @media (min-width: 1024px) {
        .header {
            font-size: 45px;
            padding-top: 60px;
        }

        .form-login {
            padding: 32px;
            margin: 30px auto;
        }

        .submit:hover {
            background-color: #153a6d;
            border-color: #153a6d;
            transform: translateY(-2px);
        }
    }

    /* Responsif untuk layar sangat kecil */
    @media (max-width: 360px) {
        .header {
            font-size: 24px;
            padding-top: 30px;
            padding-bottom: 15px;
        }

        .form-login {
            padding: 20px;
        }

        .form-control {
            padding: 12px 14px;
        }
    }

    /* Animasi untuk feedback visual */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-login {
        animation: fadeIn 0.4s ease-out;
    }
</style>

<body>
    <div class="header">Selamat Datang</div>

    <div class="form-container">
        <div class="form-login">
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username"
                        class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
                        required autocomplete="username" autofocus>
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
    </div>

    <script>
        // Mengambil data dari elemen html
        const togglePassword = document.querySelector('.toggle-password');
        const passwordField = document.getElementById('password');

        // Menambahkan event listener pada ikon mata
        togglePassword.addEventListener('click', function () {
            // Mengganti tipe input password yang awalnya 'password' menjadi 'text' dan sebaliknya
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Menambahkan event listener untuk memberikan fokus yang lebih baik pada perangkat mobile
        const formControls = document.querySelectorAll('.form-control');
        formControls.forEach(control => {
            control.addEventListener('focus', function () {
                this.parentElement.style.zIndex = '1';
            });

            control.addEventListener('blur', function () {
                this.parentElement.style.zIndex = '0';
            });
        });

        // Menambahkan event listener untuk mencegah zoom pada input (khusus iOS)
        document.addEventListener('touchstart', function () { }, { passive: true });
    </script>

</body>

</html>