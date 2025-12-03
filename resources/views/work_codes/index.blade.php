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

/* Desktop Sidebar */
.index-sidebar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    position: sticky;
    top: 80px;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
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
    cursor: pointer;
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

.mobile-tabs-container {
    display: none;
    background: white;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.mobile-tabs-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.mobile-tabs-title {
    font-size: 16px;
    font-weight: 700;
    color: #0C4079;
    display: flex;
    align-items: center;
    gap: 8px;
}

.mobile-tabs-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.mobile-tabs-scroll::-webkit-scrollbar {
    display: none;
}

.mobile-tabs {
    display: flex;
    gap: 8px;
    padding-bottom: 5px;
    min-width: max-content;
}

.mobile-tab {
    padding: 10px 16px;
    border-radius: 8px;
    background: #f8f9fa;
    color: #495057;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #e9ecef;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s;
}

.mobile-tab:hover {
    background: #e9ecef;
}

.mobile-tab.active {
    background: #0C4079;
    color: white;
    border-color: #0C4079;
}

.mobile-tab .tab-count {
    display: inline-block;
    margin-right: 5px;
    padding: 2px 6px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    font-size: 11px;
}

.mobile-tab.active .tab-count {
    background: rgba(255,255,255,0.3);
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
    display: none;
}

.gov-section.active {
    display: block;
    animation: fadeIn 0.3s ease-in-out;
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

@media (max-width: 992px) {
    .main-layout {
        grid-template-columns: 1fr;
    }
    
    .index-sidebar {
        display: none;
    }
    
    .mobile-tabs-container {
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
    
    .mobile-tabs-container {
        padding: 12px;
    }
    
    .mobile-tab {
        font-size: 12px;
        padding: 8px 12px;
    }
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

    <div class="mobile-tabs-container">
        <div class="mobile-tabs-header">
            <div class="mobile-tabs-title">
                <i class="fas fa-map-marked-alt"></i>
                المحافظات
            </div>
        </div>
        <div class="mobile-tabs-scroll">
            <div class="mobile-tabs">
                @foreach($governorates as $index => $gov)
                    <div class="mobile-tab {{ $index === 0 ? 'active' : '' }}" data-target="gov-{{ $index }}">
                        <span class="tab-count">{{ count($gov['codes']) }}</span>
                        {{ $gov['name'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="main-layout">
        
        <aside class="index-sidebar">
            <div class="index-title">
                <i class="fas fa-list"></i>
                الفهرس
            </div>
            <ul class="index-list">
                @foreach($governorates as $index => $gov)
                    <li class="index-item">
                        <a class="index-link {{ $index === 0 ? 'active' : '' }}" data-target="gov-{{ $index }}">
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
                <div class="gov-section {{ $index === 0 ? 'active' : '' }}" id="gov-{{ $index }}">
                    
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

</div>

<script>
function switchSection(targetId) {
    document.querySelectorAll('.gov-section').forEach(section => {
        section.classList.remove('active');
    });
    
    const targetSection = document.getElementById(targetId);
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    document.querySelectorAll('.index-link, .mobile-tab').forEach(link => {
        link.classList.remove('active');
    });
    
    document.querySelectorAll(`[data-target="${targetId}"]`).forEach(link => {
        link.classList.add('active');
    });
    
    if (window.innerWidth <= 992) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

document.querySelectorAll('.index-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('data-target');
        switchSection(targetId);
    });
});

document.querySelectorAll('.mobile-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        switchSection(targetId);
        
        this.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    });
});
</script>

@endsection