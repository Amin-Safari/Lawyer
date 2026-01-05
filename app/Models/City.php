<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['province_id', 'name'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
