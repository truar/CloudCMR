@extends('members.layouts.membersLayout')

@section('membersForm')
    @include('members.form.memberForm', ['route' => 'members.create'])
@endsection

