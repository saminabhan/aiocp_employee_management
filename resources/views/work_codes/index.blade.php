@extends('layouts.app')

@section('title', 'شجرة أكواد مناطق العمل')

@push('styles')
<style>
body {
    background: #f4f6fa;
    font-family: 'Cairo', sans-serif;
}

.page-header {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #0C4079;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.main-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 20px;
    align-items: start;
}

.index-sidebar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
}

.index-title {
    font-size: 16px;
    font-weight: 700;
    color: #0C4079;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 8px;
}

.index-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.index-item {
    margin-bottom: 8px;
}

.index-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    color: #495057;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s;
    border: 1px solid transparent;
}

.index-link:hover {
    background: #f8f9fa;
    color: #0C4079;
    border-color: #dee2e6;
}

.index-link.active {
    background: #e3f2fd;
    color: #0C4079;
    border-color: #0C4079;
}

.index-icon {
    width: 30px;
    height: 30px;
    background: #f8f9fa;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #0C4079;
}

.index-count {
    margin-right: auto;
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    color: #6c757d;
}

.content-area {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.gov-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    scroll-margin-top: 20px;
    display: none;
}

.gov-section.active {
    display: block;
    animation: fadeIn 0.4s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.gov-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.gov-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #0C4079;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.gov-info {
    flex: 1;
}

.gov-name {
    font-size: 18px;
    font-weight: 700;
    color: #0C4079;
    margin-bottom: 3px;
}

.gov-meta {
    font-size: 13px;
    color: #6c757d;
}

.codes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 15px;
}

.code-card {
    background: #fafbfc;
    padding: 18px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s;
}

.code-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #0C4079;
    background: white;
}

.code-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e9ecef;
}

.code-number {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #0C4079;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
}

.code-title {
    font-size: 15px;
    font-weight: 700;
    color: #212529;
    flex: 1;
}

.code-desc {
    font-size: 13px;
    color: #6c757d;
    margin-bottom: 12px;
    line-height: 1.5;
}

.children-section {
    background: white;
    border-radius: 8px;
    padding: 12px;
    border: 1px solid #e9ecef;
}

.children-header {
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.children-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.child-badge {
    background: #f8f9fa;
    color: #495057;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #dee2e6;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.child-badge:hover {
    background: #e3f2fd;
    color: #0C4079;
    border-color: #0C4079;
}

.child-badge i {
    font-size: 10px;
}

.empty-children {
    text-align: center;
    padding: 15px;
    color: #adb5bd;
    font-size: 12px;
}

.mobile-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.mobile-overlay.show {
    opacity: 1;
}

.mobile-index-toggle {
    display: none;
    position: fixed;
    bottom: 20px;
    left: 20px;
    width: 50px;
    height: 50px;
    background: #0C4079;
    color: white;
    border-radius: 50%;
    border: none;
    box-shadow: 0 4px 12px rgba(12,64,121,0.3);
    z-index: 1000;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

.mobile-index-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(12,64,121,0.4);
}

.mobile-index-toggle:active {
    transform: scale(0.95);
}

@media (max-width: 992px) {
    .main-layout {
        grid-template-columns: 1fr;
    }
    
    .index-sidebar {
        position: fixed;
        top: 0;
        right: -300px;
        bottom: 0;
        width: 280px;
        max-height: 100vh;
        z-index: 1001;
        border-radius: 0;
        transform: translateX(0);
        opacity: 1;
    }
    
    .index-sidebar.show {
        right: 0;
        box-shadow: -4px 0 12px rgba(0,0,0,0.15);
    }
    
    .mobile-index-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .mobile-overlay {
        display: block;
    }
    
    .codes-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 20px 15px;
    }
    
    .page-title {
        font-size: 20px;
    }
    
    .gov-section {
        padding: 20px 15px;
    }
    
    .code-card {
        padding: 15px;
    }
    
    .gov-name {
        font-size: 16px;
    }
}

.index-sidebar::-webkit-scrollbar {
    width: 6px;
}

.index-sidebar::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 10px;
}

.index-sidebar::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 10px;
}

.index-sidebar::-webkit-scrollbar-thumb:hover {
    background: #0C4079;
}
</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-sitemap"></i>
            شجرة أكواد مناطق العمل
        </h1>
    </div>

    <div class="main-layout">
        
        <aside class="index-sidebar" id="indexSidebar">
            <div class="index-title">
                <i class="fas fa-list"></i>
                الفهرس
            </div>
            <ul class="index-list">
                @foreach($governorates as $index => $gov)
                    <li class="index-item">
                        <a href="#gov-{{ $index }}" class="index-link" data-gov="{{ $index }}">
                            <span class="index-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <span>{{ $gov['name'] }}</span>
                            <span class="index-count">{{ count($gov['codes']) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="content-area">

            @foreach($governorates as $index => $gov)
                <div class="gov-section" id="gov-{{ $index }}">
                    
                    <div class="gov-header">
                        <div class="gov-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div class="gov-info">
                            <div class="gov-name">{{ $gov['name'] }}</div>
                            <div class="gov-meta">
                                <i class="fas fa-folder me-1"></i>
                                {{ count($gov['codes']) }} منطقة رئيسية
                            </div>
                        </div>
                    </div>

                    @if(count($gov['codes']) > 0)
                        <div class="codes-grid">
                            @foreach($gov['codes'] as $codeIndex => $code)
                                <div class="code-card">
                                    
                                    <div class="code-header">
                                        <div class="code-number">{{ $codeIndex + 1 }}</div>
                                        <div class="code-title">{{ $code->name }}</div>
                                    </div>

                                    @if($code->description)
                                        <div class="code-desc">
                                           عنوان منطقة العمل: {{ $code->description }}
                                        </div>
                                    @endif

                                    @if($code->children && $code->children->count() > 0)
                                        <div class="children-section">
                                            <div class="children-header">
                                                <i class="fas fa-layer-group"></i>
                                                المناطق الفرعية ({{ $code->children->count() }})
                                            </div>
                                            <div class="children-list">
                                                @foreach($code->children as $child)
                                                    <span class="child-badge" title="{{ $child->description ?? '' }}">
                                                        <i class="fas fa-circle" style="font-size: 6px;"></i>
                                                        {{ $child->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="empty-children">
                                            <i class="fas fa-minus-circle"></i>
                                            لا توجد مناطق فرعية
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            @endforeach

        </div>

    </div>

    <button class="mobile-index-toggle" onclick="toggleIndex()">
        <i class="fas fa-list"></i>
    </button>

    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleIndex()"></div>

</div>

<script>
document.querySelectorAll('.index-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            document.querySelectorAll('.gov-section').forEach(section => {
                section.classList.remove('active');
            });
            
            targetElement.classList.add('active');
            
            document.querySelector('.content-area').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
            
            document.querySelectorAll('.index-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            if (window.innerWidth <= 992) {
                toggleIndex();
            }
        }
    });
});

function toggleIndex() {
    const sidebar = document.getElementById('indexSidebar');
    const overlay = document.getElementById('mobileOverlay');
    
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
    
    if (sidebar.classList.contains('show')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const firstSection = document.querySelector('.gov-section');
    const firstLink = document.querySelector('.index-link');
    
    if (firstSection) {
        firstSection.classList.add('active');
    }
    if (firstLink) {
        firstLink.classList.add('active');
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('indexSidebar');
        const overlay = document.getElementById('mobileOverlay');
        
        if (sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
});
</script>

@endsection