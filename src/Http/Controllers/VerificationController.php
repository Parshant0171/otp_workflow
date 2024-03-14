<?php
namespace Jgu\Wfotp\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\GatePassLog;
use Illuminate\Http\Request;
use Jgu\Wfotp\Models\WfoOtp;
use Jgu\Wfotp\Traits\SendOTP;
use App\Models\GatePassRequest;
use Jgu\Wfotp\Traits\Verification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Utils\API\Helpers;
use Jgu\Wfotp\Models\WfoShortenUrl;

class VerificationController extends Controller
{
    use SendOTP,Verification;

    public function index($token)
    {
        if($this->isUsingTinyUrlService() == 0){
            $token = $this->getOriginalEncryptedToken($token);
        }

        $token = $this->secret($token, 'decrypt');
        return $this->verifyLink($token);

    }

    public function gprApproveReject($token,$response) {
        $token = $this->secret($token, 'decrypt');
        
        return $this->verifyRequest($token,$response);
    }

    public function adminApprove(Request $request,$model_id,$model_name)
    {
        $otp = WfoOtp::where('model_id', $model_id)
            ->where('model',$model_name)
            ->latest('id')->first();

        if(!isset($otp)){
            return redirect()
                ->back()
                ->with([
                        'message'    => "OTP is not Generated for the Request, Kindly delete the current request and Regenerate it.",
                        'alert-type' => 'error',
                    ]);
        }
        
        // Check into GatePasslog is the Student is Currently Out or not, if out then return false
        $isLogged = GatePassLog::where('gate_pass_request_id',$model_id)
                    ->where('status_id','0')->exists();

        if(isset($otp)) {
            if($otp->is_verified == -2) { 
                return redirect()
                ->back()
                ->with([
                        'message'    => "The Request is already Cancelled",
                        'alert-type' => 'error',
                    ]);
            }
            if($otp->is_verified == -1) {
                return redirect()
                ->back()
                ->with([
                        'message'    => "The Request is already Rejected",
                        'alert-type' => 'error',
                    ]);
            }

            if($isLogged){
                return redirect()
                ->back()
                ->with([
                        'message'    => "Can't Approve or Reject because the Student is Logged now.",
                        'alert-type' => 'error',
                    ]);
            }
            
        }
        
        $gatePassLog = new GatePassLog();
        $gatePassLog->gate_pass_request_id = $model_id;
        $gatePassLog->entry_exit_time = Carbon::now()->toDateTimeString();
        $message = '';
        if($request->result == 1) {
            $otp->is_verified = 1;
            $gatePassLog->status_id = 'Approved';
            $message = "Request is Succesfully Approved";
        } elseif($request->result == -1) {
            $otp->is_verified = -1;
            $gatePassLog->status_id = 'Rejected';
            $message = "Request is Succesfully Rejected";
        }
        $gatePassLog->tenant_id = Auth::user()->tenant_id;
        $gatePassLog->remarks = $request->remarks;
        $gatePassLog->save();
        
        $otp->verification_date_time =  Carbon::now()->toDateTimeString();
        $otp->save();

        return redirect()
        ->back()
        ->with([
                'message'    => $message,
                'alert-type' => 'success',
            ]);
    }

    public function otpResend(Request $request,$model_id,$model_name){
        $isAjax = $request->expectsJson();
        $gatePassRequest = new GatePassRequest;

        $lastRequest = GatePassRequest::where('id',$model_id)->first();

        $otp = WfoOtp::where('model_id', $model_id)
            ->where('model',$model_name)
            ->latest('id')->first();

        if(isset($lastRequest)){
            $inDateTime = strtotime($lastRequest->in_date_time);
            $currentTime = strtotime(Carbon::now()->toDateTimeString());
        }

        if(isset($otp)) {
            if($otp->is_verified == 1) {
                if($isAjax) {
                    return response()->json(["error" => "The Request is already Approved", "success" => false] , Helpers::$codes['success']); 
                }else{
                    return redirect()
                    ->back()
                    ->with([
                            'message'    => "The Request is already Approved",
                            'alert-type' => 'error',
                        ]);
                }
            }elseif($otp->is_verified == -1){
                if($isAjax) {
                    return response()->json(["error" => "The Request is already Rejected", "success" => false] , Helpers::$codes['success']);  
                }else{
                    return redirect()
                    ->back()
                    ->with([
                            'message'    => "The Request is already Rejected",
                            'alert-type' => 'error',
                        ]);
                }
                
            }elseif($currentTime>$inDateTime){
                if($isAjax) {
                    return response()->json(["error" => "Your Request is Expired", "success" => false] , Helpers::$codes['success']); 
                }else{
                    return redirect()
                    ->back()
                    ->with([
                            'message'    => "Your Request is Expired",
                            'alert-type' => 'error',
                        ]);
                }
                
            }
        }


        $parent = User::where('id',$lastRequest->parent_id)->first();
        
        $mobileNumber = $parent->mobile_no;
        $response = $gatePassRequest->resendOTP($model_id,$mobileNumber,$model_name);

        if($response == 'OTP Sended Sussecfully'){
            $message = 'Approving link sent successfully.';
            $type = 'success';
        }else{
            $message = $response;
            $type = 'error';
        }

        if($isAjax){
            return response()->json(['message'    => $message,"success" => true] , Helpers::$codes['success']); 
        }else{
            return redirect()
            ->back()
            ->with([
                    'message'    => $message,
                    'alert-type' => $type,
                ]);
        }
        
    }

    public function getOriginalEncryptedToken($token)
    {
        $wfoShortenUrl = WfoShortenUrl::where('shorten_url',$token)->first();
                        
        if(isset($wfoShortenUrl)){
            return $wfoShortenUrl->encrypt_token;
        }

        return null;
        
    }
}
