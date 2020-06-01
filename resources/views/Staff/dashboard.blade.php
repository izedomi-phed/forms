@extends('layouts.staff')

@section('content')
<div class="row justify-content-center mx-1 mb-5">
        <div class="col-md-12 text-center my-1">
            <h4 class="text-primary my-5"><strong> Welcome to PHED Forms portal</strong> </h4>
        </div>

        <div class="col-md-5 elevation-2 mr-2">
            <div class="row px-3 mb-2">

                <div class="col-md-12">
                    <div class="divider text-primary mb-2"><strong>PHED ACCESS FORMS</strong></div>

                </div>
            </div>
            <div class="row px-3 mt-4">
                <div class="col-md-12">

                    <p> With this feature, you can request for the various access forms</p>
                    <p> Currently availble forms:</p>
                    @foreach($forms as $form)
                        <p><a href="dashboard/{{$form->id}}/{{$form->title_slug}}"> {{$form->title}}</a></p>
                    @endforeach
                </div>

            </div>

        </div>

    </div>
@endsection
