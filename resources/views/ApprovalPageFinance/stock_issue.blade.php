@extends('layouts.auth')

@section('content')
<approval-stock-issue-note-component
:request="{{$request}}"
:role="'{{$role}}'"
:final="'{{$isFinalApprover}}'"
:completed="'{{$actionCompleted}}'"
></approval-stock-issue-note-component>

@endsection
