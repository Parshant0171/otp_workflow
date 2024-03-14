<?php

namespace Jgu\Wfotp\Traits;

use Carbon\Carbon;
use App\Models\User;
use Jgu\Wfotp\Models\WfoOtp;
use App\Models\GatePassRequest;
use Jgu\Wfotp\Models\WfoService;
use Illuminate\Support\Facades\Log;
use Jgu\Wfa\Models\WfaMasterEvent;

trait Verification
{

    // Verify OTP for specific model
    public function verifyOTP($modelId, $otp)
    {
        $otp = WfoOtp::where('model', $this->getOtpClassName())
            ->where('model_id', $modelId)
            ->where('otp', $otp)
            ->where('is_verified', 0)
            ->latest('id')->first();
        
        if ($otp) {
            if(!$this->isExpired($otp->expires_at)) {
                $otp->is_verified = 1;
                $otp->verification_date_time =  Carbon::now()->toDateTimeString();
                $otp->save();

                return true;
            } else {
                return 'expired';
            }           
        } else {
            return false;
        }
    }

    public function verifyLink($link) {
        $tokenArr = explode("&", $link);
        
        $modelId = explode("=", $tokenArr[0])[1];
        $model =  explode("=", $tokenArr[1])[1];

        $otp = WfoOtp::where('model_id', $modelId)
            ->where('model', $model)
            ->where('is_verified',-2)
            ->latest('id')->first();

        if(isset($otp) && $otp->is_verified == -2) {
            return view('Wfotp::verify', ["status" => "Cancelled"]);
        }
        
        $model =  explode("=", $tokenArr[1])[1];
        $otp = explode("=", $tokenArr[2])[1];
        $redirectTo = explode("=", $tokenArr[3])[1];

        $otp = WfoOtp::where('model', $model)
        ->where('model_id', $modelId)
        ->where('public_link', $otp)
        ->where('is_verified', 0)
        ->latest('id')->first();
        
        if(isset($otp) && $this->isExpired($otp->expires_at)){
            return view('Wfotp::verify', ["status" => "expired"]);
        }else{

            $gatePassRequest = GatePassRequest::where('id',$modelId)->first();
            $user = User::where('id',$gatePassRequest->user_id)->first();

            return view('Wfotp::approval',compact(
                'gatePassRequest',
                'user',
                'link'
            ));
        }
    }

    // Verify Request Approve or Reject
    public function verifyRequest($link,$response) {
        $tokenArr = explode("&", $link);
        
        $modelId = explode("=", $tokenArr[0])[1];
        $model =  explode("=", $tokenArr[1])[1];
        $otp = explode("=", $tokenArr[2])[1];
        $redirectTo = explode("=", $tokenArr[3])[1];

        $otp = WfoOtp::where('model', $model)
        ->where('model_id', $modelId)
        ->where('public_link', $otp)
        ->where('is_verified', 0)
        ->latest('id')->first();
    
        if($model == 'App\Models\GatePassRequest'){
            $approvedEventByParent = WfaMasterEvent::whereHas('master', function($q) use($model){
                $q->where(['model_path' => $model,'is_active' => 1]);
            })->where('unique_event_code','ABPA')->first();
    
            $rejectEventByParent = WfaMasterEvent::whereHas('master', function($q) use($model){
                $q->where(['model_path' => $model,'is_active' => 1]);
            })->where('unique_event_code','ABPR')->first();
    
            $gatePassRequest = GatePassRequest::where('id', $modelId)->latest()->first();
        }
        
        if ($otp && $model == 'App\Models\GatePassRequest') {
            if($response == 1) {
                $otp->is_verified = 1;
                $otp->verification_date_time =  Carbon::now()->toDateTimeString();
                $otp->save();
                $gatePassRequest->saveAction($approvedEventByParent->id,$gatePassRequest->parent_id);
                return view('Wfotp::verify', ["status" => "verified"]);
            }else{
                $otp->is_verified = -1;
                $otp->verification_date_time =  Carbon::now()->toDateTimeString();
                $otp->save();
                $gatePassRequest->saveAction($rejectEventByParent->id,$gatePassRequest->parent_id);
                return view('Wfotp::verify', ["status" => "Rejected"]);
            }        
        }
    }

    //current Attempt
    public function isAttemptAvailable($model_id,$model_name) {
        
        //Grab all the GatePassRequest Belong to a Particluar $model_id
        $totalOtpSended = WfoOtp::where('model_id', $model_id)
            ->where('is_verified', 0)
            ->where('model',$model_name)
            ->get()->count();
        
    
        $attempAllowed = WfoService::where('model',$model_name)
                        ->first();
        log::info("totalOtpSended = $totalOtpSended");
        log::info("attempAllowed = $attempAllowed->no_of_resend_available");
        if(($attempAllowed->no_of_resend_available)>$totalOtpSended){
            return true;
        }else{
            return false;
        }
    }


    //Is the Link active or not
    public function isLinkActive($model_id,$model_name) {

        $otp = WfoOtp::where('model_id', $model_id)
            ->where('is_verified', 0)
            ->where('model',$model_name)
            ->latest('id')->first();

        $currentTime = strtotime(Carbon::now()->toDateTimeString());
        $otpExpireTime = strtotime($otp->expires_at);

        log::info("currentTime = $currentTime");
        log::info("otpExpireTime = $otp->expires_at");

        if(($currentTime>$otpExpireTime) && ($otp->is_verified == 0)){
            return false;
        }else{
            return true;
        }
    }


}
