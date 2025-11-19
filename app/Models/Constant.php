<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
    protected $fillable = ['name', 'parent'];

    public function parentConst()
    {
        return $this->belongsTo(Constant::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(Constant::class, 'parent');
    }

    public function deleteWithChildren()
    {
        foreach ($this->children as $child) {
            $child->deleteWithChildren();
        }

        $this->delete();
    }

    public function scopeChildrenOfName($query, $name)
    {
        return $query->whereHas('parentConst', function($q) use ($name) {
            $q->where('name', $name);
        });
    }

    public function scopeChildrenOfSlug($query, $slug)
    {
        return $query->whereHas('parentConst', function($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    public function scopeChildrenOfId($query, $id)
    {
        return $query->where('parent', $id);
    }
}
