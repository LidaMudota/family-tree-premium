<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportJob extends Model
{
    use HasFactory;

    protected $fillable = ['tree_id', 'user_id', 'format', 'file_path', 'status', 'meta'];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }
}
