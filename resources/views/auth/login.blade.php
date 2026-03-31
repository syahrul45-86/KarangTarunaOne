
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
       <link rel="stylesheet" href=" {{ asset('template/css/login.css') }}">
</head>
<body>
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>
    <div class="bg-circle circle-3"></div>

    <div class="container-login">
        <div class="login-card">
            <div class="header">
                <div class="logo-wrapper">
                    <svg class="logo-icon" viewBox="0 0 24 24">
                        <rect x="5" y="11" width="14" height="10" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h1>Selamat Datang</h1>
                <p class="subtitle">Login untuk melanjutkan</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="login100-form validate-form" id="loginForm">
                @csrf

                <div class="input-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        <svg class="input-icon" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <svg class="input-icon" viewBox="0 0 24 24">
                            <rect x="5" y="11" width="14" height="10" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <svg id="eye-icon" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="options">
                    <div class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span id="btn-text">Masuk</span>
                    <svg id="arrow-icon" class="arrow-icon" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <script src="{{ asset('template/js/login.js') }}"></script>

</body>
</html>
