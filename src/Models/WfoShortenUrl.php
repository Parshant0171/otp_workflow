<?php

namespace Jgu\Wfotp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WfoShortenUrl extends WfoBaseModel
{
    use HasFactory;

    protected $fillable = [
        'shorten_url',
        'encrypt_token',
        'expires_at',
        'is_used'
    ];

}