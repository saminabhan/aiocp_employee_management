<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title', 'مشروع حصر الأضرار - قطاع غزة')</title>
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

     @stack('styles')
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js/themes/blue/pace-theme-minimal.css">

    <script src="{{ asset('assets/js/pace.min.js') }}"></script>

    <style>
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

            .work-area-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                border-radius: 18px;
                background: #f5f5f5;
                color: #666;
                font-size: 14px;
                font-weight: 600;
                border: 1px solid #f5f5f5;
                margin-inline-end: 0px;
            }

            .work-area-badge i {
                font-size: 15px;
                color: #666;
            }

    </style>
</head>
<body>
    <nav class="top-navbar">
        <div class="navbar-right">
            <div class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </div>
            <div class="brand-section">
                <div class="brand-text">مشروع حصر الأضرار - قطاع غزة</div>
            </div>
        </div>
        <div class="navbar-left">
            @php $role = Auth::user()->role->name ?? null; @endphp

            @if($role === 'survey_supervisor')
            <div class="work-area-badge">
                <span>{{ Auth::user()->mainWorkArea->name }}</span>
                    <i class="fas fa-map-marker-alt me-1"></i>
            </div>
            @endif

            <div class="nav-icon">
                <i class="fas fa-search"></i>
            </div>

            <div class="nav-icon">
                <i class="fas fa-bell"></i>
            </div>
            <!-- <div class="date-selector">
                <span>آخر 30 يوماً</span>
                <i class="fas fa-chevron-down"></i>
            </div> -->
            <div class="profile-dropdown">
@if(Auth::user()->engineer_id 
    && Auth::user()->engineer 
    && Auth::user()->engineer->personal_image)

    <img src="{{ asset('storage/' . Auth::user()->engineer->personal_image) }}"
         alt="Profile"
         class="profile-avatar"
         id="profileBtn">

@else
    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=0C4079&color=fff&size=76"
         alt="Profile"
         class="profile-avatar"
         id="profileBtn">
@endif
                <div class="dropdown-menu-custom" id="profileDropdown">
                    <div class="dropdown-header">
                        @php
                            $parts = explode(' ', trim(Auth::user()->name));
                            $first = $parts[0];
                            $last = count($parts) > 1 ? $parts[count($parts)-1] : '';
                        @endphp

                        <div class="dropdown-user-name">
                            مرحبا, {{ $first }}{{ $last ? ' ' . $last : '' }}!
                        </div>

                        <div class="dropdown-user-email">

                            @php
                                $role = Auth::user()->role;
                                $roleName = $role->name ?? null;
                                $displayName = $role->display_name ?? null;
                            @endphp

                            @if ($roleName === 'governorate_manager')
                                مدير محافظة {{ Auth::user()->governorate->name ?? '' }}

                            @elseif ($displayName)
                                {{ $displayName }}

                            @else
                                موظف
                            @endif

                        </div>
                    </div>
                    @if(Auth::user()->engineer_id && Auth::user()->engineer)
                    <a href="{{ route('engineers.profile') }}" class="dropdown-item-custom">
                        <i class="fas fa-user"></i>
                        <span>الملف الشخصي</span>
                    </a>
                    @else
                     <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
                        <i class="fas fa-user"></i>
                        <span>الملف الشخصي</span>
                    </a>
                    @endif
                   @php
                        $profileRoute = auth()->user()->can('profile.edit')
                            ? route('profile.edit')
                            : route('profile.index'); 
                    @endphp

                    <a href="{{ $profileRoute }}" class="dropdown-item-custom">
                        <i class="fas fa-cog"></i>
                        <span>إعدادات الحساب</span>
                    </a>

                    <!-- <a href="#" class="dropdown-item-custom">
                        <i class="fas fa-question-circle"></i>
                        <span>المساعدة</span>
                    </a> -->
                    <div class="dropdown-divider"></div>
                   <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item-custom logout" style="background: none; border: 0; width: 100%; text-align: right;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </nav>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-right"></i>
        </div>
        
        <div class="user-profile">
            <!-- <img src="https://ui-avatars.com/api/?name=sami+nabhan&background=047857&color=fff&size=110" 
                 alt="User" class="user-avatar"> -->
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 135px; height: auto;">
            <div class="user-name">
            @php
                $parts = explode(' ', trim(Auth::user()->name));
                $first = $parts[0];
                $last = count($parts) > 1 ? $parts[count($parts)-1] : '';
            @endphp

                مرحبا, {{ $first }}{{ $last ? ' ' . $last : '' }}!
            </div>
            <div class="user-email">

                @php
                    $role = Auth::user()->role;          
                    $roleName = $role->name ?? null;
                    $displayName = $role->display_name ?? null;
                @endphp

                @if ($roleName === 'governorate_manager')
                    مدير محافظة {{ Auth::user()->governorate->name ?? '' }}

                @elseif ($displayName)
                    {{ $displayName }}

                @else
                    موظف
                @endif

            </div>

        </div>




        <nav class="sidebar-nav">
             @if(user_can('dashboard.view'))
             <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-th-large"></i>
                <span>لوحة التحكم</span>
            </a>
            @endif

            @if(Auth::user()->engineer_id) 
                <a href="{{ route('engineers.profile') }}" 
                class="nav-item {{ request()->routeIs('engineers.profile') ? 'active' : '' }}"
                style="text-decoration: none;">
                    <i class="fas fa-id-card"></i>
                    <span>الملف الشخصي</span>
                </a>
            @endif


             @if(user_can('engineers.view'))
            <a href="{{ route('engineers.index') }}" class="nav-item {{ request()->routeIs('engineers.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-users"></i>
                <span>المهندسين</span>
            </a>
            @endif

            @if(user_can('teams.view'))
            <a href="{{ route('teams.index') }}" class="nav-item {{ request()->routeIs('teams.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-users-cog"></i>
                <span>إدارة الفرق</span>
            </a>
            @endif


             @if(user_can('constants.view'))
            <a href="{{ route('constants.index') }}" class="nav-item {{ request()->routeIs('constants.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-sliders-h"></i>
                <span>إدارة الثوابت</span>
            </a>
            @endif

            @if(user_can('survey.supervisor.view'))
                <a href="{{ route('governorate.supervisors.index') }}" class="nav-item {{ request()->routeIs('governorate.supervisors.*') ? 'active' : '' }}" style="text-decoration: none;">
                    <i class="fas fa-user-tie"></i>
                    <span>إدارة مشرفين الحصر</span>
                </a>
            @endif

            <div class="nav-item">
                <i class="fas fa-chart-bar"></i>
                <span>التقاربر</span>
                <!-- <div class="nav-badge">!</div> -->
            </div>
            <div class="nav-item">
                <i class="fas fa-business-time"></i>
                <span>الدوام اليومي</span>
            </div>

            <div class="sidebar-divider"></div>

            @if (user_can('issues.view'))
                 <a href="{{ route('issues.index') }}" class="nav-item {{ request()->routeIs('issues.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-tools"></i>
                <span>مشاكل تطبيق الحصر</span>
            </a>
            @endif
           

            @if(user_can('users.view'))
           <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-user-shield"></i>
                <span>إدارة مستخدمين النظام</span>
            </a>
            @endif

            @php
                $profileRoute = auth()->user()->can('profile.edit')
                    ? route('profile.edit')
                    : route('profile.index');
            @endphp

            <a href="{{ $profileRoute }}"
            class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"
            style="text-decoration: none;">
                <i class="fas fa-cog"></i>
                <span>إعدادات الحساب</span>
            </a>

        </nav>
    </aside>
        <main class="main-content">
            @yield('content')
        </main>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
            });
        }
        
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('mobile-active');
            sidebarOverlay.classList.toggle('show');
        });
        
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-active');
            sidebarOverlay.classList.remove('show');
        });
        
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && e.target !== profileBtn) {
                profileDropdown.classList.remove('show');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>