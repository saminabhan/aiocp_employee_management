<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppIssue extends Model
{
      protected $table = 'app_issues';

    public function problem()
    {
        return $this->belongsTo(Constant::class, 'problem_type_id');
    }

    // علاقة المهندس
    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }
}
