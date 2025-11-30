<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyAttendance extends Model
{
    protected $fillable = [
        'user_type',
        'engineer_id',
        'user_id',
        'attendance_date',
        'day_type',
        'status',
        'notes',
        'recorded_by'
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function engineer()
    {
        return $this->belongsTo(Engineer::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeEngineers($query)
    {
        return $query->where('user_type', 'engineer');
    }

    public function scopeSupervisors($query)
    {
        return $query->where('user_type', 'supervisor');
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('attendance_date', now()->year)
                     ->whereMonth('attendance_date', now()->month);
    }

    public static function checkIfFriday($date)
    {
        return Carbon::parse($date)->isFriday();
    }

    public static function getDayType($date)
    {
        $carbon = Carbon::parse($date);
        return $carbon->isFriday() ? 'friday' : 'weekday';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'present' => '<span class="badge-custom badge-success">حاضر</span>',
            'absent' => '<span class="badge-custom badge-danger">غائب</span>',
            'leave' => '<span class="badge-custom badge-warning">إجازة</span>',
            'weekend' => '<span class="badge-custom badge-secondary">عطلة</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-secondary">غير محدد</span>';
    }

    public function getUserNameAttribute()
    {
        if ($this->user_type === 'engineer' && $this->engineer) {
            return $this->engineer->name;
        } elseif ($this->user_type === 'supervisor' && $this->supervisor) {
            return $this->supervisor->name;
        }
        return 'غير معروف';
    }

    public static function canRecordForDate($date)
    {
        $targetDate = Carbon::parse($date);
        $today = Carbon::today();
        
        return $targetDate->lte($today) && $targetDate->gte($today->copy()->subDay());
    }

    public static function getMonthlyStats($userId, $userType)
    {
        $query = self::thisMonth()
                     ->where('user_type', $userType);
        
        if ($userType === 'engineer') {
            $query->where('engineer_id', $userId);
        } else {
            $query->where('user_id', $userId);
        }
        
        $total = $query->count();
        $present = $query->clone()->where('status', 'present')->count();
        $absent = $query->clone()->where('status', 'absent')->count();
        $leave = $query->clone()->where('status', 'leave')->count();
        
        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'leave' => $leave,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0
        ];
    }
}