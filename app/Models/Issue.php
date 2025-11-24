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

    /**
     * العلاقة مع المهندس
     */
    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * العلاقة مع نوع المشكلة
     */
    public function problem()
    {
        return $this->belongsTo(Constant::class, 'problem_type_id');
    }

    /**
     * الحصول على اسم مقدم التذكرة
     */
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

    /**
     * الحصول على نوع مقدم التذكرة
     */
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

    /**
     * التحقق من صاحب التذكرة
     */
    public function isOwnedBy($user)
    {
        return $this->user_id === $user->id;
    }

    /**
     * Scope للتذاكر المفتوحة
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope للتذاكر قيد المعالجة
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope للتذاكر المغلقة
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
    public function attachments()
{
    return $this->hasMany(IssueAttachment::class);
}

}
