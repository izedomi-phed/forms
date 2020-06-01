@extends('layouts.auth')

@section('content')
<approval-enhance-sage-component
:request="{{$request}}"
:role="'{{$role}}'"
:final="'{{$isFinalApprover}}'"
:completed="'{{$actionCompleted}}'"
></approval-enhance-sage-component>

@endsection
