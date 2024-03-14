<?php
namespace Jgu\Wfotp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WfoService extends WfoBaseModel
{
    use HasFactory;

    public function methods() {
        return $this->HasMany(WfoServiceConsumeMethod::class, 'wfo_service_id');
    }

    public function smsServices(){
        return $this->HasMany(WfoServiceUseSmsService::class, 'wfo_service_id');
    }
}