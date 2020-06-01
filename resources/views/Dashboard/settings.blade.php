@extends('layouts.it')

@section('content')
 
    <settings-component :admin_email="'{{$email}}'"></settings-component>
    
@endsection