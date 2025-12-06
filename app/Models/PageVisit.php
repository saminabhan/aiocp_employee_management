<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = ['user_id','url','count','last_visited_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
