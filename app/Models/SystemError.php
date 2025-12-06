<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemError extends Model
{
    protected $fillable = [
        'message','file','line','url','user_id','ip','user_agent','trace','type'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
