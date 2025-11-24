<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    protected $fillable = [
        'phone', 'message', 'status', 'api_response',
        'engineer_id', 'user_id'
    ];

    public function engineer()
    {
        return $this->belongsTo(Engineer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
