@extends('layouts.app')

@section('title', 'الإشعارات')

@push('styles')
<style>
    .notifications-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .notifications-header {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notifications-header h4 {
        margin: 0;
        color: #333;
        font-weight: 600;
    }

    .unread-badge {
        background: #dc3545;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-right: 10px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-mark-all {
        background: #0C4079;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-mark-all:hover {
        background: #082c53;
    }

    .notification-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
        position: relative;
        border-right: 4px solid transparent;
    }

    .notification-card.unread {
        background: #f0f7ff;
        border-right-color: #0C4079;
    }

    .notification-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .notification-header-card {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        flex-shrink: 0;
    }

    .notification-icon.issue {
        background: #e3f2fd;
        color: #1976d2;
    }

    .notification-icon.system {
        background: #fff3e0;
        color: #f57c00;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: 600;
        color: #333;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .notification-message {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 13px;
        color: #999;
    }

    .notification-time {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-action {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-view {
        background: #0C4079;
        color: white;
    }

    .btn-view:hover {
        background: #082c53;
    }

    .btn-mark-read {
        background: #f0f0f0;
        color: #666;
    }

    .btn-mark-read:hover {
        background: #e0e0e0;
    }

    .btn-delete {
        background: #fee;
        color: #dc3545;
    }

    .btn-delete:hover {
        background: #fcc;
    }

    .empty-notifications {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
    }

    .empty-notifications i {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-notifications h5 {
        color: #666;
        margin-bottom: 10px;
    }

    .empty-notifications p {
        color: #999;
        font-size: 14px;
    }

    .pagination {
        margin-top: 20px;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .notifications-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn-mark-all {
            width: 100%;
            justify-content: center;
        }

        .notification-card {
            padding: 15px;
        }

        .notification-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="notifications-container">
    <div class="notifications-header">
        <div style="display: flex; align-items: center;">
            <h4>الإشعارات</h4>
            @if($unreadCount > 0)
            <span class="unread-badge">{{ $unreadCount }} غير مقروء</span>
            @endif
        </div>
        <div class="header-actions">
            @if($unreadCount > 0)
            <button class="btn-mark-all" id="markAllReadBtn">
                <i class="fas fa-check-double"></i>
                <span>تحديد الكل كمقروء</span>
            </button>
            @endif
        </div>
    </div>

    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
        <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}" data-id="{{ $notification->id }}">
            <div style="display: flex;">
                <div class="notification-icon {{ $notification->type }}">
                    <i class="fas {{ $notification->type === 'issue' ? 'fa-tools' : 'fa-bell' }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ $notification->title }}</div>
                    <div class="notification-message">{{ $notification->message }}</div>
                    
                    <div class="notification-meta">
                        <div class="notification-time">
                            <i class="fas fa-clock"></i>
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if($notification->is_read)
                        <div style="color: #28a745;">
                            <i class="fas fa-check-circle"></i>
                            <span>مقروء</span>
                        </div>
                        @else
                        <div style="color: #0C4079; font-weight: 600;">
                            <i class="fas fa-circle" style="font-size: 8px;"></i>
                            <span>جديد</span>
                        </div>
                        @endif
                    </div>

                    <div class="notification-actions">
                        @if($notification->issue_id)
                        <a href="{{ route('issues.show', $notification->issue_id) }}" 
                           class="btn-action btn-view"
                           onclick="markAsReadAndRedirect(event, {{ $notification->id }}, '{{ route('issues.show', $notification->issue_id) }}')">
                            <i class="fas fa-eye"></i>
                            <span>عرض التذكرة</span>
                        </a>
                        @endif

                        @if(!$notification->is_read)
                        <button class="btn-action btn-mark-read" onclick="markAsRead({{ $notification->id }})">
                            <i class="fas fa-check"></i>
                            <span>تحديد كمقروء</span>
                        </button>
                        @endif

                        <button class="btn-action btn-delete" onclick="deleteNotification({{ $notification->id }})">
                            <i class="fas fa-trash"></i>
                            <span>حذف</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{ $notifications->links('vendor.pagination.bootstrap-custom') }}
        </div>
    @else
        <div class="empty-notifications">
            <i class="fas fa-bell-slash"></i>
            <h5>لا توجد إشعارات</h5>
            <p>سيتم عرض جميع الإشعارات هنا</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Mark all as read
document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم تحديد جميع الإشعارات كمقروءة',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، تحديد الكل',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#0C4079',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    Swal.fire({
                        toast: true,
                        position: 'bottom-start',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }
    });
});

// Mark single notification as read
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'bottom-start',
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.reload();
            });
        }
    });
}

// Mark as read and redirect
function markAsReadAndRedirect(event, notificationId, url) {
    event.preventDefault();
    
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(() => {
        window.location.href = url;
    });
}

// Delete notification
function deleteNotification(notificationId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف هذا الإشعار نهائياً',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'bottom-start',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }
    });
}
</script>
@endpush