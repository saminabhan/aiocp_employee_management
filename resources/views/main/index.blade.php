@extends('layouts.app')

@section('content')

    <div class="stats-grid">

        @if(user_can('engineers.view'))
        <div class="stat-card">
            <div class="stat-info">
                <h3>إجمالي المهندسين</h3>
                <p>{{ $engineer_count }} مهندس/ة</p>
            </div>
            <div class="stat-icon bg-blue">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
        @endif

        @if(user_can('teams.view'))
         <div class="stat-card">
            <div class="stat-info">
                <h3>عدد الفرق</h3>
                <p>{{ $team_count }} فرق</p>
            </div>
            <div class="stat-icon bg-black">
                <i class="fas fa-users-cog"></i>
            </div>
        </div>
        @endif

        <div class="stat-card">
            <div class="stat-info">
                <h3>سجلات الدوام اليومي</h3>
                <p>37 مهندس/ة</p>
            </div>
            <div class="stat-icon bg-green">
                <i class="fas fa-business-time"></i>
            </div>
        </div>

    </div>
@endsection