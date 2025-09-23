<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ['title', 'parent_id', 'year_id'];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Node::class, 'parent_id')->orderBy('order', 'asc');
    }

    // Relasi ke file
    public function files()
    {
        return $this->hasMany(File::class, 'node_id');
    }

    public function getIsCompletedAllAttribute()
    {
        // Jika node ini tidak punya child, statusnya tergantung pada file yang diunggah
        if ($this->children->isEmpty()) {
            return $this->files->isNotEmpty();
        }

        // Jika punya child, periksa apakah semua child sudah selesai
        return $this->children->every(function ($child) {
            return $child->is_completed_all; // Rekursif ke child
        });
    }
}
