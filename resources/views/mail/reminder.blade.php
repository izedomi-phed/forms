<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PHED FORMS</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link href="{{ asset('assets/css/core.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/thesaas.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <!--
            Note: form_category is to be used to differentiate between incoming calls
          -->

           @if($send_to != null && $send_to == "TO_HOD")
              <div class="content">
                  <div class="title m-b-md text-primary">

                      <p class="text-primary"><strong>{{$form_title}} Access Requests</strong></p><br/>
                      <p> You are recieving this email with respect to a {{$form_title}} access request from a staff in your Department </p>
                      <p> In accordance to {{$form_title}} access request policies, you are required as the Head of Department, to verify the request and accept or decline as appropriate.</p>

                      <a href="{{$approval_link}}"> click here to see request details </a> OR
                      <a href="{{config('app.url')}}"> Login into your account to view request details</a>

                      <br/>
                      <p> Thanks.</p><br/>

                      <p>Best regards</p>
                      <p>I.T PHED </p>
                  </div>

              </div>
            @endif


            @if($send_to != null && $send_to == "OTHERS")
                <div class="content">
                    <div class="title m-b-md text-primary">

                        <p class="text-primary"><strong>{{$form_title}}</strong></p><br/>
                        <p> You are recieving this email with respect to a {{$form_title}} access request from a staff </p>
                        <p> In accordance to {{$form_title}} access request policies, you are required to verify the request and accept or decline as appropriate.</p>


                        <a href="{{$approval_link}}"> click here to see request details </a> OR
                        <a href="{{config('app.url')}}"> Login into your account to view request details</a>

                        <br/>
                        <p> Thanks.</p><br/>

                        <p>Best regards</p>
                        <p>I.T PHED </p>
                    </div>
                </div>
            @endif

            @if($send_to != null && $send_to == "TO_CREATE")
                <div class="content">
                    <div class="title m-b-md text-primary">

                        <p class="text-primary"><strong>{{$form_title}}</strong></p><br/>
                        <p> You are recieving this email with respect to a {{$form_title}} access request from a staff </p>
                        <p> Staff {{$form_title}} access request has been successfully approved by all the required stakeholders</p>

                        <p> Please do well to create the required account access</p>

                        <a href="{{$approval_link}}"> click here to see request details </a>

                        <br/>
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
