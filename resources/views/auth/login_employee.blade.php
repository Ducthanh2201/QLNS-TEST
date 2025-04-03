<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập | Cổng nhân viên</title>
    
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
            height: 100vh;
            overflow: hidden;
        }
        .login-container {
            display: flex;
            height: 100vh;
        }
        .login-sidebar {
            width: 40%;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: white;
            position: relative;
        }
        .login-content {
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 60%;
            background: #f8f9fa;
        }
        .login-form {
            width: 400px;
            max-width: 100%;
        }
        .login-header {
            margin-bottom: 30px;
        }
        .login-box-logo img {
            height: 60px;
            margin-bottom: 15px;
        }
        .sidebar-content {
            text-align: center;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }
        .sidebar-content h3 {
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        .sidebar-content p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        .sidebar-image {
            margin-bottom: 30px;
        }
        .sidebar-image img {
            max-width: 250px;
        }
        .card {
            border: none;
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .card-body {
            padding: 40px;
        }
        .form-control {
            height: 45px;
            border-radius: 5px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            height: 45px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4295e3 0%, #00d9e4 100%);
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
        .login-footer {
            margin-top: 20px;
            text-align: center;
        }
        .copyright {
            margin-top: 30px;
            font-size: 14px;
            color: #6c757d;
        }
        .shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        .shape svg {
            position: relative;
            display: block;
            width: calc(125% + 1.3px);
            height: 135px;
        }
        .shape .shape-fill {
            fill: rgba(255, 255, 255, 0.1);
        }
        
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }
            .login-sidebar {
                width: 100%;
                height: 35%;
                padding: 30px;
            }
            .login-content {
                width: 100%;
                height: 65%;
                padding: 30px;
            }
            .sidebar-content h3 {
                font-size: 1.8rem;
            }
            .sidebar-content p {
                font-size: 1rem;
                margin-bottom: 15px;
            }
            .sidebar-image {
                display: none;
            }
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-container">
        <!-- Sidebar section -->
        <div class="login-sidebar">
            <div class="sidebar-content">
                <div class="sidebar-image">
                    <img src="{{ asset('img/employee-login.svg') }}" alt="Employee Portal">
                </div>
                <h3>Cổng thông tin nhân viên</h3>
                <p>Chào mừng bạn đến với hệ thống quản lý nhân sự. Đăng nhập để quản lý thông tin, chấm công và theo dõi lương thưởng.</p>
                <div class="d-flex justify-content-center">
                    <div class="bg-white p-2 rounded mr-2">
                        <img src="{{ asset('img/app-store.png') }}" height="40" alt="App Store">
                    </div>
                    <div class="bg-white p-2 rounded">
                        <img src="{{ asset('img/google-play.png') }}" height="40" alt="Google Play">
                    </div>
                </div>
            </div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
                </svg>
            </div>
        </div>
        
        <!-- Content section -->
        <div class="login-content">
            <div class="login-form">
                <div class="login-box-logo text-center">
                    <img src="{{ asset('img/logo.png') }}" alt="QLNS Logo">
                    <h4 class="mt-3">Đăng nhập hệ thống</h4>
                    <p class="text-muted">Vui lòng nhập thông tin đăng nhập của bạn</p>
                </div>
                
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('employee.login') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="employee_id">Mã nhân viên</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" placeholder="Nhập mã nhân viên" value="{{ old('employee_id') }}" required autofocus>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-id-card"></span>
                                        </div>
                                    </div>
                                    @error('employee_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Mật khẩu</label>
                                <div class="input-group mb-3">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu" required>
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
                                <div class="col-4 text-right">
                                    <a href="{{ route('employee.password.request') }}">Quên mật khẩu?</a>
                                </div>
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="login-footer">
                    <p>Không thể đăng nhập? Vui lòng liên hệ <a href="mailto:support@example.com">bộ phận IT</a></p>
                    
                    <div class="divider">
                        <span class="text-muted px-2">hoặc</span>
                    </div>
                    
                    <p class="mb-0">
                        <i class="fas fa-user-shield mr-1"></i>
                        <a href="{{ route('admin.login') }}" class="text-center">
                            Đăng nhập quản trị viên
                        </a>
                    </p>
                    
                    <div class="copyright">
                        &copy; {{ date('Y') }} QLNS - Hệ thống Quản lý Nhân sự<br>
                        <small>Version 1.0.0</small>
                    </div>
                </div>
            </div>
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