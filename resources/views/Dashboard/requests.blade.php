@extends('layouts.it')

@section('content')
 
    <requests-component :admin_email="'{{$email}}'"></requests-component>
    
@endsection