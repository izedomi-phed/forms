<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PHED APPRAISALS</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link href="{{ asset('assets/css/core.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/thesaas.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    </head>
    <body>
        <div class="flex-center position-ref full-height">


            @if($approver_action == "DECLINED")
                <div class="content">
                    <div class="title m-b-md text-primary">

                        <p class="text-primary"><strong>FINANCE: {{strtoupper($form_title)}}</strong></p><br/>
                        <p> You are recieving this email with respect to {{$form_title}} Form initiated by {{$staff_name}} with staff Id {{$staff_id}} </p>
                        <p> The {{$form_title}} was declined by {{$approver_name_role}}</p>
                        <p> Reason for decline: {{$comment}}</p>

                        <p> Thanks.</p><br/>

                        <p>Best regards</p>
                        <p>FINANCE PHED </p>
                    </div>

                </div>
            @endif


            @if($approver_action == "ACCEPTED" && !$isFinalApprover)

                <div class="content">
                    <div class="title m-b-md text-primary">

                        <p class="text-primary"><strong>FINANCE: {{strtoupper($form_title)}}</strong></p><br/>
                        <p> You are recieving this email with respect to {{$form_title}} Form initiated by {{$staff_name}} with staff Id {{$staff_id}} </p>
                        <p> You are required to verify, and accept or decline as appropriate.</p>

                        <a href="{{$approver_link}}"> click here to see request details </a> OR
                        <a href="{{config('app.url')}}"> Login into your account to view request details</a>

                        <br/>
                        <p> Thanks.</p><br/>

                        <p>Best regards</p>
                        <p>FINANCE PHED </p>
                    </div>

                </div>

            @endif

            @if($approver_action == "ACCEPTED" && $isFinalApprover)

                <div class="content">
                    <div class="title m-b-md text-primary">

                        <p class="text-primary"><strong>FINANCE: {{strtoupper($form_title)}}}</strong></p><br/>
                        <p> You are recieving this email with respect to {{$form_title}} Form initiated by {{$staff_name}} with staff Id {{$staff_id}} </p>
                        <p> {{$form_title}} has been successfully approved by the final approver {{$approver_name_role}} </p>

                        <p> Thanks.</p><br/>

                        <p>Best regards</p>
                        <p>FINANCE PHED </p>
                    </div>

                </div>
            @endif


        </div>

    <script src="{{ asset('assets/js/core.min.j') }}s"></script>
    <script src="{{ asset('assets/js/thesaas.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

</html>
