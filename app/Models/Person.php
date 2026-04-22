<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'tree_id','first_name','last_name','middle_name','birth_last_name','gender','life_status',
        'birth_date_precision','birth_date','birth_year','birth_month','birth_place',
        'death_date_precision','death_date','death_year','death_month','death_place',
        'summary_note','full_note','photo_path','meta',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'death_date' => 'date', 'meta' => 'array'];
    }

    public function tree(): BelongsTo { return $this->belongsTo(Tree::class); }

    public function displayName(): string
    {
        return trim($this->last_name.' '.$this->first_name.' '.$this->middle_name);
    }
}
