@extends('layouts.auth')

@section('content')
<approval-store-received-advice-component
:request="{{$request}}"
:role="'{{$role}}'"
:final="'{{$isFinalApprover}}'"
:completed="'{{$actionCompleted}}'"
></approval-store-received-advice-component>

@endsection
