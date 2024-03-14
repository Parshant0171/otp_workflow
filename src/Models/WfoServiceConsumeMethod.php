<?php
namespace Jgu\Wfotp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WfoServiceConsumeMethod extends WfoBaseModel
{
    use HasFactory;

    protected $table = 'wfo_services_consume_methods';

    public function method(){
        return $this->belongsTo(WfoMasterMethod::class, 'wfo_master_method_id');
    }

    public function service(){
        return $this->belongsTo(WfoService::class, 'wfo_service_id');
    }

}
