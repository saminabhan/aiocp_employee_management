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

    </style>
</head>
<body>
    <!-- Top Navbar -->
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
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=0C4079&color=fff&size=76" 
                alt="Profile" class="profile-avatar" id="profileBtn">
                <div class="dropdown-menu-custom" id="profileDropdown">
                    <div class="dropdown-header">
                        <div class="dropdown-user-name">مرحبا, {{ Auth::user()->name }} !</div>
                        <div class="dropdown-user-email">
                            @if (Auth::user()->role->name === 'governorate_manager')
                            مدير محافظة 
                            {{  Auth::user()->governorate->name }}           
                            @elseif (Auth::user()->role?->display_name)
                            {{ Auth::user()->role->display_name }}
                            @else
                            موظف
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
                        <i class="fas fa-user"></i>
                        <span>الملف الشخصي</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item-custom">
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

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-right"></i>
        </div>
        
        <div class="user-profile">
            <!-- <img src="https://ui-avatars.com/api/?name=sami+nabhan&background=047857&color=fff&size=110" 
                 alt="User" class="user-avatar"> -->
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 135px; height: auto;">
            <div class="user-name">مرحبا, {{ Auth::user()->name }} !</div>
            <div class="user-email">
                @if (Auth::user()->role->name === 'governorate_manager')
                مدير محافظة 
                {{  Auth::user()->governorate->name }}           
                @elseif (Auth::user()->role?->display_name)
                {{ Auth::user()->role->display_name }}
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

             @if(user_can('engineers.view'))
            <a href="{{ route('engineers.index') }}" class="nav-item {{ request()->routeIs('engineers.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-users"></i>
                <span>المهندسين</span>
            </a>
            @endif

             @if(user_can('constants.view'))
            <a href="{{ route('constants.index') }}" class="nav-item {{ request()->routeIs('constants.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-sliders-h"></i>
                <span>إدارة الثوابت</span>
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

            <!-- <div class="nav-item">
                <i class="fas fa-users"></i>
                <span>حسابيات</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-chart-bar"></i>
                <span>التقارير</span>
            </div> -->

            <div class="sidebar-divider"></div>

            @if(user_can('users.view'))
           <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-user-shield"></i>
                <span>إدارة مستخدمين النظام</span>
            </a>
            @endif

             <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" style="text-decoration: none;">
                <i class="fas fa-cog"></i>
                <span>إعدادات الحساب</span>
            </a>

            <!-- <div class="nav-item">
                <i class="fas fa-question-circle"></i>
                <span>المساعدة</span>
            </div> -->
        </nav>
    </aside>
        <main class="main-content">
            @yield('content')
        </main>
    <!-- Main Content -->
    <!-- <main class="main-content">
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-home stat-icon"></i>
                <div class="card-menu">
                    <i class="fas fa-ellipsis-h"></i>
                </div>
                <div class="stat-label">مصروف الإيجار</div>
                <div class="stat-value">إيجار المنزل</div>
                <div class="stat-change">1150 ريال</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-piggy-bank stat-icon"></i>
                <div class="card-menu">
                    <i class="fas fa-ellipsis-h"></i>
                </div>
                <div class="stat-badge">عرض التفاصيل</div>
                <div class="stat-label">إجمالي المدخرات</div>
                <div class="stat-value">17,550 ريال</div>
                <div class="stat-change down">
                    <i class="fas fa-arrow-down"></i>
                    <span>3% مقابل آخر 30 يوماً</span>
                </div>
            </div>

            <div class="stat-card featured">
                <i class="fas fa-wallet stat-icon"></i>
                <div class="card-menu">
                    <i class="fas fa-ellipsis-h"></i>
                </div>
                <div class="stat-label">المصاريف الكلية</div>
                <div class="stat-value">27,450 ريال</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-down"></i>
                    <span>2% مقابل آخر 30 يوماً</span>
                </div>
            </div>

            <div class="stat-card">
                <i class="fas fa-arrow-down stat-icon"></i>
                <div class="card-menu">
                    <i class="fas fa-ellipsis-h"></i>
                </div>
                <div class="stat-label">إجمالي الدخل</div>
                <div class="stat-value">45,000 ريال</div>
                <div class="stat-change up">
                    <i class="fas fa-arrow-up"></i>
                    <span>6% مقابل آخر 30 يوماً</span>
                </div>
            </div>
        </div>

        <div class="middle-section">
            <div class="chart-card">
                <div class="card-title">المصاريف الحديثة</div>
                <div class="expense-list">
                    <div class="expense-row">
                        <div class="expense-info">
                            <h4>تلاجة</h4>
                            <p>2 ديسمبر 2/23</p>
                        </div>
                        <div class="expense-amount">550 ريال</div>
                    </div>
                    <div class="expense-row">
                        <div class="expense-info">
                            <h4>فاتورة الإنترنت</h4>
                            <p>22 ديسمبر 12/22</p>
                        </div>
                        <div class="expense-amount">17 ريال</div>
                    </div>
                    <div class="expense-row">
                        <div class="expense-info">
                            <h4>البنات الداخلية</h4>
                            <p>21 ديسمبر 12/21</p>
                        </div>
                        <div class="expense-amount">96 ريال</div>
                    </div>
                    <div class="expense-row">
                        <div class="expense-info">
                            <h4>بنفل</h4>
                            <p>12 ديسمبر 12/12</p>
                        </div>
                        <div class="expense-amount">11 ريال</div>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header">
                    <div class="card-menu">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                    <div class="card-title">أعلى 10 مصادر المصروفات</div>
                </div>
                <div class="bar-chart-container">
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 60%;">
                            <div class="bar-tooltip">376 ريال</div>
                        </div>
                        <div class="bar-label">إصلاحات</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 88%;">
                            <div class="bar-tooltip">3519 ريال</div>
                        </div>
                        <div class="bar-label">إيجار المنزل</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 73%;">
                            <div class="bar-tooltip">490 ريال</div>
                        </div>
                        <div class="bar-label">الشرحيون</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 52%;">
                            <div class="bar-tooltip">270 ريال</div>
                        </div>
                        <div class="bar-label">بنقل</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 62%;">
                            <div class="bar-tooltip">310 ريال</div>
                        </div>
                        <div class="bar-label">حاسوب محمول</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 68%;">
                            <div class="bar-tooltip">360 ريال</div>
                        </div>
                        <div class="bar-label">مكالم دول</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 57%;">
                            <div class="bar-tooltip">285 ريال</div>
                        </div>
                        <div class="bar-label">تيار كهرباء</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 45%;">
                            <div class="bar-tooltip">190 ريال</div>
                        </div>
                        <div class="bar-label">طريق نفل</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 66%;">
                            <div class="bar-tooltip">350 ريال</div>
                        </div>
                        <div class="bar-label">مدرسة</div>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="height: 71%;">
                            <div class="bar-tooltip">395 ريال</div>
                        </div>
                        <div class="bar-label">التنظفات</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-section">
            <div class="chart-card">
                <div class="card-header">
                    <div class="chart-legend">
                        <div class="legend-item legend-inactive">
                            <span class="legend-dot" style="background: #ccc;"></span>
                            <span>المصاريف المتوقعة</span>
                        </div>
                        <div class="legend-item legend-active">
                            <span class="legend-dot" style="background: #0C4079;"></span>
                            <span>المصاريف الفعلية</span>
                        </div>
                    </div>
                    <div class="card-title">نشاط المصاريف</div>
                </div>
                <div class="line-chart-wrapper">
                    <svg viewBox="0 0 550 220" style="overflow: visible;">
                        <line x1="0" y1="180" x2="550" y2="180" stroke="#f0f0f0" stroke-width="1"/>
                        <line x1="0" y1="135" x2="550" y2="135" stroke="#f0f0f0" stroke-width="1"/>
                        <line x1="0" y1="90" x2="550" y2="90" stroke="#f0f0f0" stroke-width="1"/>
                        <line x1="0" y1="45" x2="550" y2="45" stroke="#f0f0f0" stroke-width="1"/>
                        
                        <polyline points="10,85 60,100 110,95 160,70 210,45 260,75 310,60 360,65 410,75 460,85 510,80" 
                                  fill="none" stroke="#0C4079" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        
                        <circle cx="10" cy="85" r="4" fill="#0C4079"/>
                        <circle cx="60" cy="100" r="4" fill="#0C4079"/>
                        <circle cx="110" cy="95" r="4" fill="#0C4079"/>
                        <circle cx="160" cy="70" r="4" fill="#0C4079"/>
                        <circle cx="210" cy="45" r="4" fill="#0C4079"/>
                        <circle cx="260" cy="75" r="4" fill="#0C4079"/>
                        <circle cx="310" cy="60" r="4" fill="#0C4079"/>
                        <circle cx="360" cy="65" r="4" fill="#0C4079"/>
                        <circle cx="410" cy="75" r="4" fill="#0C4079"/>
                        <circle cx="460" cy="85" r="4" fill="#0C4079"/>
                        <circle cx="510" cy="80" r="4" fill="#0C4079"/>
                        
                        <rect x="180" y="30" width="60" height="20" fill="#0C4079" rx="4"/>
                        <text x="210" y="44" text-anchor="middle" fill="white" font-size="11" font-weight="600">3519 ریال</text>
                        
                        <text x="10" y="200" text-anchor="middle" fill="#999" font-size="10">1</text>
                        <text x="60" y="200" text-anchor="middle" fill="#999" font-size="10">2</text>
                        <text x="110" y="200" text-anchor="middle" fill="#999" font-size="10">3</text>
                        <text x="160" y="200" text-anchor="middle" fill="#999" font-size="10">4</text>
                        <text x="210" y="200" text-anchor="middle" fill="#999" font-size="10">5</text>
                        <text x="260" y="200" text-anchor="middle" fill="#999" font-size="10">6</text>
                        <text x="310" y="200" text-anchor="middle" fill="#999" font-size="10">7</text>
                        <text x="360" y="200" text-anchor="middle" fill="#999" font-size="10">8</text>
                        <text x="410" y="200" text-anchor="middle" fill="#999" font-size="10">9</text>
                        <text x="460" y="200" text-anchor="middle" fill="#999" font-size="10">10</text>
                        <text x="510" y="200" text-anchor="middle" fill="#999" font-size="10">11</text>
                    </svg>
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header">
                    <div class="card-menu">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                    <div class="card-title">نظرة عامة على التقرير</div>
                </div>
                <div class="donut-wrapper">
                    <div class="donut-chart">
                        <svg viewBox="0 0 200 200">
                            <circle cx="100" cy="100" r="70" fill="none" stroke="#e8e8e8" stroke-width="35"/>
                            
                            <circle cx="100" cy="100" r="70" fill="none" stroke="#0C4079" stroke-width="35"
                                    stroke-dasharray="308 440" transform="rotate(-90 100 100)"/>
                            
                            <circle cx="100" cy="100" r="70" fill="none" stroke="#50BEF9" stroke-width="35"
                                    stroke-dasharray="88 440" stroke-dashoffset="-308" transform="rotate(-90 100 100)"/>
                            
                            <circle cx="100" cy="100" r="70" fill="none" stroke="#1e293b" stroke-width="35"
                                    stroke-dasharray="44 440" stroke-dashoffset="-396" transform="rotate(-90 100 100)"/>
                            
                            <text x="100" y="95" text-anchor="middle" fill="#0C4079" font-size="32" font-weight="700">70%</text>
                            <text x="100" y="115" text-anchor="middle" fill="#999" font-size="13">مصروفات</text>
                        </svg>
                    </div>
                    
                    <div class="legend-list">
                        <div class="legend-row">
                            <div class="legend-left">
                                <div class="legend-color" style="background: #0C4079;"></div>
                                <span class="legend-text">مصروفات</span>
                            </div>
                            <div class="legend-value">
                                <i class="fas fa-arrow-down legend-icon" style="color: #ef4444;"></i>
                                27,450 ريال
                            </div>
                        </div>
                        <div class="legend-row">
                            <div class="legend-left">
                                <div class="legend-color" style="background: #50BEF9;"></div>
                                <span class="legend-text">دخل</span>
                            </div>
                            <div class="legend-value">
                                <i class="fas fa-arrow-up legend-icon" style="color: #50BEF9;"></i>
                                45,000 ريال
                            </div>
                        </div>
                        <div class="legend-row">
                            <div class="legend-left">
                                <div class="legend-color" style="background: #1e293b;"></div>
                                <span class="legend-text">مدخرات</span>
                            </div>
                            <div class="legend-value">
                                <i class="fas fa-arrow-down legend-icon" style="color: #ef4444;"></i>
                                17,550 ريال
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')

    <script>
        // Sidebar toggle for desktop
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
            });
        }
        
        // Mobile menu
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
        
        // Profile dropdown toggle
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && e.target !== profileBtn) {
                profileDropdown.classList.remove('show');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>