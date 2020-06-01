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

                        <p class="text-primary"><strong>{{$form_title}}</strong></p><br/>
                        <p> You are recieving this email with respect to a {{$form_title}} access request initiated by {{$staff_name}} with staff Id {{$staff_id}} </p>
                        <p> Your {{$form_title}} access request was declined by {{$approver_name_role}}</p>
                        <p> Reason for decline: {{$comment}}</p>

                        <p> Thanks.</p><br/>

                        <p>Best regards</p>
                        <p>I.T PHED </p>
                    </div>

                </div>
            @endif

            @if($approver_action == "ACCEPTED")

                <div class="content">
                    <div class="title m-b-md text-primary">

                        <p class="text-primary"><strong>{{$form_title}}</strong></p><br/>
                        <p> You are recieving this email with respect to a {{$form_title}} access request initiated by {{$staff_name}} with staff Id {{$staff_id}} </p>
                        <p> {{$form_title}} access request have been successfully created</p>

                        <p> Thanks.</p><br/>

                        <p>Best regards</p>
                        <p>I.T PHED </p>
                    </div>

                </div>

            @endif


        </div>

    <script src="{{ asset('assets/js/core.min.j') }}s"></script>
    <script src="{{ asset('assets/js/thesaas.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

</html>
