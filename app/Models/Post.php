<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'enable_to_send' => 'boolean',
        'frequency' => 'string' //FrequencyEnum::class
    ];

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'post_chats');
    }
}
