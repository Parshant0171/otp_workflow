<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="main.css">
    <title>Verification</title>
    <style>
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
        }


        .form-wrapper {
            border: 1px solid #28a745;
            border-radius: 5px;
            padding: 25px;
        }

        .form-wrapper.auth .form-title {
            color: #28a745;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form-wrapper auth">
                <h3 class="text-center form-title">
                    @if($status === "verified")
                        Request has been Approved.
                    @endif

                    @if($status === "expired")
                        This Link has been expired.
                    @endif

                    @if($status === "Cancelled")
                        Request has been cancelled by Student.
                    @endif

                    @if($status === "Rejected")
                        <h1 style="color:red">Request has been Rejected.</h1>
                    @endif
                </h3>
            </div>
        </div>
    </div>
</body>

</html>