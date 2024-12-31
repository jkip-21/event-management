<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Atendee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    // One user can attend many events
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    // This user belongs/has attended to/a this event
    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
}
