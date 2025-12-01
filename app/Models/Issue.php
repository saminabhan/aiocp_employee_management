<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $table = 'app_issues';

    protected $fillable = [
        'engineer_id',
        'user_id',
        'problem_type_id',
        'description',
        'status',
        'priority',
        'solution',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function problem()
    {
        return $this->belongsTo(Constant::class, 'problem_type_id');
    }


    public function getSubmitterNameAttribute()
    {
        if ($this->engineer_id) {
            return $this->engineer->full_name ?? 'مهندس محذوف';
        }
        if ($this->user_id) {
            return $this->user->name ?? 'مستخدم محذوف';
        }
        return 'غير محدد';
    }

    public function getSubmitterProfileUrlAttribute()
    {
    if ($this->engineer_id) {
        return route('engineers.show', $this->engineer_id);
    }

    if ($this->user_id) {
        return route('profile.view', $this->user_id);
    }

    return null;
    }



    public function getSubmitterTypeAttribute()
    {
        if ($this->engineer_id) {
            return 'مهندس';
        }
       if ($this->user_id && $this->user && $this->user->role) {
            return $this->user->role->display_name ?? 'مستخدم';
        }
        return 'غير محدد';
    }

    public function isOwnedBy($user)
    {
        return $this->user_id === $user->id;
    }


    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }


    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }


    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
    public function attachments()
    {
        return $this->hasMany(IssueAttachment::class);
    }

}
