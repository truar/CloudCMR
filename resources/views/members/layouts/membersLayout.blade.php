@extends('layouts.global')

@section('title', '- Membres')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('members.table.membersTable')
        @yield('membersForm')
    </div>
</div>
@endsection