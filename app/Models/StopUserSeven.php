<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StopUserSeven extends Model {

    protected $table = 'stopUserSeven';
    protected $fillable = ['chat_id', 'user_name', 'invited_count', 'message_sended'];
}