@extends('layouts.auth')

@section('content')
<approval-stores-credit-note-component
:request="{{$request}}"
:role="'{{$role}}'"
:final="'{{$isFinalApprover}}'"
:completed="'{{$actionCompleted}}'"
></approval-stores-credit-note-component>

@endsection
