{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - مشروع حصر الأضرار</title>

    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js/themes/blue/pace-theme-minimal.css">

    <script src="{{ asset('assets/js/pace.min.js') }}"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-image: url('assets/images/background-login.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
         .pace {
                pointer-events: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                    -ms-user-select: none;
                        user-select: none;
            }
            
            .pace .pace-progress {
                background: #0C4079 ;
                position: fixed;
                z-index: 2000;
                top: 0;
                right: 100%;
                width: 100%;
                height: 2px;
            }
            
            .pace .pace-progress-inner {
                display: block;
                position: absolute;
                right: 0;
                width: 100px;
                height: 100%;
                -webkit-box-shadow: 0 0 10px #0C4079 , 0 0 5px #0C4079 ;
                        box-shadow: 0 0 10px #0C4079 , 0 0 5px #0C4079 ;
                opacity: 1;
                -webkit-transform: rotate(3deg) translate(0px, -4px);
                    -ms-transform: rotate(3deg) translate(0px, -4px);
                        transform: rotate(3deg) translate(0px, -4px);
            }
            
            .pace-inactive {
                display: none;
            }
    </style>
</head>
<body>
 
@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const button = document.getElementById('loginButton');

        form.addEventListener('submit', function() {
                button.disabled = true;

            button.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                جاري التحقق...
            `;
        });
    });
</script>
</body>
</html>
