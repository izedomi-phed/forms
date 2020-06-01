@extends('layouts.staff')

@section('content')

<form-store-received-advice-component
:email="'{{$email}}'"
:staff_id="'{{$staffId}}'"
:last_name="'{{$lastname}}'"
:first_name="'{{$firstname}}'"
:other_name="'{{$othername}}'"
:form_type="'{{$requestType}}'"
:form_id="'{{$formId}}'">
</form-store-received-advice-component>

@endsection
