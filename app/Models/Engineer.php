<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_image',
        'national_id',
        'first_name',
        'second_name',
        'third_name',
        'last_name',
        'mobile_1',
        'mobile_2',
        'gender_id',
        'marital_status_id',
        'birth_date',
        'home_governorate_id',
        'home_city_id',
        'home_address_details',
        'work_governorate_id',
        'work_city_id',
        'work_address_details',
        'experience_years',
        'specialization',
        'specialization_id',
        'salary_amount',
        'salary_currency_id',
        'work_start_date',
        'work_end_date',
        'app_username',
        'app_password',
        'phone_type',
        'phone_name',
        'os_version',
        'bank_name',
        'bank_account_number',
        'iban_number',
        'account_owner_first',
        'account_owner_second',
        'account_owner_third',
        'account_owner_last',
        'account_owner_national_id',
        'account_owner_mobile',
        'main_work_area_code',
        'sub_work_area_code',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'work_start_date' => 'date',
        'work_end_date' => 'date',
        'salary_amount' => 'decimal:2',
    ];

    protected $appends = ['full_name'];

    /**
     * Get all attachments for the engineer
     */
    public function attachments()
    {
        return $this->hasMany(EngineerAttachment::class);
    }

    /**
     * Get gender constant
     */
    public function gender()
    {
        return $this->belongsTo(Constant::class, 'gender_id');
    }

    /**
     * Get marital status constant
     */
    public function maritalStatus()
    {
        return $this->belongsTo(Constant::class, 'marital_status_id');
    }

    /**
     * Get home governorate
     */
    public function homeGovernorate()
    {
        return $this->belongsTo(Constant::class, 'home_governorate_id');
    }

    /**
     * Get home city
     */
    public function homeCity()
    {
        return $this->belongsTo(Constant::class, 'home_city_id');
    }

    /**
     * Get work governorate
     */
    public function workGovernorate()
    {
        return $this->belongsTo(Constant::class, 'work_governorate_id');
    }

    /**
     * Get work city
     */
    public function workCity()
    {
        return $this->belongsTo(Constant::class, 'work_city_id');
    }

    /**
     * Get salary currency
     */
    public function salaryCurrency()
    {
        return $this->belongsTo(Constant::class, 'salary_currency_id');
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->second_name} {$this->third_name} {$this->last_name}";
    }

    /**
     * Get personal image URL
     */
    public function getPersonalImageUrlAttribute()
    {
        return $this->personal_image ? asset('storage/' . $this->personal_image) : asset('images/default-avatar.png');
    }
    /**
     * Get age attribute
     */
    public function getAgeAttribute()
    {
    if (!$this->birth_date) {
        return null;
    }

    return \Carbon\Carbon::parse($this->birth_date)->age;
    }

    public function issues()
{
    return $this->hasMany(AppIssue::class, 'engineer_id');
}

public function engineer_specialization()
{
    return $this->belongsTo(Constant::class, 'specialization_id');
}

public function teams()
{
    return $this->belongsToMany(Team::class, 'team_engineer');
}

public function user()
{
    return $this->hasOne(User::class, 'engineer_id');
}

    public function mainWorkAreaCode()
    {
        return $this->belongsTo(Constant::class, 'main_work_area_code');
    }

public function specialization()
{
    return $this->belongsTo(Constant::class, 'specialization_id');
}

}