<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relationship extends Model
{
    use HasFactory;

    protected $fillable = ['tree_id', 'person_id', 'relative_id', 'type'];

    public function tree(): BelongsTo { return $this->belongsTo(Tree::class); }
    public function person(): BelongsTo { return $this->belongsTo(Person::class); }
    public function relative(): BelongsTo { return $this->belongsTo(Person::class, 'relative_id'); }
}
