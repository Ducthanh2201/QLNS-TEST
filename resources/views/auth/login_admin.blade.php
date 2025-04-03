<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập | Quản trị hệ thống</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        .login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
        }
        .login-box {
            width: 420px;
            margin: 0 auto;
        }
        .login-logo a {
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        .login-card-body {
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        }
        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }
        .btn-primary:hover {
            background-color: #5a6fdc;
            border-color: #5a6fdc;
        }
        .input-group-text {
            width: 45px;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .divider::before {
            margin-right: 10px;
        }
        .divider::after {
            margin-left: 10px;
        }
        .social-auth-links a {
            margin-bottom: 10px;
        }
        .version {
            margin-top: 15px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <!-- Login Logo -->
        <div class="login-logo">
            <a href="/">
                <b>QLNS</b>Admin
            </a>
        </div>
        
        <!-- Login Card -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Đăng nhập hệ thống quản trị</p>
                
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                
                <form action="{{ route('admin.login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Tài khoản quản trị" value="{{ old('username') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mật khẩu" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                        </div>
                    </div>
                </form>
                
                <p class="text-center mb-1">
                    <a href="{{ route('admin.password.request') }}">Quên mật khẩu?</a>
                </p>
                
                <div class="divider">
                    <span class="text-muted px-2">hoặc</span>
                </div>
                
                <div class="social-auth-links text-center">
                    <a href="{{ route('auth.google') }}" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Đăng nhập với Google
                    </a>
                    <a href="{{ route('auth.github') }}" class="btn btn-block btn-dark">
                        <i class="fab fa-github mr-2"></i> Đăng nhập với Github
                    </a>
                </div>
                
                <div class="text-center mt-3">
                    <p class="mb-0">
                        <i class="fas fa-user mr-1"></i>
                        <a href="{{ route('employee.login') }}" class="text-center">
                            Đăng nhập dành cho nhân viên
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="text-center version">
            <p>QLNS - Hệ thống Quản lý Nhân sự</p>
            <p>Version 1.0.0</p>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>