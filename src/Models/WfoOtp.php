<?php

namespace Jgu\Wfotp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WfoOtp extends WfoBaseModel
{
    use HasFactory;

    protected $fillable = [
        'model',
        'model_id',
        'expires_at',
        'is_verified',
        'public_link',
        'otp'
    ];
}
