<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'governorate_id',
        'engineer_ids',
        'is_active',
    ];

    protected $casts = [
        'engineer_ids' => 'array',  // هذا مهم جداً
        'is_active' => 'boolean',
    ];


    /**
     * العلاقة مع المحافظة (من جدول الثوابت)
     */
    public function governorate()
    {
        return $this->belongsTo(Constant::class, 'governorate_id');
    }

    /**
     * الحصول على المهندسين المرتبطين بالفريق
     */
    public function engineers()
    {
        if (empty($this->engineer_ids)) {
            return collect([]);
        }
        
        return Engineer::whereIn('id', $this->engineer_ids)->get();
    }

    /**
     * إضافة مهندس للفريق
     */
    public function addEngineer($engineerId)
    {
        $engineers = $this->engineer_ids ?? [];
        
        if (!in_array($engineerId, $engineers)) {
            $engineers[] = $engineerId;
            $this->engineer_ids = $engineers;
            $this->save();
        }
    }

    /**
     * إزالة مهندس من الفريق
     */
    public function removeEngineer($engineerId)
    {
        $engineers = $this->engineer_ids ?? [];
        
        $engineers = array_diff($engineers, [$engineerId]);
        $this->engineer_ids = array_values($engineers);
        $this->save();
    }

    /**
     * التحقق من وجود مهندس في الفريق
     */
    public function hasEngineer($engineerId)
    {
        return in_array($engineerId, $this->engineer_ids ?? []);
    }

    /**
     * الحصول على عدد المهندسين
     */
    public function getEngineersCountAttribute()
    {
        return count($this->engineer_ids);
    }

    /**
     * التحقق من أن المهندس متاح (ليس في فريق آخر)
     */
    public static function isEngineerAvailable($engineerId, $governorateId, $excludeTeamId = null)
    {
        return !self::where('governorate_id', $governorateId)
            ->when($excludeTeamId, function($query) use ($excludeTeamId) {
                $query->where('id', '!=', $excludeTeamId);
            })
            ->get()
            ->pluck('engineer_ids')
            ->flatten()
            ->contains($engineerId);
    }

}