<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id','url','method','ip','user_agent','action_type','additional_data'
    ];

    protected $casts = [
        'additional_data' => 'json',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
