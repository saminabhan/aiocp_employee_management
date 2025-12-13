<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyEngineerSync extends Model
{
    use HasFactory;

    protected $table = 'daily_engineer_syncs';

    protected $fillable = [
        'engineer_id',
        'sync_date',
        'is_synced',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'sync_date' => 'date',
        'is_synced' => 'boolean',
    ];


    public function engineer()
    {
        return $this->belongsTo(Engineer::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }


    public function scopeForDate($query, $date)
    {
        return $query->whereDate('sync_date', $date);
    }

    public function scopeSynced($query)
    {
        return $query->where('is_synced', true);
    }

    public function scopeNotSynced($query)
    {
        return $query->where('is_synced', false);
    }


    public function getStatusTextAttribute()
    {
        return $this->is_synced ? 'زامن' : 'لم يزامن';
    }
}
