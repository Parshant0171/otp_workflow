<!DOCTYPE html>
<html lang="en">
@php
  $approve = 1;
  $reject = -1;
  use Jgu\Wfotp\Traits\SendOTP;
  use Carbon\Carbon;
  $token = SendOTP::secret($link, "encrypt");
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>GatePassRequest Approval</title>
    <style>
        .body{
          position: center;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
        }

        @import url('https://fonts.googleapis.com/css?family=Lora');

        li {
            list-style-type: none;
        }

        .form-wrapper {
            margin: 50px auto 50px;
            font-family: 'Lora', serif;
            font-size: 1.09em;
            position: absolute;
            left: 50%;
            top: 30%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            border: 1px solid #28a745;
            border-radius: 5px;
            padding: 25px;
        }

        .form-wrapper.auth .form-title {
            color: #28a745;
        }

        h1,h2 {
           color: black;
        }
    </style>
</head>

<body>
    <div class="container" width=100% height=100%>
        <div class="row">
            <div class="col-md-4 offset-md-4 form-wrapper auth" style="margin-top: 250px;">
                <h3 class="text-center form-title">
                  <div class="w3-container w3-center">
                    <h1 style="font-size: 50px;">Gate Pass Request</h1>
                    <i class="fa fa-id-card"></i>
                    <!-- <h5>Student</h5> -->
                    <div>
                      <img src="{{'https://s3.ap-south-1.amazonaws.com/jgu-zero/'.$user->avatar}}" class="author-image" width=50% height=50%>
                      <h1>{{$user->name}}</h1>
                      <h2>Out Time : {{date('d/m/Y H:i:s', strtotime($gatePassRequest->out_date_time))}}</h2>
                      <h2>In Time : {{date('d/m/Y H:i:s', strtotime($gatePassRequest->in_date_time))}}</h2>
                      <h2>Purpose : {{$gatePassRequest->purpose}}</h2>
                    </div>
                    <div class="w3-section">
                      <a href="{{route('pass_request',[$token,$approve])}}" class="btn btn-success"><span>Approve</span></a>
                      <a href="{{route('pass_request',[$token,$reject])}}" class="btn btn-danger"><span>Reject</span></a>
                    </div>
                  </div>
                </h3>
            </div>
        </div>
    </div>
</body>