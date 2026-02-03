<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>DryClean - Register</title>
    
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
            padding: 20px 0;
        }
        
        .loginbox {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 100vh;
            display: flex;
        }
        
        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        
        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 40px;
        }
        
        .login-right-wrap {
            width: 100%;
            max-width: 400px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-control {
            height: 45px;
            border: 2px solid #e8ecef;
            border-radius: 8px;
            padding: 15px 50px 5px 15px;
            font-size: 14px;
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
            left: 10px;
            font-size: 11px;
            color: #667eea;
            background: white;
            padding: 0 5px;
        }
        
        .form-label {
            position: absolute;
            top: 12px;
            left: 15px;
            font-size: 14px;
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
        
        textarea.form-control {
            height: auto;
            padding: 15px;
            resize: vertical;
        }
        
        textarea.form-control:focus + .form-label,
        textarea.form-control:not(:placeholder-shown) + .form-label {
            top: -8px;
            left: 10px;
            font-size: 11px;
            color: #667eea;
            background: white;
            padding: 0 5px;
        }
        
        .btn-primary {
            height: 45px;
            background: linear-gradient(135deg, #58aeff 0%, #2617fa 100%);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #fee;
            color: #c33;
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
        
        @media (max-width: 768px) {
            .loginbox {
                flex-direction: column;
                min-height: auto;
            }
            
            .login-left {
                padding: 20px;
            }
            
            .login-right {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container-fluid">
                <div class="loginbox">
                    <div class="login-left">
                        {{-- <img class="img-fluid" src="{{ asset('adm/assets/img/login.png') }}" alt="DryClean"> --}}
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Join DryClean</h1>
                            <p class="account-subtitle">Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
                            
                            <h2>Create Account</h2>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    @foreach($errors->all() as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                
                                <div class="form-group">
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder=" "
                                           required>
                                    <label class="form-label">Full Name <span class="login-danger">*</span></label>
                                    <span class="profile-views"><i class="fas fa-user"></i></span>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input class="form-control @error('email') is-invalid @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder=" "
                                           required>
                                    <label class="form-label">Email <span class="login-danger">*</span></label>
                                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input class="form-control @error('phone') is-invalid @enderror" 
                                           type="text" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder=" ">
                                    <label class="form-label">Phone Number</label>
                                    <span class="profile-views"><i class="fas fa-phone"></i></span>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input class="form-control pass-input-1 @error('password') is-invalid @enderror" 
                                           type="password" 
                                           name="password" 
                                           placeholder=" "
                                           id="password1"
                                           required>
                                    <label class="form-label">Password <span class="login-danger">*</span></label>
                                    <span class="toggle-password" onclick="togglePassword1()">
                                        <i class="feather-eye" id="toggleIcon1"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input class="form-control pass-input-2" 
                                           type="password" 
                                           name="password_confirmation" 
                                           placeholder=" "
                                           id="password2"
                                           required>
                                    <label class="form-label">Confirm Password <span class="login-danger">*</span></label>
                                    <span class="toggle-password" onclick="togglePassword2()">
                                        <i class="feather-eye" id="toggleIcon2"></i>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              name="address" 
                                              rows="3" 
                                              placeholder=" ">{{ old('address') }}</textarea>
                                    <label class="form-label">Address</label>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block" type="submit">Register</button>
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
        function togglePassword1() {
            const passwordInput = document.getElementById('password1');
            const toggleIcon = document.getElementById('toggleIcon1');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('feather-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'feather-eye';
            }
        }

        function togglePassword2() {
            const passwordInput = document.getElementById('password2');
            const toggleIcon = document.getElementById('toggleIcon2');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('feather-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'feather-eye';
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
