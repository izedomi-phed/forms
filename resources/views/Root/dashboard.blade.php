@extends('layouts.it')

@section('content')
 
    <!-- <requests-component :admin_email="'{{$email}}'"></requests-component> -->
    <root-component :admin_email="'{{$email}}'"></root-component>
    
@endsection