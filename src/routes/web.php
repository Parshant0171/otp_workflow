<?php

Route::group(['namespace' => 'Jgu\Wfotp\Http\Controllers', 'middleware' => ['web']], function(){
    Route::get('wfo-verify/{token?}', 'VerificationController@index')->where('token', '(.*)');
    Route::get('/pass-request/{token?}/{response}', 'VerificationController@gprApproveReject')->name('pass_request')->where('token', '(.*)');
});
