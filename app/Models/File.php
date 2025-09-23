<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'node_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    // Relasi ke node
    public function node()
    {
        return $this->belongsTo(Node::class);
    }
}
