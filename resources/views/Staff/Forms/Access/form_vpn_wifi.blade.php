@extends('layouts.staff')

@section('content')

<form-vpn-wifi-component
:email="'{{$email}}'"
:staff_id="'{{$staffId}}'"
:staff_last_name="'{{$lastname}}'"
:staff_first_name="'{{$firstname}}'"
:staff_other_name="'{{$othername}}'"
:form_type="'{{$requestType}}'"
:form_id="'{{$formId}}'">
</form-vpn-wifi-component>

@endsection
