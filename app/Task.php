<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // white list
    protected $fillable = ['title', 'description', 'order', 'status_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
