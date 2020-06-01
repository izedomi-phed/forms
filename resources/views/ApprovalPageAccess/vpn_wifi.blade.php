@extends('layouts.auth')

@section('content')

<approval-vpn-wifi-component
:request="{{$request}}"
:role="'{{$role}}'"
:final="'{{$isFinalApprover}}'"
:completed="'{{$actionCompleted}}'">
</approval-vpn-wifi-component>

@endsection
