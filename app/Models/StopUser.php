<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StopUser extends Model {

    protected $table = 'stopUser';
    protected $fillable = ['chat_id', 'user_name', 'invited_count', 'message_sended'];
}