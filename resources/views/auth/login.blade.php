<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Login - DryClean</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('adm/assets/img/favicon.png') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    
    <!-- Feather CSS -->
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/feather/feather.css') }}">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/fontawesome/css/all.min.css') }}">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('adm/assets/css/style.css') }}">
    
        <style>
        body {
            background: #f8f9fa;
        }
        
        .main-wrapper.login-body {
            background: #f8f9fa;
        }
        
        .login-wrapper {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .loginbox {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-control {
            height: 50px;
            border: 2px solid #e8ecef;
            border-radius: 10px;
            padding: 15px 50px 5px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: transparent;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -8px;
            left: 15px;
            font-size: 12px;
            color: #667eea;
            background: white;
            padding: 0 5px;
        }
        
        .form-label {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 16px;
            color: #6c757d;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }
        
        .profile-views {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
            z-index: 2;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 2;
        }
        
        .btn-primary {
            height: 50px;
            background: linear-gradient(135deg, #58aeff 0%, #2617fa 100%);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 25px;
        }
        
        .alert-danger {
            background-color: #fee;
            color: #c33;
        }
        
        .alert-success {
            background-color: #efe;
            color: #363;
        }
        
        .login-or {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
        }
        
        .or-line {
            width: 100%;
            height: 1px;
            background-color: #e8ecef;
            margin: 0 10px;
        }
        
        .span-or {
            font-weight: 500;
            color: #667eea;
        }
    </style>
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        {{-- <img class="img-fluid" src="{{ asset('adm/assets/img/login.png') }}" alt="DryClean"> --}}
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Welcome to DryClean</h1>
                            <p class="account-subtitle">Need an account? <a href="{{ route('register') }}">Sign Up</a></p>
                            
                            <h2>Sign in</h2>

                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    @foreach($errors->all() as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="form-group">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input class="form-control @error('email') is-invalid @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email"
                                           required>
                                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input class="form-control pass-input @error('password') is-invalid @enderror" 
                                           type="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required>
                                    <span class="profile-views feather-eye toggle-password"></span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                

                                <div class="forgotpass">
                                    <div class="remember-me">
                                        <label class="custom_check mr-2 mb-0 d-inline-flex remember-me">
                                            Remember me
                                            <input type="checkbox" name="remember">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit">Login</button>
                                </div>
                            </form>

                            <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or">or</span>
                            </div>

                            <div class="text-center">
                                <p class="mb-2">Track your laundry status</p>
                                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>Track Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adm/assets/js/jquery-3.6.0.min.js') }}"></script>
    
    <!-- Bootstrap JS -->
    <script src="{{ asset('adm/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Feather JS -->
    <script src="{{ asset('adm/assets/js/feather.min.js') }}"></script>
    
    <!-- Main JS -->
    <script src="{{ asset('adm/assets/js/script.js') }}"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.querySelector('.pass-input');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('feather-eye');
                toggleIcon.classList.add('feather-eye-off');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.add('feather-eye-off');
                toggleIcon.classList.remove('feather-eye');
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>
