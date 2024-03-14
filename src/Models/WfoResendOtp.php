<?php
namespace Jgu\Wfotp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WfoResendOtp extends WfoBaseModel
{
    use HasFactory;

    protected $table = "wfo_otp_resends";

    public function otp(){
        return $this->belongsTo(WfoOtp::class);
    }

}
