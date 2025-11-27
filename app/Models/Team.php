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
        'supervisor_id',
        'main_work_area_code',
        'sub_work_area_code',
    ];

    protected $casts = [
        'engineer_ids' => 'array',
        'is_active' => 'boolean',
    ];


    public function getEngineerIdsAttribute($value)
    {
        if (empty($value)) return [];

        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return is_array($decoded) ? $decoded : [];
    }

    public function engineers()
    {
        return Engineer::whereIn('id', $this->engineer_ids)->get();
    }

    public function getEngineersCountAttribute()
    {
        return count($this->engineer_ids ?? []);
    }

    public function addEngineer($engineerId)
    {
        $ids = $this->engineer_ids ?? [];
        if (!in_array($engineerId, $ids)) {
            $ids[] = $engineerId;
            $this->update(['engineer_ids' => $ids]);
        }
    }

    public function removeEngineer($engineerId)
    {
        $ids = array_values(array_diff($this->engineer_ids ?? [], [$engineerId]));
        $this->update(['engineer_ids' => $ids]);
    }

    public function hasEngineer($engineerId)
    {
        return in_array($engineerId, $this->engineer_ids ?? []);
    }

    public function governorate()
    {
        return $this->belongsTo(Constant::class, 'governorate_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function members()
    {
        return $this->hasMany(User::class, 'team_id');
    }

    public static function isEngineerAvailable($engineerId, $governorateId, $excludeTeamId = null)
    {
        return !self::where('governorate_id', $governorateId)
            ->when($excludeTeamId, fn ($q) => $q->where('id', '!=', $excludeTeamId))
            ->get()
            ->pluck('engineer_ids')
            ->flatten()
            ->contains($engineerId);
    }

    public function mainWorkArea()
    {
        return $this->belongsTo(Constant::class, 'main_work_area_code');
    }

    public function subWorkArea()
    {
        return $this->belongsTo(Constant::class, 'sub_work_area_code');
    }

}
