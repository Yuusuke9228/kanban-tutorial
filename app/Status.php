<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // white list
    protected $fillable = ['title', 'slug', 'order'];

    // タイムスタンプを削除した場合
    //public $timestamps = false;

    public function tasks ()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 親を消した際に、関連タスクを削除するため
     */
    public static function boot ()
    {
        parent::boot ();

        static::deleting(function ($status) {
            $status->tasks()->delete();
        });
    }
}
