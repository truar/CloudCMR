@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('members.table.membersTable')
        @include('members.form.memberForm', ['route' => 'members.create'])
    </div>
</div>
@endsection
