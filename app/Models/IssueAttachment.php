<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueAttachment extends Model
{
    protected $fillable = [
        'issue_id',
        'attachment_type_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
    ];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function type()
    {
        return $this->belongsTo(Constant::class, 'attachment_type_id');
    }
}
