# Installation

Add the following code in your composer.json

```
"repositories": [
    ...
    { "type": "vcs", "url": "https://github.com/jgu-it/otp_workflow.git" },
    ...
  ]
```
Run `composer install`
Run `php artisan vendor:publish` and select wfo from the list

Update the `yourapp/config/wfo.php` set following configuration:

```
'useTenants' => false, // true if required
'secret_key' => "your-secret-key", // secret key is openssl_encrypt encryt/decrypt secret key
'secret_iv' => 'JGU-2020-IT-TO',  // secret iv is openssl_encrypt encryt/decrypt secret iv   
'baseUrl' => 'https://zero.jgu.edu.in', // base url of your app
'sns_key' => env('AWS_ACCESS_KEY_ID', '123'), // aws sns access key
'sns_secret' => env('AWS_SECRET_ACCESS_KEY', 'xyz'), // aws sns secret key
'sns_region' => env('AWS_DEFAULT_REGION', 'us-east-1'), // aws region
```

Run `php artisan config:cache`

# Configuration

## WFA Tables:
1. `wfo_otps`: Table that stores the OTP and their verification information.
2. `wfo_otp_resends`: Table that stores the re-send OTP detail.
3. `wfo_services`: Table to store model configuration.
4. `wfo_master_methods`: Table to store Different methods of verification like OTP, Public URL.
5. `wfo_services_consume_methods`: Table that contains the mapping of service and method,
6. `wfo_master_sms_services`: Master Table that stores the sms services. This package supports aws sns service. We will extent this in future.
7. `wfo_services_uses_sms_services`: Table that stores the mapping of sms service and service.

### WFO OTP
`Jgu\Wfotp\Models\WfoOtp`

Fields:

| Item | Type | Required | Description
| --- | --- | --- | --- |
| model | string | Yes | table name of the model to be made `Verified` |
| model_id | int | Yes |  id of the model |
| otp | int | No \ Default: `null` | Auto Generated OTP |
| public_link | int  | No \ Default: `null` | Auto Generated Token for public link |
| expires_at | DateTime | Yes | indicated the expiration time of OTP |
| is_verified | tinyint \ to be used as boolean | No \ Default: `0` | Flag that tells the OTP is verified or not |
| verification_date_time | DateTime | No \ Default: `null` | Verification date time indication |
| additional_properties | json | No \ Default: `null` | Stores the additional info in json format |

### WFO Resend OTP
`Jgu\Wfotp\Models\WfoResendOtp`

Fields:

| Item | Type | Required | Description
| --- | --- | --- | --- |
| wfo_otp_id | int \ Relationship | Yes | Belongs To WFO OTP|
| date_time | DateTime | Yes | Stores resend otp date time |

### WFO Service
`Jgu\Wfotp\Models\WfoService`

Fields:

| Item | Type | Required | Description
| --- | --- | --- | --- |
| model | string | Yes | Model Path for which configuration need to define|
| no_of_resend_available | int | Yes | Number of otp can resend |
| expiration_time | int | Yes | OTP expiration time in minutes |
| is_whatsapp_accepts | tinyint \ to be used as boolean | No \ Default: `0` | Flag that tells if model can verify by whatsapp mode too |
| message_text | String | Yes | Text which will send to devices with $otp veriable to replace the dynamic otp |

### WFO Master Method
`Jgu\Wfotp\Models\WfoMasterMethod`

Fields:

| Item | Type | Required | Description
| --- | --- | --- | --- |
| method | string | Yes | Method by which model will verified such as OTP on Approval, Public Link Approval, Preemptive OTP|

### WFO Service Consume Method
`Jgu\Wfotp\Models\WfoServiceConsumeMethod`

Fields:

| Item | Type | Required | Description
| --- | --- | --- | --- |
| wfo_master_method_id | int \ Relationship | Yes | Belongs To WfoMasterMethod |
| wfo_service_id | int \ Relationship | Yes |  Belongs To WfoService |

### WFO Master Sms Service
`Jgu\Wfotp\Models\WfoMasterSmsService`

Fields:

| Item | Type | Required | Description
| --- | --- | --- | --- |
| service_name | string | Yes | Stores service name. We support aws sns for now |

### WFO Services Uses Sms Service
`Jgu\Wfotp\Models\WfoServiceUseSmsService`

Fields:

| Item | Type | Required | Description
| --- | --- | --- | --- |
| wfo_service_id | int \ Relationship | Yes |  Belongs To WfoService |
| master_sms_service_id | int \ Relationship | Yes |  Belongs To WfoMasterSmsService |

### Make a model to send and Verify OTP under WFO Package:

In the Eloquent Model file, use the Trait: SendOTP, Verification

```
use Jgu\Wfotp\Traits\SendOTP;
use Jgu\Wfotp\Traits\Verification;

class User extends Model
{
    use SendOTP, Verification;
    ...
    ...
    ...
```
## Use Below method to send OTP

`$user->resendOTP(12, '9999332859');` 

-   First parameter is model_id
-   Second parameter is mobile_number

Returns Boolean true/false as response

## Use Below method to verify OTP

`$user->verifyOTP(12, '1234');`
-   First parameter is model_id
-   Second parameter is otp

Returns Boolean true/false as response

### OTP Model Verification 
Override the following function in your model to get the class name 

```
public function getClassName(){
    return $this->className ?? get_class();
}
```