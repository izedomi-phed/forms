@extends('layouts.auth')

@section('content')
<approval-stock-consignment-note-component
:request="{{$request}}"
:role="'{{$role}}'"
:final="'{{$isFinalApprover}}'"
:completed="'{{$actionCompleted}}'"
></approval-stock-consignment-note-component>

@endsection
