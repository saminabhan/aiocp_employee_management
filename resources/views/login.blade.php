<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - إكسباند</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #047857 0%, #059669 50%, #10b981 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        /* Right Side - Form */
        .login-form-section {
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: #047857;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.3);
        }

        .logo-icon i {
            color: white;
            font-size: 2rem;
        }

        .brand-name {
            font-size: 2rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.3rem;
        }

        .brand-tagline {
            color: #666;
            font-size: 0.95rem;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-text h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .welcome-text p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            outline: none;
        }

        .form-input:focus {
            border-color: #047857;
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: #666;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #047857;
        }

        .forgot-password {
            color: #047857;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .forgot-password:hover {
            color: #059669;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(4, 120, 87, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e8e8e8;
        }

        .divider::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e8e8e8;
        }

        .divider span {
            color: #999;
            font-size: 0.85rem;
            background: white;
            padding: 0 1rem;
        }

        .social-login {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.8rem;
            border: 2px solid #e8e8e8;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #555;
        }

        .social-btn:hover {
            border-color: #047857;
            background: #f0fdf4;
        }

        .social-btn i {
            font-size: 1.2rem;
        }

        .social-btn.google i {
            color: #DB4437;
        }

        .social-btn.microsoft i {
            color: #00A4EF;
        }

        .signup-link {
            text-align: center;
            color: #666;
            font-size: 0.95rem;
        }

        .signup-link a {
            color: #047857;
            font-weight: 700;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* Left Side - Branding */
        .branding-section {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .branding-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        .features-list {
            position: relative;
            z-index: 1;
            margin-top: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            text-align: right;
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .feature-text p {
            font-size: 0.85rem;
            opacity: 0.9;
            margin: 0;
        }

        .branding-logo {
            position: relative;
            z-index: 1;
            margin-bottom: 2rem;
        }

        .branding-logo img {
            max-width: 200px;
            height: auto;
            filter: brightness(0) invert(1);
            margin-bottom: 1rem;
        }

        .branding-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .branding-subtitle {
            font-size: 1.1rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 992px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .branding-section {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .login-form-section {
                padding: 2rem 1.5rem;
            }
            
            .brand-name {
                font-size: 1.6rem;
            }
            
            .welcome-text h2 {
                font-size: 1.3rem;
            }
            
            .social-login {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Right Side - Login Form -->
        <div class="login-form-section">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-th"></i>
                </div>
                <div class="brand-name">إكسباند</div>
                <p class="brand-tagline">إدارة مالية احترافية</p>
            </div>

            <div class="welcome-text">
                <h2>مرحباً بعودتك</h2>
                <p>سجل الدخول للوصول إلى لوحة التحكم الخاصة بك</p>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-input" placeholder="example@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">كلمة المرور</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-input" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox">
                        <span>تذكرني</span>
                    </label>
                    <a href="#" class="forgot-password">نسيت كلمة المرور؟</a>
                </div>

                <button type="submit" class="login-btn">
                    <span>تسجيل الدخول</span>
                </button>
            </form>

            <div class="divider">
                <span>أو سجل الدخول باستخدام</span>
            </div>

            <div class="social-login">
                <button class="social-btn google">
                    <i class="fab fa-google"></i>
                    <span>Google</span>
                </button>
                <button class="social-btn microsoft">
                    <i class="fab fa-microsoft"></i>
                    <span>Microsoft</span>
                </button>
            </div>

            <div class="signup-link">
                ليس لديك حساب؟ <a href="#">إنشاء حساب جديد</a>
            </div>
        </div>

        <!-- Left Side - Branding -->
        <div class="branding-section">
            <div class="branding-logo">
                <!-- يمكنك وضع شعارك هنا -->
                <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-chart-line" style="font-size: 3rem;"></i>
                </div>
            </div>
            
            <h1 class="branding-title">إكسباند</h1>
            <p class="branding-subtitle">منصة متكاملة لإدارة أموالك بذكاء</p>
            
            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="feature-text">
                        <h4>تحليلات متقدمة</h4>
                        <p>تقارير تفصيلية لجميع معاملاتك المالية</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>أمان عالي</h4>
                        <p>حماية بياناتك بأحدث تقنيات التشفير</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>وصول من أي مكان</h4>
                        <p>تابع أموالك على جميع أجهزتك</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // هنا يمكنك إضافة كود تسجيل الدخول
            window.location.href = 'dashboard.html'; // الانتقال للوحة التحكم
        });
    </script>
</body>
</html>