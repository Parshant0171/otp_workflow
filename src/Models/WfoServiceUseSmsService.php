<?php
namespace Jgu\Wfotp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WfoServiceUseSmsService extends WfoBaseModel
{
    use HasFactory;

    protected $table = 'wfo_services_uses_sms_services';

    public function smsService(){
        return $this->belongsTo(WfoMasterSmsService::class, 'master_sms_service_id');
    }

    public function service(){
        return $this->belongsTo(WfoService::class);
    }

}
