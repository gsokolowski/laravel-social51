<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';

    protected $fillable = [
        'body'
    ];

    public function user() {

        // status belongs to user
        return $this->belongsTo('Social\Models\User', 'user_id' );
    }

    // gives statuses but not that ones where you replied to - only our own statuses
    public function scopeNotReply($query) {

        return $query->whereNull('parent_id');
    }

    public function replies() {
        return $this->hasMany('Social\Models\Status', 'parent_id');
    }
}

