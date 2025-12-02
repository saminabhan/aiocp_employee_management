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
/* Notification Icon */
.notification-icon {
    position: relative;
    cursor: pointer;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -8px;
    background: #dc3545;
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: 600;
    min-width: 18px;
    text-align: center;
}

/* Notification Dropdown */
/* Notification Dropdown */
.notification-dropdown {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(10px);
    width: 380px;
    max-height: 500px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    margin-top: 8px;
}

.notification-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

.notification-dropdown::before {
    content: '';
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 8px solid white;
}
.notification-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.notification-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.mark-all-read-btn {
    background: none;
    border: none;
    color: #0C4079;
    font-size: 12px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 4px;
    transition: 0.2s;
}

.mark-all-read-btn:hover {
    background: #f0f0f0;
}

.notification-list {
    flex: 1;
    overflow-y: auto;
    max-height: 400px;
}

.notification-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f5f5f5;
    cursor: pointer;
    transition: background 0.2s;
    position: relative;
}

.notification-item:hover {
    background: #f9f9f9;
}

.notification-item.unread {
    background: #f0f7ff;
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background: #0C4079;
    border-radius: 50%;
}

.notification-title {
    font-weight: 600;
    font-size: 14px;
    color: #333;
    margin-bottom: 4px;
}

.notification-message {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.notification-time {
    font-size: 11px;
    color: #999;
}

.notification-footer {
    padding: 12px 15px;
    border-top: 1px solid #eee;
    text-align: center;
}

.notification-footer a {
    color: #0C4079;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

.notification-footer a:hover {
    text-decoration: underline;
}

.empty-notifications {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.empty-notifications i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.3;
}

/* للشاشات الصغيرة */
/* للشاشات الصغيرة */
@media (max-width: 768px) {
    .notification-dropdown {
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        width: calc(100vw - 20px);
        max-width: 380px;
    }
    
    .notification-dropdown.show {
        transform: translateX(-50%) translateY(0);
    }
}    </style>
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

            <!-- <div class="nav-icon">
                <i class="fas fa-search"></i>
            </div> -->

          <div style="position: relative;">
    <div class="nav-icon notification-icon" id="notificationBtn">
        <i class="fas fa-bell"></i>
        @if(auth()->user()->unreadNotificationsCount() > 0)
        <span class="notification-badge">{{ auth()->user()->unreadNotificationsCount() }}</span>
        @endif
    </div>

    <!-- Notification Dropdown -->
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            <h6>الإشعارات</h6>
            <button class="mark-all-read-btn" id="markAllReadBtn">
                <i class="fas fa-check-double"></i> تحديد الكل كمقروء
            </button>
        </div>
        
        <div class="notification-list" id="notificationList">
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
            </div>
        </div>
        
        <div class="notification-footer">
            <a href="{{ route('notifications.index') }}">عرض جميع الإشعارات</a>
        </div>
    </div>
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

                    <a href="{{ route('sessions.index') }}" class="dropdown-item-custom">
                        <i class="fas fa-user-clock"></i>
                        <span>جلسات الدخول</span>
                    </a>

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

                <a href="{{ route('workCodes.index') }}" 
                class="nav-item {{ request()->routeIs('workCodes.*') ? 'active' : '' }}" 
                style="text-decoration: none;">
                    <i class="fas fa-layer-group"></i>
                    <span>أكواد مناطق العمل</span>
                </a>


            @if(user_can('survey.supervisor.view'))
                <a href="{{ route('governorate.supervisors.index') }}" class="nav-item {{ request()->routeIs('governorate.supervisors.*') ? 'active' : '' }}" style="text-decoration: none;">
                    <i class="fas fa-user-tie"></i>
                    <span>إدارة مشرفين الحصر</span>
                </a>
            @endif

            <!-- <div class="nav-item">
                <i class="fas fa-chart-bar"></i>
                <span>التقاربر</span>
                <div class="nav-badge">!</div>
            </div> -->
          @if(user_can('attendance.view'))
            <a href="{{ route('attendance.index') }}" 
            class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}" 
            style="text-decoration: none;">
                <i class="fas fa-business-time"></i>
                <span>الدوام اليومي</span>
            </a>
            @endif

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
<script>
// Notifications System
const notificationBtn = document.getElementById('notificationBtn');
const notificationDropdown = document.getElementById('notificationDropdown');
const notificationList = document.getElementById('notificationList');
const markAllReadBtn = document.getElementById('markAllReadBtn');

// Toggle notification dropdown
if (notificationBtn) {
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('show');
        
        if (notificationDropdown.classList.contains('show')) {
            loadNotifications();
        }
    });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!notificationDropdown.contains(e.target) && e.target !== notificationBtn) {
        notificationDropdown.classList.remove('show');
    }
});

// Load notifications via AJAX
function loadNotifications() {
    fetch('{{ route("notifications.fetch") }}')
        .then(response => response.json())
        .then(data => {
            renderNotifications(data.notifications, data.unread_count);
            updateBadge(data.unread_count);
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            notificationList.innerHTML = '<div class="empty-notifications">حدث خطأ في تحميل الإشعارات</div>';
        });
}

// Render notifications
function renderNotifications(notifications, unreadCount) {
    if (notifications.length === 0) {
        notificationList.innerHTML = `
            <div class="empty-notifications">
                <i class="fas fa-bell-slash"></i>
                <p>لا توجد إشعارات</p>
            </div>
        `;
        return;
    }

    let html = '';
    notifications.forEach(notification => {
        const unreadClass = !notification.is_read ? 'unread' : '';
        const timeAgo = getTimeAgo(notification.created_at);
        const issueLink = notification.issue_id ? `{{ url('issues') }}/${notification.issue_id}` : '#';
        
        html += `
            <div class="notification-item ${unreadClass}" 
                 data-id="${notification.id}"
                 data-issue-link="${issueLink}"
                 onclick="handleNotificationClick(${notification.id}, '${issueLink}')">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-message">${notification.message}</div>
                <div class="notification-time">
                    <i class="fas fa-clock"></i> ${timeAgo}
                </div>
            </div>
        `;
    });

    notificationList.innerHTML = html;
}

// Handle notification click
function handleNotificationClick(notificationId, link) {
    // Mark as read
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => {
        // Redirect to issue
        if (link !== '#') {
            window.location.href = link;
        }
    });
}

// Mark all as read
if (markAllReadBtn) {
    markAllReadBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        fetch('{{ route("notifications.readAll") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                Swal.fire({
                    toast: true,
                    position: 'bottom-start',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000,
                            customClass: {
            popup: 'medium-small-toast'
        }

                });
            }
        });
    });
}

// Update notification badge
function updateBadge(count) {
    const badge = document.querySelector('.notification-badge');
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            notificationBtn.insertAdjacentHTML('beforeend', 
                `<span class="notification-badge">${count}</span>`
            );
        }
    } else {
        if (badge) {
            badge.remove();
        }
    }
}

// Get time ago
function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'الآن';
    if (seconds < 3600) return `منذ ${Math.floor(seconds / 60)} دقيقة`;
    if (seconds < 86400) return `منذ ${Math.floor(seconds / 3600)} ساعة`;
    if (seconds < 604800) return `منذ ${Math.floor(seconds / 86400)} يوم`;
    return date.toLocaleDateString('ar-EG');
}

// Auto refresh notifications every 30 seconds
// setInterval(() => {
//     if (notificationDropdown.classList.contains('show')) {
//         loadNotifications();
//     } else {
        // Just update badge
//         fetch('{{ route("notifications.fetch") }}')
//             .then(response => response.json())
//             .then(data => updateBadge(data.unread_count));
//     }
// }, 30000);
</script>
</body>
</html>