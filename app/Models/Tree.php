<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tree extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'is_archived', 'archived_at', 'viewport'];

    protected function casts(): array
    {
        return ['is_archived' => 'boolean', 'archived_at' => 'datetime', 'viewport' => 'array'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function people(): HasMany { return $this->hasMany(Person::class); }
    public function relationships(): HasMany { return $this->hasMany(Relationship::class); }
}
