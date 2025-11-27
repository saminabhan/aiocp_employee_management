<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerAttachment extends Model
{
    use HasFactory;

    protected $table = 'engineer_attachments';

    protected $fillable = [
        'engineer_id',
        'attachment_type_id',
        'file_path',
        'file_name',
        'details',
    ];

    public function engineer()
    {
        return $this->belongsTo(Engineer::class);
    }

    public function attachmentType()
    {
        return $this->belongsTo(Constant::class, 'attachment_type_id');
    }

    public function type()
    {
    return $this->belongsTo(Constant::class, 'attachment_type_id');
    }

}
