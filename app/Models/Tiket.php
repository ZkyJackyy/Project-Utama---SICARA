<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tickets';
    protected $fillable = ['user_id', 'subject', 'category', 'status'];

    public function messages()
    {
        return $this->hasMany(Message::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
