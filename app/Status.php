<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // white list
    protected $fillable = ['title', 'slug', 'order'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks ()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ステータス削除時に、関連タスクを削除する
     */
    public static function boot ()
    {
        parent::boot ();

        static::deleting(function ($status) {
            $status->tasks()->delete();
        });
    }
}
