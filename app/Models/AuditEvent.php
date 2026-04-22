<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditEvent extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_type', 'subject_type', 'subject_id', 'ip_address', 'user_agent', 'context'];

    protected function casts(): array
    {
        return ['context' => 'array'];
    }
}
