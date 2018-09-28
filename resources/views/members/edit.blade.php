@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @forelse ($members as $member)
                <li>{{ $member->lastname }} {{ $member->firstname }}</li>
            @empty
                <p>No members</p>
            @endforelse
        </div>
        <div class="col-md-9">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{ Form::model($memberToUpdate, ['route' => ['members.update', $memberToUpdate->id]]) }}
                <div class="form-group row">
                    {{ Form::label('lastname', 'Nom', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('lastname', $memberToUpdate->lastname, ['class' => 'form-control']) }}
                    </div>
                    {{ Form::label('firstname', 'Prénom', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('firstname', $memberToUpdate->firstname, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('gender', 'Genre', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::select('gender', ['Male' => 'Homme', 'Female' => 'Femme'], $memberToUpdate->gender, ['class' => 'custom-select mr-sm-2']) }}
                    </div>
                    {{ Form::label('birthdate', 'Date Naiss', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::date('birthdate', $memberToUpdate->birthdate, ['class' => 'form-control']) }}
                        <small id="birthdateHelp" class="form-text text-muted">Exemple : 25/05/1985</small>
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('email', 'Email', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-11">
                        {{ Form::email('email', $memberToUpdate->email, ['class' => 'form-control']) }}
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
